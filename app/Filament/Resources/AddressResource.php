<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AddressResource\Pages;
use App\Filament\Resources\AddressResource\RelationManagers;
use App\Filament\Resources\AddressResource\RelationManagers\CustomerRelationManager;
use App\Models\Address;
use Dotswan\MapPicker\Fields\Map;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddressResource extends Resource
{
    protected static ?string $model = Address::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label(__('Customer Name')),
                Forms\Components\TextInput::make('state')
                    ->required()
                    ->label(__('State')),
                Forms\Components\TextInput::make('city')
                    ->required()
                    ->label(__('City')),
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->label(__('Full Address')),
                Forms\Components\TextInput::make('lat')
                    ->required()
                    ->label(__('Latitude')),
                Forms\Components\TextInput::make('lng')
                    ->required()
                    ->label(__('longitude')),
                Forms\Components\Checkbox::make('is_active')
                    ->label(__('Active'))
                    ->default(true),
                Map::make('address')
                    ->hint('salam in hintesh')
                    ->label('Location')
                    ->columnSpanFull()
                    ->afterStateUpdated(function (Get $get, Set $set, string|array|null $old, ?array $state): void {
                        $set('lat', $state['lat']);
                        $set('lng', $state['lng']);
                    })
                    ->extraStyles([
                        'min-height: 50vh',
                        'border-radius: 16px'
                    ])
                    ->liveLocation()
                    ->showMarker()
                    ->markerColor("#22c55eff")
                    ->showFullscreenControl()
                    ->showZoomControl()
                    ->draggable()
                    ->detectRetina()
                    ->showMyLocationButton()
                    ->extraControl([
                        'zoomDelta' => 1,
                        'zoomSnap' => 2,
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\CheckboxColumn::make('is_active')->sortable(),
                Tables\Columns\TextColumn::make('customer.name')->searchable(),
                Tables\Columns\TextColumn::make('state'),
                Tables\Columns\TextColumn::make('city'),
                Tables\Columns\TextColumn::make('address')->toggleable()->searchable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                ->label(__('Active Address')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CustomerRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAddresses::route('/'),
            'create' => Pages\CreateAddress::route('/create'),
            'edit' => Pages\EditAddress::route('/{record}/edit'),
        ];
    }
}
