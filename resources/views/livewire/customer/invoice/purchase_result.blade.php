<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>نتیجه پرداخت</title>
    <script src="https://cdn.tailwindcss.com "></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
<div class="max-w-lg w-full bg-white shadow-xl rounded-2xl overflow-hidden">
    @if($status === 1)
        <!-- Success -->
        <div class="p-6 text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">پرداخت موفق</h2>
            <p class="text-gray-600 mb-6">پرداخت شما با موفقیت انجام شد.</p>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-right text-sm text-blue-800 mb-6">
                <p>شماره مرجع: <strong>{{ $reference_id }}</strong></p>
                <p>صورتحساب: <a href="{{route('customer.panel.invoice.show', [$invoice])}}">#{{ $invoice->id }}</a></p>
                <p>مبلغ: <strong>{{ number_format($invoice->amount) }} تومان</strong></p>
            </div>

            <a href="{{ route('customer.panel.invoices') }}" class="inline-block w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200">
                بازگشت به لیست صورتحساب ها
            </a>
        </div>
    @else
        <!-- Failed -->
        <div class="p-6 text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">خطا در پرداخت</h2>
            <p class="text-gray-600 mb-6">متاسفانه پرداخت شما انجام نشد.</p>

            <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-right text-sm text-red-800 mb-6">
                @if(isset($message))
                    {{ $message }}
                @else
                    لطفاً مجدداً تلاش کنید یا با پشتیبانی تماس بگیرید.
                @endif
            </div>

            <a href="{{ route('customer.panel.invoices') }}" class="inline-block w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200">
                بازگشت به لیست صورتحساب ها
            </a>
        </div>
    @endif
</div>
</body>
</html>
