<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Shetabit\Multipay\Receipt;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';

    protected static ?string $label = 'تراکنش';
    protected static ?string $title = 'تراکنش‌ها';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('payment_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at','desc')
            ->recordTitleAttribute('payment_id')
            ->columns([
                Tables\Columns\TextColumn::make('payment_id')
                    ->translateLabel()
                    ->formatStateUsing(fn (string $state): string => substr($state, 0, 8) . '...' . substr($state, -4))
                    ->tooltip(fn ($state) => $state)
                    ->searchable(),
                Tables\Columns\TextColumn::make('invoice.id')
                    ->label(__('Invoice Id'))
                    ->prefix("#")
                    ->url(fn (Model $record) => route('filament.admin.resources.invoices.edit', $record->invoice))
                    ->searchable(),
                Tables\Columns\TextColumn::make('order.id')
                    ->label(__('Order ID'))
                    ->prefix("#")
                    ->url(fn (Model $record) => route('filament.admin.resources.orders.edit', $record->order))
                    ->searchable(),
                Tables\Columns\TextColumn::make('paid')
                    ->translateLabel()
                    ->numeric(locale: 'en')
                    ->suffix(' تومان'),
                Tables\Columns\TextColumn::make('transaction_id')
                    ->formatStateUsing(fn (string $state): string => substr($state, 0, 8) . '...' . substr($state, -4))
                    ->tooltip(fn ($state) => $state)
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('status')
                    ->translateLabel()
                    ->formatStateUsing(function ($state) {
                        return __('transaction.status.' . $state);
                    })
                    ->badge()
                    ->sortable()
                    ->color(fn ($state) => match ($state) {
                        0 => 'danger',
                        1 => 'warning',
                        2 => 'success'
                    }),
                Tables\Columns\TextColumn::make('transaction_result')
                    ->translateLabel()
                    ->tooltip(function ($state) {
                        if (! $state instanceof Receipt) {
                            if ($state) {
                                return $state['message'];
                            }
                            return null;
                        }

                        return collect([
                            'درگاه'        => $state->getDriver(),
                            'شماره ارجاع'  => $state->getReferenceId(),
                            'وضعیت'        => $state->getDetail('message') ?? '',
                            'کد پاسخ'      => $state->getDetail('code') ?? '',
                            'کارت'         => $state->getDetail('card_pan') ?? '',
                            'کارمزد (ریال)' => number_format($state->getDetail('fee') ?? 0),
                            'تاریخ'        => optional($state->getDate())->toDateTimeString(),
                        ])
                            ->map(fn ($val, $key) => "$key: $val")
                            ->implode("\n");
                    })
                    ->extraAttributes([
                        'style' => 'white-space: pre-line; direction: rtl; text-align: right;',
                    ])
                    ->formatStateUsing(function ($state) {
                        if ($state instanceof Receipt) {
                            return $state->getDriver();
                        }
                        return 'نامشخص';
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->translateLabel()
                    ->formatStateUsing(fn ($state) => verta($state)->format('Y/m/d H:i')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
//                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
//                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
