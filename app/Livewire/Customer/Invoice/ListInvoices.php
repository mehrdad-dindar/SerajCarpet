<?php

namespace App\Livewire\Customer\Invoice;

use App\Models\Invoice;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Colors\Color;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class ListInvoices extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $invoices;

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->query(Invoice::whereIn('id', $this->invoices->pluck('id')))
            ->columns([
                TextColumn::make('id')
                    ->label(__('Invoice Id'))
                    ->prefix('#'),
                TextColumn::make('order.id')
                    ->prefix('#')
                    ->url(function (Model $record): string {
                        return route('customer.panel.order.show', $record->order);
                    })
                    ->description(fn (Model $record): ?string => verta($record->created_at)->format('(d F Y)'))
                    ->label(__('Order Id')),
                TextColumn::make('amount')
                    ->numeric()
                    ->suffix(' تومان')
                    ->label(__('Order Total')),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->formatStateUsing(function($state) {
                        return __('invoice.status.' . $state);
                    })
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'paid' => 'success',
                        'canceled' => 'danger',
                        'pending' => 'warning'
                    }),
                TextColumn::make('expire_at')
                    ->jalaliDate('d F Y - H:i')
                    ->translateLabel(),
                TextColumn::make('created_at')
                    ->jalaliDate('d F Y')
                    ->translateLabel(),
            ])
            ->filters([
            ])
            ->actions([
                Action::make('مشاهده')
                    ->link()
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => route('customer.panel.invoice.show', $record))
                    ->color('primary'),
                Action::make('پرداخت')
                    ->visible(fn ($record) => $record->status == 'pending')
                    ->button()
                    ->icon('heroicon-o-banknotes')
                    ->url(fn ($record) => route('customer.panel.invoice.purchase', $record))
                    ->color(Color::Lime),
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        return view('livewire.customer.invoice.list-invoices');
    }
}
