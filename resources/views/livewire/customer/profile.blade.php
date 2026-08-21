<div class="max-w-2xl mx-auto space-y-6" dir="rtl">
    <div>
        <h2 class="text-xl md:text-2xl font-black text-gray-900 dark:text-white">اطلاعات حساب کاربری</h2>
        <p class="text-xs text-gray-400 mt-1">مشاهده و ویرایش مشخصات هویتی و شماره‌های تماس</p>
    </div>

    <form wire:submit.prevent="save" class="bg-white dark:bg-gray-800 rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 dark:border-gray-700 space-y-5">

        <div>
            <label class="text-xs font-bold text-gray-700 dark:text-gray-300 block mb-1.5">نام و نام خانوادگی *</label>
            <input type="text" wire:model="name" class="w-full bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 rounded-2xl text-sm font-medium focus:ring-amber-500">
            @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="text-xs font-bold text-gray-700 dark:text-gray-300 block mb-1.5">شماره موبایل اصلی (غیرقابل ویرایش)</label>
            <input type="text" value="{{ $phone }}" disabled class="w-full bg-gray-100 dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-2xl text-sm font-mono text-gray-500 cursor-not-allowed" dir="ltr">
        </div>

        <div>
            <label class="text-xs font-bold text-gray-700 dark:text-gray-300 block mb-1.5">شماره تماس دوم / اضطراری (اختیاری)</label>
            <input type="text" wire:model="phone2" placeholder="09xxxxxxxxx" class="w-full bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 rounded-2xl text-sm font-mono focus:ring-amber-500" dir="ltr">
            @error('phone2') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="pt-4 border-t dark:border-gray-700 flex justify-end">
            <button type="submit" class="px-8 py-3 bg-amber-500 hover:bg-amber-600 text-white font-extrabold rounded-2xl text-xs shadow-lg shadow-amber-500/30 transition transform active:scale-95">
                ذخیره تغییرات
            </button>
        </div>
    </form>
</div>
