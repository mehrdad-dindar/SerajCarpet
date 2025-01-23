<?php

namespace App\Traits;

use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait Neshan
{
    public array $driverLocation = [];

    public static function reverseGeocoding($latitude, $longitude): JsonResponse
    {
        $apiKey = 'service.18c25979b1a74a46a31ddfe28a9bd8d8';
        $url = "https://api.neshan.org/v5/reverse?lat={$latitude}&lng={$longitude}";
        try {
            $response = Http::withHeaders([
                'Api-Key' => $apiKey,
            ])->get($url);

            $data = $response->json();

            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['error' . $e->getCode() => $e->getMessage()]);
        }
    }

    /**
     * Geocoding : Convert Address To Location
     *
     * @param string $address
     * @return array|null
     */
    public static function geocoding(string $address): ?array
    {
        $apiKey = 'service.18c25979b1a74a46a31ddfe28a9bd8d8';
        $url = "https://api.neshan.org/v6/geocoding?address=" . urlencode($address);
        try {
            $response = self::sendRequest($url, $apiKey);
            if (self::isValidResponse($response)) {
                return self::extractLocation($response);
            }
            return null;
        } catch (Exception $e) {
            Log::error('Geocoding failed: ' . $e->getMessage(), ['address' => $address]);
            return null;
        }
    }

    /**
     * @throws ConnectionException
     */
    protected static function sendRequest(string $url, string $apiKey): PromiseInterface|Response
    {
        return Http::withHeaders([
            'Api-Key' => $apiKey,
        ])->get($url);
    }

    protected static function isValidResponse($response): bool
    {
        return $response->successful() && isset($response['location']['x'], $response['location']['y']);
    }

    protected static function extractLocation($response): array
    {
        return [
            'latitude' => $response['location']['y'],
            'longitude' => $response['location']['x'],
        ];
    }

    public function salesman($points): JsonResponse
    {
        $apiKey = 'service.18c25979b1a74a46a31ddfe28a9bd8d8';
        $url = 'https://api.neshan.org/v3/trip?waypoints='
            . urlencode($this->getFormattedCoordinates($points))
            . '&roundTrip=false&sourceIsAnyPoint=false';
        try {
            $response = Http::withHeaders([
                'Api-Key' => $apiKey,
            ])->get($url);

            $data = $response->json();

            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['error' . $e->getCode() => $e->getMessage()]);
        }
    }

    protected function getFormattedCoordinates($points): string
    {
        $formattedCoordinates = $points->map(function ($point) {
            return "{$point['latitude']},{$point['longitude']}";
        })->implode('|');

        return $this->getStartLocation() . $formattedCoordinates;
    }

    protected function getStartLocation(): string
    {
        $location = settings()->factory_location;

        if ($this->driverLocation != []) {
            $location = $this->driverLocation;
        }

        return implode(',', $location) . '|';
    }

    public function showMap()
    {
        $apiKey = env('NESHAN_API_KEY', 'service.df64f13754cc4cde9c69362bed1a62c4');
        $url = "https://api.neshan.org/v4/static?key=$apiKey&type=neshan&width=500&height=500&zoom=12&center=32.657307%2C51.677579&markerToken=101139.nRmybq5";
    }
}
