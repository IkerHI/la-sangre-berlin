<?php

namespace App\Services;

use App\Models\Concert;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BandsintownService
{
    private string $appId;
    private string $artist;
    private string $baseUrl = 'https://rest.bandsintown.com';

    public function __construct()
    {
        $this->appId  = config('services.bandsintown.app_id', 'cd02bab338a157051f7ae53302f7552c');
        $this->artist = config('services.bandsintown.artist', 'la%20sangre');
    }

    public function syncConcerts(): array
    {
        $encodedArtist = rawurlencode($this->artist);
        $response = Http::get("{$this->baseUrl}/artists/{$encodedArtist}/events", [
            'app_id' => $this->appId,
        ]);

        if (!$response->successful()) {
            Log::error('Bandsintown sync failed', [
                'artist'   => $this->artist,
                'status'   => $response->status(),
                'response' => $response->body(),
            ]);
            return ['synced' => 0, 'error' => 'API request failed (HTTP ' . $response->status() . ')'];
        }

        $events  = $response->json();
        $synced  = 0;

        if (!is_array($events)) {
            Log::error('Bandsintown returned unexpected response', ['body' => $response->body()]);
            return ['synced' => 0, 'error' => 'Unexpected API response'];
        }

        foreach ($events as $event) {
            $venue  = $event['venue'] ?? [];
            $date   = $event['datetime'] ?? null;

            if (!$date) continue;

            $status = now()->gt($date) ? 'past' : 'upcoming';

            Concert::updateOrCreate(
                ['bandsintown_id' => (string) $event['id']],
                [
                    'date'       => $date,
                    'venue_name' => $venue['name']    ?? 'TBD',
                    'city'       => $venue['city']    ?? '',
                    'country'    => $venue['country'] ?? '',
                    'ticket_url' => $event['offers'][0]['url'] ?? null,
                    'status'     => $status,
                ]
            );

            $synced++;
        }

        return ['synced' => $synced];
    }
}
