<x-filament::page>
    <h2 class="text-xl font-bold mb-6">
        پاسخ‌های نظرسنجی: {{ $record->title }}
    </h2>
    @if ($respondersCount)
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 divide-y divide-gray-200 rounded-lg shadow">
                <thead class="bg-gray-50">
                <tr>
                    @foreach ($headers as $header)
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">
                            {{ $header['title'] ?? $header['name'] ?? 'ستون' }}
                        </th>
                    @endforeach
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                @foreach ($rows as $row)
                    <tr>
                        @foreach ($row as $cell)
                            @if(is_array($cell))
                                @foreach($cell as $cellData)
                                <td class="px-4 py-2 text-sm text-gray-600">
                                    {{ $cellData }}
                                </td>
                                @endforeach
                            @endif
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $this->getTableData()->links() }}
        </div>
    @else
        <p class="text-gray-500">هیچ پاسخی یافت نشد.</p>
    @endif
</x-filament::page>
