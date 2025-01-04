<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Traits\Neshan;
use Hashids\Hashids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    use Neshan;

    public function __invoke(Request $request): Collection
    {
        $data = Customer::query()
            ->select('id', 'id_name')
            ->when(
                $request->search,
                fn (Builder $query) => $query
                    ->where('id_name', 'like', "%{$request->search}%")
            )
            ->when(
                $request->exists('selected'),
                fn (Builder $query) => $query->whereIn('id', $request->input('selected', [])),
                fn (Builder $query) => $query->limit(10)
            )
            ->orderBy('id_name')
            ->get();
        return $data;
        /*->map(function (Customer $customer) {
            $customer->profile_image = "https://picsum.photos/300?id={$customer->id}";

            return $customer;
        })*/
    }
    public function getFullAddress(Request $request)
    {
        $latitude = $request->latitude;
        $longitude = $request->longitude;

        return $this->reverseGeocoding($latitude, $longitude);
    }

    public function createAddress(Request $request)
    {
        $hashId = new Hashids('', 6);
        $customerID = $hashId->decode($request->id)[0];
        $customer = Customer::findOrFail($customerID);
        if ($customer) {
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
