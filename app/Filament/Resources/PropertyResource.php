<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertyResource\Pages;
use App\Filament\Resources\PropertyResource\RelationManagers;
use App\Models\Property;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables\Actions\Action;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $navigationGroup = 'Services Setting';
    protected static ?string $navigationLabel = 'ویژگی‌ها و قیمت‌گذاری';
    protected static ?string $pluralModelLabel = "ویژگی‌ها";
    protected static ?string $modelLabel = 'ویژگی';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('service_item_id')->relationship('serviceItem', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('parent_id')->relationship('parent', 'name'),
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\Select::make('unit')
                    ->required()
                    ->options([
                        'meter' => 'متر',
                        'takhte' => 'تخته',
                        'item' => 'عدد',
                    ])
                    ->searchable(),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->translateLabel()
                    ->integer()
                    ->mask(RawJs::make("\$money(\$input)"))
                    ->suffix('تومان')
                    ->stripCharacters('.')
                    ->mutateStateForValidationUsing(fn($state) => str_replace(',', '', $state))
                    ->mutateDehydratedStateUsing(fn($state) => str_replace(',', '', $state)),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('serviceItem.name'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('parent.name'),
                Tables\Columns\TextColumn::make('unit')
                    ->label('واحد'),
                Tables\Columns\TextColumn::make('price')
                    ->formatStateUsing(function ($state){
                        return number_format($state, 0) . ' تومان';
                    })
                    ->badge()
                    ->translateLabel()
                    ->sortable()
                    ->toggleable()
            ])
            ->filters([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }
}
