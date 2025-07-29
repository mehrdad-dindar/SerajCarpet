<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationGroup = 'Management';

    protected static ?string $navigationLabel = 'صورتحساب‌ها';

    protected static ?string $pluralModelLabel = 'صورتحساب‌ها';

    protected static ?string $modelLabel = 'صورتحساب';

    protected static ?int $navigationSort = 2;

    //    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('information')
                    ->schema([
                        Forms\Components\Placeholder::make('order')
                            ->columnSpan(1)
                            ->content(fn (Invoice $record) => '#'.$record->order->id)
                            ->key(fn (Invoice $record) => '#'.$record->order->id)
                            ->hintAction(
                                Action::make('Show order')
                                    ->translateLabel()
                                    ->icon('heroicon-s-eye')
                                    ->url(
                                        fn (Invoice $record) =>
                                        route('filament.admin.resources.orders.edit', $record->order->id),
                                        true
                                    )
                            )
                            ->label(__('Order ID')),
                        Forms\Components\Placeholder::make('customer_name')
                            ->columnSpan(1)
                            ->label(__('Customer Name'))
                            ->content(fn (Invoice $record) => $record->customer->name),
                        Forms\Components\Fieldset::make('customer_phone')
                            ->columnSpan(2)
                            ->schema([
                                Forms\Components\Placeholder::make('customer_phone')
                                    ->label(__('Customer Phone'))
                                    ->hintAction(
                                        Action::make('call')
                                            ->translateLabel()
                                            ->icon('heroicon-s-sparkles')
                                            ->url(fn (Invoice $record) => 'tel:+98'. intval($record->customer->phone))
                                    )
                                    ->content(fn (Invoice $record) => $record->customer->phone),
                                Forms\Components\Placeholder::make('customer_phone2')
                                    ->label(__("Customer's second contact number"))
                                    ->visible(fn (Invoice $record) => $record->customer?->phone2)
                                    ->hintAction(
                                        Action::make('call')
                                            ->translateLabel()
                                            ->icon('heroicon-s-sparkles')
                                            ->url(fn (Invoice $record) => 'tel:+98'. intval($record->customer->phone2))
                                    )
                                    ->content(fn (Invoice $record) => $record->customer->phone2),
                            ])
                            ->label(__('Customer Phone')),
                        Forms\Components\Fieldset::make('Address Info')
                            ->schema([
                                Forms\Components\Placeholder::make('address')
                                    ->label(__('Full Address'))
                                    ->content(fn (Invoice $record) => $record->order->address->getFullAddress())
                            ])
                            ->translateLabel(),
                    ])
                    ->columns(4),
                Forms\Components\TextInput::make('amount')
                    ->translateLabel()
                    ->required()
                    ->mask(RawJs::make('$money($input)'))
                    ->suffix('تومان'),
                Forms\Components\Select::make('status')
                    ->translateLabel()
                    ->options([
                        'paid' => __('invoice.status.paid'),
                        'pending' => __('invoice.status.pending'),
                        'canceled' => __('invoice.status.canceled')
                    ])
                    ->native(false)
                    ->required(),
                Forms\Components\DateTimePicker::make('expire_at')
                    ->jalali()
                    ->translateLabel()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('order.id')
                    ->label(__("Order ID"))
                    ->prefix('#')
                    ->url(fn (Model $record) => route('filament.admin.resources.orders.edit', $record->order))
                    ->sortable(),
                Tables\Columns\TextColumn::make('order.customer.name')
                    ->label('Name')
                    ->searchable(['name', 'phone', 'phone2'])
                    ->translateLabel()
                    ->url(function (Model $record): string {
                        return route('filament.admin.resources.customers.edit', $record->order->customer_id);
                    })
                    ->description(fn (Model $record): ?string => $record->order->customer->phone)
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__("Amount"))
                    ->numeric(locale: 'en')
                    ->suffix(' تومان')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->translateLabel()
                    ->formatStateUsing(function ($state) {
                        return __('invoice.status.' . $state);
                    })
                    ->badge()
                    ->sortable()
                    ->color(fn ($state) => match ($state) {
                        'paid' => 'success',
                        'canceled' => 'danger',
                        'pending' => 'warning'
                    }),
                Tables\Columns\TextColumn::make('expire_at')
                    ->sortable()
                    ->jalaliDate('d F Y - H:i')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('created_at')
                    ->translateLabel()
                    ->jalaliDate('d F Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->native(false)
                    ->options([
                        'paid' => __('invoice.status.paid'),
                        'pending' => __('invoice.status.pending'),
                        'canceled' => __('invoice.status.canceled'),
                    ])
                    ->translateLabel(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('issue-invoice')
                    ->hidden(fn (Model $record) => $record->status === 'paid')
                    ->label(__('Reissuance of invoice'))
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->action(fn (Model $record) => app(InvoiceService::class)->issueInvoice($record->order))
                    ->requiresConfirmation()
                    ->modalHeading(__('Are you sure you want to issue an invoice for this order?')),
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return parent::getWidgets();
    }
}
