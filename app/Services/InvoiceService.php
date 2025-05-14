<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class InvoiceService
{
    /**
     * Generate an invoice for the given order.
     *
     * @param Order $order
     * @return Invoice
     *
     * @throws Exception
     */
    public function generate(Order $order): array
    {
        return DB::transaction(function () use ($order) {
            $invoiceData = [
                'amount' => $order->total,
                'status' => 'pending',
                'expire_at' => Carbon::now()->addDays(7),
            ];

            if ($order->invoice()->exists()) {
                $order->invoice()->update($invoiceData);
                return [
                    'invoice' => $order->invoice()->first(),
                    'created' => false,
                ];
            }

            $invoice = $order->invoice()->create($invoiceData);

            return [
                'invoice' => $invoice,
                'created' => true,
            ];
        });
    }
}
