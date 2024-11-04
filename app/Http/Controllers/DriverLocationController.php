<?php

namespace App\Http\Controllers;

use App\Events\DriverLocationUpdated;
use App\Models\DriverLocation;
use Illuminate\Http\Request;

class DriverLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DriverLocation $driverLocation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DriverLocation $driverLocation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DriverLocation $driverLocation)
    {
        //
    }

    public function updateLocation(Request $request)
    {
        $driver = auth('driver')->user();
        $driver->location()->updateOrCreate(
            [],
            [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]
        );

        // انتشار رویداد برای کلاینت‌ها
        event(new DriverLocationUpdated($driver->id, $request->latitude, $request->longitude));

        return response()->json(['message' => 'Location updated successfully.']);
    }

}
