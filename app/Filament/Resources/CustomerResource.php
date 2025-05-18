<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationGroup = 'Management';
    protected static ?string $navigationLabel = 'مشتریان';
    protected static ?string $pluralModelLabel = "مشتریان";
    protected static ?string $modelLabel = 'مشتری';
    protected static ?int $navigationSort = 2;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make("name")
                    ->label(__("Customer Name"))
                    ->required(),
                Forms\Components\TextInput::make("phone")
                    ->label(__("Customer Phone"))
                    ->unique(
                        table: 'customers',
                        column: 'phone',
                        ignorable: fn ($record) => $record
                    )
                    ->required(),
                Forms\Components\TextInput::make("phone2")
                    ->label(__("Customer's second contact number")),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('شناسه مشتری')->sortable(),
                Tables\Columns\TextColumn::make("name")->label('نام')->searchable(),
                Tables\Columns\TextColumn::make("phone")->label('شماره تماس')->searchable(),
                Tables\Columns\TextColumn::make("created_at")->label('تاریخ عضویت'),
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            RelationManagers\OrdersRelationManager::class,
            RelationGroup::make('پرداخت‌ها', [
                RelationManagers\InvoicesRelationManager::class,
                RelationManagers\TransactionsRelationManager::class
            ])->icon('heroicon-o-banknotes'),
            RelationManagers\AddressRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
