<?php
namespace App\Services;
use App\Core\Database;

class MapsService
{
    private string $apiKey;

    public function __construct()
    {
        $cfg          = require BASE_PATH . '/config/payment.php';
        $this->apiKey = $cfg['google_maps']['key'] ?? '';
    }

    public function getRouteInfo(string $origin, string $destination): ?array
    {
        // Check cache
        $cacheKey = md5("route:{$origin}:{$destination}");
        try {
            $cached = Database::fetchOne(
                "SELECT data FROM route_cache WHERE cache_key=? AND expires_at>NOW()", [$cacheKey]
            );
            if ($cached) return json_decode($cached['data'], true);
        } catch (\Exception $e) { /* ignore cache errors */ }

        if (!$this->apiKey || str_contains($this->apiKey, 'YOUR_')) return null;

        $url = 'https://maps.googleapis.com/maps/api/distancematrix/json?' . http_build_query([
            'origins'      => $origin . ', Indonesia',
            'destinations' => $destination . ', Indonesia',
            'mode'         => 'driving',
            'language'     => 'id',
            'key'          => $this->apiKey,
        ]);

        $ctx      = stream_context_create(['http' => ['timeout' => 5]]);
        $response = @file_get_contents($url, false, $ctx);
        if (!$response) return null;

        $data    = json_decode($response, true);
        $element = $data['rows'][0]['elements'][0] ?? null;
        if (!$element || $element['status'] !== 'OK') return null;

        $result = [
            'distance_text' => $element['distance']['text'],
            'distance_m'    => $element['distance']['value'],
            'duration_text' => $element['duration']['text'],
            'duration_min'  => (int)round($element['duration']['value'] / 60),
        ];

        try {
            Database::query(
                "INSERT INTO route_cache (cache_key,data,expires_at) VALUES (?,?,DATE_ADD(NOW(),INTERVAL 24 HOUR))
                 ON DUPLICATE KEY UPDATE data=VALUES(data),expires_at=VALUES(expires_at)",
                [$cacheKey, json_encode($result)]
            );
        } catch (\Exception $e) { /* ignore */ }

        return $result;
    }

    public function estimatePrice(int $distanceM, string $vehicleType): int
    {
        $rates = ['car' => 2500, 'minibus' => 1800, 'bus' => 1200];
        return (int)(ceil(($distanceM / 1000) * ($rates[$vehicleType] ?? 2000) / 1000) * 1000);
    }
}
