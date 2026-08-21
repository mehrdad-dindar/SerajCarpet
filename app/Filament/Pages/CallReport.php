<?php

namespace App\Filament\Pages;

use App\Models\CallLog;
use Filament\Forms;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class CallReport extends Page implements Tables\Contracts\HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-phone-arrow-up-right';
    protected static ?string $navigationGroup = 'Management';
    protected static ?string $navigationLabel = 'گزارش تماس‌ها و مکالمات';
    protected static ?string $title = 'گزارش و ضبط تماس‌های ایزابل';
    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.pages.call-report';

    public function table(Table $table): Table
    {
        return $table
            ->query(CallLog::query()->with('customer')->latest())
            ->columns([
                Tables\Columns\TextColumn::make('caller_id')
                    ->label('شماره تماس')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono'),

                Tables\Columns\TextColumn::make('customer.name')
                    ->label('نام مشتری')
                    ->default('ناشناس / جدید')
                    ->searchable(),

                Tables\Columns\TextColumn::make('extension')
                    ->label('داخلی')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('type')
                    ->label('نوع تماس')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'inbound'  => 'ورودی موفق',
                        'outbound' => 'خروجی',
                        'missed'   => 'بی‌پاسخ / ناموفق',
                        default    => $state,
                    })
                    ->color(fn ($state) => match ($state) {
                        'inbound'  => 'success',
                        'outbound' => 'info',
                        'missed'   => 'danger',
                        default    => 'gray',
                    }),

                Tables\Columns\TextColumn::make('duration')
                    ->label('مدت (ثانیه)')
                    ->formatStateUsing(fn ($state) => $state ? gmdate("H:i:s", (int)$state) : '-')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('زمان تماس')
                    ->jalaliDateTime('Y/m/d - H:i:s')
                    ->sortable(),

                Tables\Columns\TextColumn::make('recording_file')
                    ->label('فایل ضبط مکالمه')
                    ->formatStateUsing(function ($state, CallLog $record) {
                        if (blank($state)) {
                            return new HtmlString('<span class="text-xs text-gray-400">فاقد ضبط</span>');
                        }
                        $url = route('voip.recordings.stream', $record);
                        return new HtmlString(<<<HTML
                            <audio controls preload="none" class="h-8 w-48">
                                <source src="{$url}" type="audio/wav">
                                مرورگر شما پشتیبانی نمی‌کند.
                            </audio>
                        HTML);
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'inbound'  => 'ورودی',
                        'outbound' => 'خروجی',
                        'missed'   => 'از دست رفته (Missed)',
                    ])
                    ->label('فیلتر نوع تماس'),
            ])
            ->actions([
                Tables\Actions\Action::make('create_order')
                    ->label('ثبت سفارش')
                    ->icon('heroicon-o-plus')
                    ->color('success')
                    ->url(fn (CallLog $record) => route('filament.admin.resources.orders.create', [
                        'customer_id' => $record->customer_id,
                    ]))
            ]);
    }
}
