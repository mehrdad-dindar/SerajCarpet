<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceItemResource\Pages;
use App\Filament\Resources\ServiceItemResource\RelationManagers;
use App\Models\ServiceItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceItemResource extends Resource
{
    protected static ?string $model = ServiceItem::class;

    protected static ?string $navigationGroup = 'Services Setting';
    protected static ?string $navigationLabel = 'آیتم‌های خدمات';
    protected static ?string $pluralModelLabel = "آیتم‌های خدمات";
    protected static ?string $modelLabel = 'آیتم خدمات';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('service_id')
                    ->relationship('service', 'name')
                    ->label('انتخاب خدمت')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label('عنوان آیتم')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('عنوان آیتم'),
                Tables\Columns\TextColumn::make('service.name')
                    ->label('خدمت')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'ترمیم' => 'warning',
                        'شستشو' => 'success',
                    }),
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
            'index' => Pages\ListServiceItems::route('/'),
            'create' => Pages\CreateServiceItem::route('/create'),
            'edit' => Pages\EditServiceItem::route('/{record}/edit'),
        ];
    }
}
