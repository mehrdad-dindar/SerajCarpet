<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddressRelationManager extends RelationManager
{
    protected static string $relationship = 'address';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('city')
            ->columns([
                Tables\Columns\CheckboxColumn::make('is_active')->sortable(),
                Tables\Columns\TextColumn::make('state'),
                Tables\Columns\TextColumn::make('city'),
                Tables\Columns\TextColumn::make('address'),
                Tables\Columns\TextColumn::make('lat')->toggleable(),
                Tables\Columns\TextColumn::make('lng')->toggleable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
