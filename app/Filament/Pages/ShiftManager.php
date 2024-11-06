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
                Forms\Components\Repeater::make('shift_hours')
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
                                        Forms\Components\TimePicker::make('morning_start')
                                            ->label(__('Start Time'))
                                            ->seconds(false)
                                            ->datalist([
                                                '09:00',
                                                '09:30',
                                                '10:00',
                                                '10:30',
                                            ])
                                            ->required(),
                                        Forms\Components\TimePicker::make('morning_end')
                                            ->label(__('End Time'))
                                            ->seconds(false)
                                            ->datalist([
                                                '12:00',
                                                '12:30',
                                                '13:00',
                                                '13:30',
                                            ])
                                            ->required(),
                                    ]),
                                Forms\Components\Fieldset::make('Afternoon Shift')
                                    ->translateLabel()
                                    ->columnSpan(1)
                                    ->schema([
                                        Forms\Components\TimePicker::make('afternoon_start')
                                            ->label(__('Start Time'))
                                            ->seconds(false)
                                            ->datalist([
                                                '14:00',
                                                '14:30',
                                                '15:00',
                                                '15:30',
                                            ])
                                            ->required(),
                                        Forms\Components\TimePicker::make('afternoon_end')
                                            ->label(__('End Time'))
                                            ->seconds(false)
                                            ->datalist([
                                                '18:00',
                                                '18:30',
                                                '19:00',
                                                '19:30',
                                            ])
                                            ->required(),
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

    private function getDaysOption()
    {
        $now = Verta::now();
        $weekDays = [];

        for ($i = 0; $i <= 6; $i++) {
            $weekDays[$i] = $now->startWeek()->addDays($i)->format('l');
        }

        return $weekDays;
    }
}
