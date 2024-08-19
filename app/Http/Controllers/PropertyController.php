<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PropertyController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $properties = Property::query()
            ->select('id','name','service_item_id','dimensions')
            ->when(
                $request->search,
                fn (Builder $query) => $query
                    ->whereHas('serviceItem.service', fn (Builder $q) =>
                    $q->where('name', 'like', "%{$request->search}%")
                    )->orWhereHas('serviceItem', fn (Builder $q) =>
                    $q->where('name', 'like', "%{$request->search}%")
                    )->orWhere('name', 'like', "%{$request->search}%")
            )
            ->when(
                $request->exists('selected'),
                fn (Builder $query) => $query->whereIn('id', $request->input('selected', [])),
                fn (Builder $query) => $query->limit(10)
            )
            ->get()
            ->map(function (Property $property) {
                return [
                    'id' => $property->id,
                    'fullTitle' => $property->fullTitle,
                    'dimensions' => $property->dimensions,
                ];
            });
        $this->getLocalDimensions($properties);
//        $this->dispatch('customerCreated', $customer->id);
        return $properties;
    }

    public function getDimensions(Property $property)
    {
        if ($property->dimensions){
            return $property->dimensions;
        }
        return [];
    }

    private function getLocalDimensions(Collection $properties)
    {
        $dimensions = [];
        foreach ($properties as $property) {
            $dimensions[] = [
                'id' => $property['id'],
                'dimensions' => $property['dimensions']
            ];
        }
    }
}
