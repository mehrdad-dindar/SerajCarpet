<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Transaction;
use Exception;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Exceptions\PurchaseFailedException;
use Shetabit\Multipay\Invoice as ShetabitInvoice;
use Illuminate\Http\Request;
use Shetabit\Payment\Facade\Payment;
use SoapFault;

class InvoiceController extends Controller
{
    public Customer $customer;
    public function __construct()
    {
        $this->customer = auth()->guard('customer')->user();
    }
    public function index()
    {
        $invoices = $this->customer->invoices;
    }

    public function purchase(Invoice $invoice)
    {
        try {
            $ShetabitInvoice = new ShetabitInvoice();
            $ShetabitInvoice->amount(intval($invoice->amount));
            $paymentId = md5(uniqid());
            $transaction = $this->customer->transactions()->create([
                'invoice_id' => $invoice->id,
                'paid' => $ShetabitInvoice->getAmount(),
                'invoice_details' => $ShetabitInvoice,
                'payment_id' => $paymentId,
            ]);

            $callbackUrl = route("customer.panel.invoice.purchase.result", [$invoice, 'payment_id' => $paymentId]);
            $payment = Payment::callbackUrl($callbackUrl);
            $payment->config('description', 'پرداخت صورتحساب به شماره #'.$invoice->id);
            $payment->purchase(
                $ShetabitInvoice,
                function ($driver, $transactionId) use ($transaction) {
                    $transaction->transaction_id = $transactionId;
                    $transaction->save();
                }
            );
            return $payment->pay()->render();
        } catch (PurchaseFailedException|Exception|SoapFault $e) {
            dd($e->getMessage());
            $transaction->transaction_result = $e->getMessage();
            $transaction->status = Transaction::STATUS_FAILED;
            $transaction->save();
        }
    }

    public function result(Request $request, Invoice $invoice)
    {
        if ($request->missing('payment_id')) {
            return view('livewire.customer.invoice.purchase_result')->with('status', 'failed');
        }

        $transaction = Transaction::where('payment_id', $request->payment_id)->first();

        if (empty($transaction)) {
            return view('livewire.customer.invoice.purchase_result')->with('status', 'failed');
        }

        if ($transaction->customer_id <> $this->customer->id) {
            return view('livewire.customer.invoice.purchase_result')->with('status', 'failed');
        }

        if ($transaction->invoice_id <> $invoice->id) {
            return view('livewire.customer.invoice.purchase_result')->with('status', 'failed');
        }

        if ($transaction->status <> Transaction::STATUS_PENDING) {
            return view('livewire.customer.invoice.purchase_result')->with('status', 'failed');
        }

        try {
            $reciept = Payment::amount($transaction->paid)
                ->transactionId($transaction->transaction_id)
                ->verify();

            $transaction->transaction_result = $reciept;
            $transaction->status = Transaction::STATUS_SUCCESS;
            $transaction->save();

            $invoice->status = 'paid';
            $invoice->save();
            // Todo : اس ام اس تایید پرداخت

            return view('livewire.customer.invoice.purchase_result')->with([
                'status' => 1,
                'reference_id' => $reciept->getReferenceId(),
                'invoice' => $invoice
            ]);
        } catch (Exception|InvalidPaymentException $e) {
            if ($e->getCode() < 0) {
                $transaction->status = Transaction::STATUS_FAILED;
                $transaction->transaction_result = [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ];
                $transaction->save();
                return view('livewire.customer.invoice.purchase_result')->with([
                    'status' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]);
            }
        }
    }
}
