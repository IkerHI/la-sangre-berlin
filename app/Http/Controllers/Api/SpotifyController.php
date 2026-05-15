<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SpotifyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class SpotifyController extends Controller
{
    public function __construct(private SpotifyService $spotify) {}

    public function tracks(): JsonResponse
    {
        $artistId = config('services.spotify.artist_id', '');

        if (empty($artistId)) {
            return response()->json([
                'embed_url' => null,
                'tracks'    => [],
            ]);
        }

        $data = Cache::remember("spotify_tracks_{$artistId}", 3600, function () use ($artistId) {
            return [
                'embed_url' => $this->spotify->getArtistPlaylistEmbed($artistId),
                'tracks'    => $this->spotify->getArtistTracks($artistId),
            ];
        });

        return response()->json($data);
    }
}
