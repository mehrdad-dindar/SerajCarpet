<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-white to-primary-400 px-6 py-8 text-white text-center">
            <h2 class="text-2xl font-bold">پرداخت هزینه خدمات</h2>
            <p class="mt-2 font-semibold">جزئیات فاکتور و روش‌های پرداخت</p>
        </div>

        <!-- Invoice Details -->
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg border">
                    <h3 class="text-sm text-gray-500 uppercase tracking-wider">شماره فاکتور</h3>
                    <p class="text-lg font-semibold">#{!! $invoice->id !!}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg border">
                    <h3 class="text-sm text-gray-500 uppercase tracking-wider">تاریخ صدور</h3>
                    <p class="text-lg font-semibold">{!! verta($invoice->updated_at)->format('d F Y') !!}</p>
                </div>
            </div>
            <div class="border-t pt-4">
                <h3 class="text-lg font-medium text-gray-800 mb-3">خدمات انجام شده</h3>
                <ul class="space-y-2">
                    @foreach($invoice->order->getAllItemsAttribute() as $item)
                        <li class="flex justify-between items-center">
                            <span>{!! $item->property->full_title ?? $item->title !!}</span>
                            <span class="font-medium">{!! number_format($item->sub_total) !!} تومان</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="border-t pt-4 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900">جمع کل</h3>
                <span class="text-2xl font-extrabold text-green-600">{!! number_format($invoice->amount) !!} تومان</span>
            </div>

            <!-- Payment Methods -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">روش پرداخت</h3>
                <div class="space-y-3">
                    <label class="flex items-center space-x-3 space-x-reverse bg-gray-50 p-3 rounded-lg border cursor-pointer hover:bg-blue-50 transition">
                        <input type="radio" name="payment_method" class="form-radio h-5 w-5 text-blue-600" checked>
                        <div class="flex flex-col justify-center gap-2">
                            <h4>کارت بانکی</h4>
                            <p>پرداخت با کلیه کارت های بانکی عضو شبکه شتاب</p>
                        </div>
                    </label>
{{--                    <label class="flex items-center space-x-3 space-x-reverse bg-gray-50 p-3 rounded-lg border cursor-pointer hover:bg-blue-50 transition">--}}
{{--                        <input type="radio" name="payment_method" class="form-radio h-5 w-5 text-blue-600">--}}
{{--                        <span>نقدی در محل</span>--}}
{{--                    </label>--}}
{{--                    <label class="flex items-center space-x-3 space-x-reverse bg-gray-50 p-3 rounded-lg border cursor-pointer hover:bg-blue-50 transition">--}}
{{--                        <input type="radio" name="payment_method" class="form-radio h-5 w-5 text-blue-600">--}}
{{--                        <span>کیف پول الکترونیکی</span>--}}
{{--                    </label>--}}
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 text-center">
                <x-srj-button full wire:click="$refresh" spinner info label="پرداخت" />
{{--                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md transition transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">--}}
{{--                    پرداخت--}}
{{--                </button>--}}
            </div>
        </div>
    </div>
</div>
