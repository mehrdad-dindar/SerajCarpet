<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use App\Services\InvoiceService;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);
        parse_str(parse_url(url()->previous(), PHP_URL_QUERY) ?? '', $filters);
        session()->put('orders_filters', $filters);
    }

    protected function afterSave()
    {
        $comment = $this->data['comment'] ?? null;
        if ($comment) {
            $this->record->comments()->create([
                'body' => $comment,
                'commenter_type' => Auth::user()::class,
                'commenter_id' => Auth::id(),
            ]);
            $this->dispatch('comment-added');
        }
    }
    protected function getFooterWidgets(): array
    {
        $order = $this->record;
        return [
            OrderResource\Widgets\OrderStatusHistoryWidget::make(['order' => $order]),
        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->icon('heroicon-o-trash'),
            Actions\Action::make('issue-invoice')
                ->label(__('Issue Invoice'))
                ->icon('heroicon-o-clipboard-document-list')
                ->color('primary')
                ->action(fn () => $this->issueInvoice())
                ->requiresConfirmation()
                ->modalHeading(__('Are you sure you want to issue an invoice for this order?')),
        ];
    }

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('index', session()->get('orders_filters', []));
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (isset($data['time_apply_status'])) {
            [$data['reservation_date'],$data['reservation_time']] = explode(' ', $data['time_apply_status']);
        }
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['reservation_date'], $data['reservation_time'])) {
            $data['time_apply_status'] = Carbon::parse($data['reservation_date'] . ' ' . $data['reservation_time']);
        }
        unset(
            $data['reservation_date'],
            $data['reservation_time'],
            $data['comment']
        );

        return $data;
    }

    public function issueInvoice(): void
    {
        try {
            /** @var \App\Models\Order $order */
            $order = $this->getRecord();

            ['invoice' => $invoice, 'created' => $created] = app(InvoiceService::class)->generate($order);

            Notification::make()
                ->title($created ? __('Invoice created') : __('Invoice updated'))
                ->body(__("Invoice ID: #:id - Amount: :amount", [
                    'id' => $invoice->id,
                    'amount' => number_format($invoice->amount),
                ]))
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title(__('Invoice creation failed'))
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
