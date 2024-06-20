<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Traits\Neshan;
use Hashids\Hashids;
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

    public function createAddress(Request $request)
    {
        $hashid = new Hashids('',6);
        $customerID = $hashid->decode($request->id)[0];
        $customer = Customer::findOrFail($customerID);
        if ($customer){
            $customer->addresses()->create([
                'state' => $request->state,
                'city' => $request->city,
                'address' => $request->address,
                'no' => $request->no,
                'note' => $request->note,
                'is_active' => true,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);
            return response()->json(['message' => 'Address created successfully'], 200);
        } else {
            return response()->json(['message' => 'Customer not found'], 404);
        }
    }
}
