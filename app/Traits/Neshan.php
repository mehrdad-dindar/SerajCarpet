<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Http;

trait Neshan
{
    public function reverseGeocoding($latitude, $longitude)
    {
        $apiKey = env('NESHAN_API_KEY');
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
}
