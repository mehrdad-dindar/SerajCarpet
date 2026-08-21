<div class="space-y-6 max-w-5xl mx-auto" dir="rtl">

    {{-- هدر صفحه --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl md:text-2xl font-black text-gray-900 dark:text-white">دفترچه آدرس‌ها</h2>
            <p class="text-xs text-gray-400 mt-1">مدیریت مکان‌های تحویل و جمع‌آوری فرش‌های قالیشویی سراج</p>
        </div>
        <button wire:click="openCreateModal" class="px-5 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-2xl text-xs font-bold shadow-lg shadow-amber-500/30 flex items-center gap-2 transition transform active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            افزودن آدرس جدید
        </button>
    </div>

    {{-- لیست آدرس‌ها --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($addresses as $addr)
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 border {{ $addr->is_active ? 'border-amber-500 shadow-md ring-2 ring-amber-500/20' : 'border-gray-100 dark:border-gray-700 shadow-sm' }} flex flex-col justify-between space-y-4">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b dark:border-gray-700">
                        <span class="text-xs font-black text-amber-600 dark:text-amber-400">
                            منطقه {{ $addr->municipality_zone ?? 'تهران' }} - {{ $addr->neighbourhood ?? '' }}
                        </span>
                        @if($addr->is_active)
                            <span class="px-3 py-1 bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-300 text-[11px] font-bold rounded-full">
                                آدرس پیش‌فرض
                            </span>
                        @else
                            <button wire:click="makeDefault({{ $addr->id }})" class="text-[11px] text-gray-400 hover:text-amber-600 transition">
                                تبدیل به پیش‌فرض
                            </button>
                        @endif
                    </div>
                    <p class="text-xs text-gray-700 dark:text-gray-300 font-medium mt-3 leading-relaxed">
                        {{ $addr->getFullAddress() }}
                    </p>
                    @if($addr->description)
                        <p class="text-[11px] text-gray-400 mt-2 bg-gray-50 dark:bg-gray-700/50 p-2.5 rounded-xl">
                            یادداشت: {{ $addr->description }}
                        </p>
                    @endif
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t dark:border-gray-700">
                    <button wire:click="editAddress({{ $addr->id }})" class="px-3 py-1.5 text-xs text-blue-600 hover:bg-blue-50 dark:hover:bg-gray-700 rounded-lg font-bold">
                        ویرایش
                    </button>
                    <button wire:click="deleteAddress({{ $addr->id }})" wire:confirm="آیا از حذف این آدرس اطمینان دارید؟" class="px-3 py-1.5 text-xs text-red-500 hover:bg-red-50 dark:hover:bg-gray-700 rounded-lg font-bold">
                        حذف
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-2 p-12 text-center bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700">
                <p class="text-sm text-gray-500">هیچ آدرسی ثبت نشده است.</p>
            </div>
        @endforelse
    </div>

    {{-- مودال اختصاصی نشان با Leaflet و Alpine.js --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" x-data="{
            map: null,
            marker: null,
            initMap(lat, lng) {
                this.$nextTick(() => {
                    if (this.map) this.map.remove();
                    this.map = L.map('address-picker-map').setView([lat, lng], 14);
                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(this.map);
                    this.marker = L.marker([lat, lng], { draggable: true }).addTo(this.map);

                    this.marker.on('dragend', (e) => {
                        const position = e.target.getLatLng();
                        $wire.setCoordinates(position.lat, position.lng);
                    });

                    this.map.on('click', (e) => {
                        this.marker.setLatLng(e.latlng);
                        $wire.setCoordinates(e.latlng.lat, e.latlng.lng);
                    });
                });
            }
        }" x-init="initMap({{ $latitude }}, {{ $longitude }})" @init-map-picker.window="initMap($event.detail.lat, $event.detail.lng)">

            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700 w-full max-w-2xl overflow-hidden max-h-[90vh] flex flex-col">
                <div class="p-5 border-b dark:border-gray-700 flex items-center justify-between bg-gray-50 dark:bg-gray-900/40">
                    <h3 class="font-bold text-gray-900 dark:text-white">
                        {{ $editingAddressId ? 'ویرایش آدرس' : 'ثبت آدرس جدید روی نقشه' }}
                    </h3>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-6 space-y-4 overflow-y-auto">
                    {{-- نقشه --}}
                    <div>
                        <label class="text-xs font-bold text-gray-600 dark:text-gray-300 block mb-1">موقعیت دقیق روی نقشه (پین را جابجا کنید)</label>
                        <div id="address-picker-map" style="height: 220px;" class="rounded-2xl border border-gray-200 dark:border-gray-700 z-0"></div>
                    </div>

                    {{-- متن آدرس کامل --}}
                    <div>
                        <label class="text-xs font-bold text-gray-600 dark:text-gray-300 block mb-1">متن کامل آدرس</label>
                        <textarea wire:model="address" rows="2" class="w-full bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 rounded-xl text-xs" placeholder="نام خیابان، کوچه، فرعی..."></textarea>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="text-xs font-bold text-gray-600 dark:text-gray-300 block mb-1">پلاک *</label>
                            <input type="text" wire:model="no" class="w-full bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 rounded-xl text-xs">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-600 dark:text-gray-300 block mb-1">طبقه</label>
                            <input type="text" wire:model="floor" class="w-full bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 rounded-xl text-xs">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-600 dark:text-gray-300 block mb-1">واحد</label>
                            <input type="text" wire:model="unit" class="w-full bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 rounded-xl text-xs">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-600 dark:text-gray-300 block mb-1">توضیحات و راهنمای آدرس</label>
                        <input type="text" wire:model="description" class="w-full bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 rounded-xl text-xs" placeholder="مثال: درب طوسی، روبروی پارک">
                    </div>

                    <label class="flex items-center gap-2 text-xs font-bold text-gray-700 dark:text-gray-300 cursor-pointer pt-2">
                        <input type="checkbox" wire:model="is_active" class="rounded text-amber-500 focus:ring-amber-500">
                        تنظیم به عنوان آدرس پیش‌فرض
                    </label>
                </div>

                <div class="p-5 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 flex justify-end gap-3">
                    <button wire:click="$set('showModal', false)" type="button" class="px-5 py-2.5 text-xs font-bold text-gray-600 hover:text-gray-800">انصراف</button>
                    <button wire:click="save" type="button" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-extrabold shadow-md">ذخیره آدرس</button>
                </div>
            </div>
        </div>
    @endif
</div>
