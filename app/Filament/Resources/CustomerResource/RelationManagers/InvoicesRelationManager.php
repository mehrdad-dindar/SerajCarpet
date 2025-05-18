<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Services\InvoiceService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoicesRelationManager extends RelationManager
{
    protected static string $relationship = 'invoices';
    protected static ?string $label = 'صورتحساب';
    protected static ?string $title = 'صورتحساب‌ها';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('Invoice Id'))
                    ->prefix('#')
                    ->searchable(),
                Tables\Columns\TextColumn::make('order.id')
                    ->label(__('Order Id'))
                    ->prefix('#')
                    ->url(fn (Model $record) => route('filament.admin.resources.orders.edit', $record->order))
                    ->searchable(),
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
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
