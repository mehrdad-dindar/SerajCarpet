<?php

namespace App\Filament\Pages;

use App\Settings\ShiftSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Verta;

class ShiftManager extends SettingsPage
{
    protected static ?string $navigationLabel = 'مدیریت شیفت';

    protected static ?string $title = 'مدیریت شیفت';

    protected static string $settings = ShiftSettings::class;

    protected static ?string $navigationGroup = 'System Setting';

    protected static ?int $navigationSort = 2;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Repeater::make('shifts')
                    ->label('Shift Hours by Day')
                    ->translateLabel()
                    ->schema([
                        Forms\Components\Select::make('day')
                            ->label(__('Day'))
                            ->options($this->getDaysOption())
                            ->required(),
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\Fieldset::make('Morning Shift')
                                    ->translateLabel()
                                    ->columnSpan(1)
                                    ->schema([
                                        Forms\Components\Repeater::make('morning_shift_hours')
                                            ->label('Shift Hours')
                                            ->translateLabel()
                                            ->columnSpanFull()
                                            ->columns()
                                            ->schema([
                                                Forms\Components\TimePicker::make('morning_start')
                                                    ->label(__('Start Time'))
                                                    ->seconds(false)
                                                    ->required(),
                                                Forms\Components\TimePicker::make('morning_end')
                                                    ->label(__('End Time'))
                                                    ->seconds(false)
                                                    ->required(),
                                            ]),
                                    ]),
                                Forms\Components\Fieldset::make('Afternoon Shift')
                                    ->translateLabel()
                                    ->columnSpan(1)
                                    ->schema([
                                        Forms\Components\Repeater::make('afternoon_shift_hours')
                                            ->label('Shift Hours')
                                            ->translateLabel()
                                            ->columnSpanFull()
                                            ->columns()
                                            ->schema([
                                                Forms\Components\TimePicker::make('afternoon_start')
                                                    ->label(__('Start Time'))
                                                    ->seconds(false)
                                                    ->required(),
                                                Forms\Components\TimePicker::make('afternoon_end')
                                                    ->label(__('End Time'))
                                                    ->seconds(false)
                                                    ->required(),
                                            ]),
                                    ]),
                            ])
                            ->columnSpanFull()
                            ->columns(),
                    ])
                    ->reorderable()
                    ->columns()
                    ->columnSpanFull()
                    ->addActionLabel(__('Add shift')),
            ]);
    }

    private function getDaysOption(): array
    {
        $now = Verta::now();
        $weekDays = [];

        for ($i = 0; $i <= 6; $i++) {
            $weekDays[$i] = $now->startWeek()->addDays($i)->format('l');
        }

        return $weekDays;
    }
}
