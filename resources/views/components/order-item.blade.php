@props(['title', 'dimensions', 'unit', 'quantity', 'price','item'])

<li class="group relative p-6 mb-3 list-none transition-all duration-300 bg-white rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 border border-gray-100">
    <div class="flex flex-col space-y-3">
        <!-- عنوان با اکسنت رنگی -->
        <h6 class="text-lg font-semibold text-gray-800 transition-colors group-hover:text-blue-600">
            {{ $title }}
        </h6>

        <!-- جزییات آیتم -->
        <div class="grid grid-cols-2 gap-3 md:grid-cols-3">
            <!-- ابعاد -->
            <div class="flex flex-col justify-center items-center gap-2">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9h-5m0 0h5m-9 0h-2"/>
                    </svg>
                    <h3 class="text-xs font-medium text-gray-500 mb-0">{{ __("Dimensions") }}</h3>
                </div>
                <p class="text-sm font-semibold text-gray-700">{{ $dimensions }} {{ __($unit) }}</p>
            </div>

            <!-- تعداد -->
            <div class="flex flex-col justify-center items-center gap-2">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="text-xs font-medium text-gray-500 mb-0">{{ __("Quantity") }}</h3>
                </div>
                <p class="text-sm font-semibold text-gray-700">{{ $quantity }}</p>
            </div>

            <!-- قیمت -->
            <div class="flex flex-col justify-center items-center gap-2">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-xs font-medium text-gray-500 mb-0">{{ __("Price") }}</h3>
                </div>
                <p class="text-sm font-semibold text-green-600">{{ number_format($price) }} {{ __("Toman") }}</p>
            </div>
            <!-- پیوست -->
            @if($item->hasMedia())
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <h4 class="text-sm font-medium text-gray-500 mb-2">{{ __('Attachments') }}</h4>

                    <div class="grid grid-cols-2 gap-3">
                        @foreach($item->getMedia() as $file)
                            <div class="relative group/file">
                                @if(str_starts_with($file->mime_type, 'image/'))
                                    <!-- پیش‌نمایش تصویر -->
                                    <a href="{{ Storage::url($file->file_name) }}"
                                       class="block aspect-square bg-gray-100 rounded-lg overflow-hidden"
                                       data-fancybox="gallery-{{ $item->id }}">
                                        <img src="{{ Storage::url($file->file_name) }}"
                                             class="object-cover w-full h-full hover:scale-105 transition-transform">
                                    </a>
                                @else
                                    <!-- فایل‌های غیر تصویری -->
                                    <div class="p-3 border rounded-lg bg-gray-50 hover:bg-blue-50 transition-colors">
                                        <div class="flex items-center gap-2">
                                            @switch($file->mime_type)
                                                @case(str_starts_with($file->mime_type, 'audio/'))
                                                    <x-phosphor.icons::duotone.music-note class="w-6 h-6 text-blue-500"/>
{{--                                                    <svg class="w-6 h-6 text-blue-500"><!-- آیکون صوت --></svg>--}}
                                                    @break
                                                @case(str_starts_with($file->mime_type, 'video/'))
                                                    <x-phosphor.icons::duotone.youtube-logo class="w-6 h-6 text-red-500"/>
                                                    @break
                                                @default
                                                    <x-phosphor.icons::duotone.cloud-arrow-down class="w-6 h-6 text-gray-500"/>
                                            @endswitch

                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-medium text-gray-700 truncate">
                                                    {{ $file->file_name }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    {{ Str::upper(Str::afterLast($file->mime_type, '/')) }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- دکمه دانلود -->
                                        <a href="{{ Storage::url($file->file_name) }}"
                                           download
                                           class="absolute inset-0 z-10">
                                            <span class="sr-only">دانلود</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</li>
