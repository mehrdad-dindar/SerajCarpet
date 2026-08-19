<?php

namespace App\Filament\Pages;

//use App\Filament\Widgets\CallReportWidget;
use App\Models\CallLog;
use Filament\Forms;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;

class CallReport extends Page implements Tables\Contracts\HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'گزارش تماس‌ها';
    protected static ?string $title = 'گزارش تماس‌ها';

    protected static string $view = 'filament.pages.call-report';

    public function table(Table $table): Table
    {
        return $table
            ->query(CallLog::query())
            ->columns([
                Tables\Columns\TextColumn::make('caller_id')
                    ->label('شماره تماس')
                    ->searchable(),
                Tables\Columns\TextColumn::make('call_type')
                    ->label('نوع تماس')
                    ->formatStateUsing(fn ($state) => $state === 'incoming' ? 'ورودی' : 'خروجی'),
                Tables\Columns\TextColumn::make('timestamp')
                    ->label('زمان')
                    ->dateTime('Y-m-d H:i:s'),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('مشتری')
                    ->default('ناشناس'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('call_type')
                    ->options([
                        'incoming' => 'ورودی',
                        'outgoing' => 'خروجی',
                    ])
                    ->label('نوع تماس'),
                Tables\Filters\Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('from_date')->label('از تاریخ'),
                        Forms\Components\DatePicker::make('to_date')->label('تا تاریخ'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from_date'], fn ($q) => $q->whereDate('timestamp', '>=', $data['from_date']))
                            ->when($data['to_date'], fn ($q) => $q->whereDate('timestamp', '<=', $data['to_date']));
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('view_customer')
                    ->label('مشاهده مشتری')
                    ->url(fn (CallLog $record): string => $record->customer ? route('filament.admin.resources.customers.edit', $record->customer) : '#')
                    ->disabled(fn (CallLog $record): bool => ! $record->customer),
            ])
            ->bulkActions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ExportAction::make()->label('خروجی به CSV'),
                ]),
            ]);
    }

    public function getHeaderWidgets(): array
    {
        return [
//            CallReportWidget::class,
        ];
    }
}
