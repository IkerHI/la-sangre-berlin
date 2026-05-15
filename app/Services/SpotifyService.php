<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class SpotifyService
{
    private string $clientId;
    private string $clientSecret;
    private string $baseUrl = 'https://api.spotify.com/v1';

    public function __construct()
    {
        $this->clientId     = config('services.spotify.client_id', '');
        $this->clientSecret = config('services.spotify.client_secret', '');
    }

    private function getAccessToken(): ?string
    {
        return Cache::remember('spotify_token', 3500, function () {
            if (empty($this->clientId) || empty($this->clientSecret)) {
                return null;
            }

            $response = Http::asForm()
                ->withBasicAuth($this->clientId, $this->clientSecret)
                ->post('https://accounts.spotify.com/api/token', [
                    'grant_type' => 'client_credentials',
                ]);

            return $response->successful() ? $response->json('access_token') : null;
        });
    }

    public function getArtistTracks(string $artistId): array
    {
        $token = $this->getAccessToken();
        if (!$token) return [];

        $albumsResp = Http::withToken($token)
            ->get("{$this->baseUrl}/artists/{$artistId}/albums", [
                'include_groups' => 'album,single',
                'limit' => 50,
            ]);

        if (!$albumsResp->successful()) return [];

        $tracks = [];
        foreach ($albumsResp->json('items', []) as $album) {
            $tracksResp = Http::withToken($token)
                ->get("{$this->baseUrl}/albums/{$album['id']}/tracks", ['limit' => 50]);

            if ($tracksResp->successful()) {
                foreach ($tracksResp->json('items', []) as $track) {
                    $tracks[] = [
                        'id'           => $track['id'],
                        'name'         => $track['name'],
                        'duration_ms'  => $track['duration_ms'],
                        'preview_url'  => $track['preview_url'],
                        'spotify_url'  => $track['external_urls']['spotify'] ?? null,
                        'album_name'   => $album['name'],
                        'album_cover'  => $album['images'][0]['url'] ?? null,
                        'embed_url'    => "https://open.spotify.com/embed/track/{$track['id']}",
                    ];
                }
            }
        }

        return $tracks;
    }

    public function getArtistPlaylistEmbed(string $artistId): string
    {
        return "https://open.spotify.com/embed/artist/{$artistId}?utm_source=generator";
    }
}
