<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Http;

trait Neshan
{
    public static function reverseGeocoding($latitude, $longitude)
    {
        $apiKey = env('NESHAN_API_KEY','service.df64f13754cc4cde9c69362bed1a62c4');
        $url = "https://api.neshan.org/v5/reverse?lat={$latitude}&lng={$longitude}";
        try {
            $response = Http::withHeaders([
                'Api-Key' => $apiKey,
            ])->get($url);

            $data = $response->json();
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['error'. $e->getCode() => $e->getMessage()]);
        }
    }

    public function salesman($points)
    {
        $apiKey = env('NESHAN_API_KEY','service.df64f13754cc4cde9c69362bed1a62c4');
        $url = "https://api.neshan.org/v3/trip?waypoints=". urlencode($this->getFormattedCoordinates($points)) . "&sourceIsAnyPoint=false";
        try {
            $response = Http::withHeaders([
                'Api-Key' => $apiKey,
            ])->get($url);

            $data = $response->json();
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['error'. $e->getCode() => $e->getMessage()]);
        }
    }

    protected function getFormattedCoordinates($points)
    {
        $factoryLocation = "";
        if (isset(settings()->location_latitude) && isset(settings()->location_longitude)) {
            $factoryLocation = settings()->location_latitude . ',' . settings()->location_longitude . '|';
        }
        $formattedCoordinates = $points->map(function($point) {
            return "{$point['latitude']},{$point['longitude']}";
        })->implode('|');

        return $factoryLocation . $formattedCoordinates;
    }
    public function showMap()
    {
        $apiKey = env('NESHAN_API_KEY','service.df64f13754cc4cde9c69362bed1a62c4');
        $url = "https://api.neshan.org/v4/static?key=$apiKey&type=neshan&width=500&height=500&zoom=12&center=32.657307%2C51.677579&markerToken=101139.nRmybq5";
    }
}
