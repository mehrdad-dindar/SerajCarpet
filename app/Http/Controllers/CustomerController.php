<?php

namespace App\Http\Controllers;

use App\Traits\Neshan;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    use Neshan;
    public function getFullAddress(Request $request)
    {
        $latitude = $request->latitude;
        $longitude = $request->longitude;

        return $this->reverseGeocoding($latitude, $longitude);
    }
}
