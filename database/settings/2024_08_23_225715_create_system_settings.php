<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('system.sms_panel_username', '9363432406');
        $this->migrator->add('system.sms_panel_password', 'd5c81d78-1a21-4aa7-ac42-e1bdbe5a144c');
        $this->migrator->add('system.factory_location', ['35.747962952792','51.517529189587']);
        $this->migrator->add('shift.shifts', [
            [
                'day' => '0',
                'morning_shift_hours' => [
                    [
                        'morning_end' => '11:00',
                        'morning_start' => '09:00',
                    ],
                    [
                        'morning_end' => '13:00',
                        'morning_start' => '11:01',
                    ],
                    [
                        'morning_end' => '15:00',
                        'morning_start' => '13:01',
                    ],
                ],
                'afternoon_shift_hours' => [
                    [
                        'afternoon_end' => '17:00',
                        'afternoon_start' => '15:01',
                    ],
                    [
                        'afternoon_end' => '19:00',
                        'afternoon_start' => '17:01',
                    ],
                    [
                        'afternoon_end' => '21:00',
                        'afternoon_start' => '19:01',
                    ],
                ],
            ],
            [
                'day' => '1',
                'morning_shift_hours' => [
                    [
                        'morning_end' => '11:00',
                        'morning_start' => '09:00',
                    ],
                    [
                        'morning_end' => '13:00',
                        'morning_start' => '11:01',
                    ],
                    [
                        'morning_end' => '15:00',
                        'morning_start' => '13:01',
                    ],
                ],
                'afternoon_shift_hours' => [
                    [
                        'afternoon_end' => '17:00',
                        'afternoon_start' => '15:01',
                    ],
                    [
                        'afternoon_end' => '19:00',
                        'afternoon_start' => '17:01',
                    ],
                    [
                        'afternoon_end' => '21:00',
                        'afternoon_start' => '19:01',
                    ],
                ],
            ],
            [
                'day' => '2',
                'morning_shift_hours' => [
                    [
                        'morning_end' => '11:00',
                        'morning_start' => '09:00',
                    ],
                    [
                        'morning_end' => '13:00',
                        'morning_start' => '11:01',
                    ],
                    [
                        'morning_end' => '15:00',
                        'morning_start' => '13:01',
                    ],
                ],
                'afternoon_shift_hours' => [
                    [
                        'afternoon_end' => '17:00',
                        'afternoon_start' => '15:01',
                    ],
                    [
                        'afternoon_end' => '19:00',
                        'afternoon_start' => '17:01',
                    ],
                    [
                        'afternoon_end' => '21:00',
                        'afternoon_start' => '19:01',
                    ],
                ],
            ],
            [
                'day' => '3',
                'morning_shift_hours' => [
                    [
                        'morning_end' => '11:00',
                        'morning_start' => '09:00',
                    ],
                    [
                        'morning_end' => '13:00',
                        'morning_start' => '11:01',
                    ],
                    [
                        'morning_end' => '15:00',
                        'morning_start' => '13:01',
                    ],
                ],
                'afternoon_shift_hours' => [
                    [
                        'afternoon_end' => '17:00',
                        'afternoon_start' => '15:01',
                    ],
                    [
                        'afternoon_end' => '19:00',
                        'afternoon_start' => '17:01',
                    ],
                    [
                        'afternoon_end' => '21:00',
                        'afternoon_start' => '19:01',
                    ],
                ],
            ],
            [
                'day' => '4',
                'morning_shift_hours' => [
                    [
                        'morning_end' => '11:00',
                        'morning_start' => '09:00',
                    ],
                    [
                        'morning_end' => '13:00',
                        'morning_start' => '11:01',
                    ],
                    [
                        'morning_end' => '15:00',
                        'morning_start' => '13:01',
                    ],
                ],
                'afternoon_shift_hours' => [
                    [
                        'afternoon_end' => '17:00',
                        'afternoon_start' => '15:01',
                    ],
                    [
                        'afternoon_end' => '19:00',
                        'afternoon_start' => '17:01',
                    ],
                    [
                        'afternoon_end' => '21:00',
                        'afternoon_start' => '19:01',
                    ],
                ],
            ],
            [
                'day' => '5',
                'morning_shift_hours' => [
                    [
                        'morning_end' => '11:00',
                        'morning_start' => '09:00',
                    ],
                    [
                        'morning_end' => '13:00',
                        'morning_start' => '11:01',
                    ],
                    [
                        'morning_end' => '15:00',
                        'morning_start' => '13:01',
                    ],
                ],
                'afternoon_shift_hours' => [
                    [
                        'afternoon_end' => '17:00',
                        'afternoon_start' => '15:01',
                    ],
                    [
                        'afternoon_end' => '19:00',
                        'afternoon_start' => '17:01',
                    ],
                    [
                        'afternoon_end' => '21:00',
                        'afternoon_start' => '19:01',
                    ],
                ],
            ],
            [
                'day' => '6',
                'morning_shift_hours' => [
                    [
                        'morning_end' => '11:00',
                        'morning_start' => '09:00',
                    ],
                    [
                        'morning_end' => '13:00',
                        'morning_start' => '11:01',
                    ],
                    [
                        'morning_end' => '15:00',
                        'morning_start' => '13:01',
                    ],
                ],
                'afternoon_shift_hours' => [
                    [
                        'afternoon_end' => '17:00',
                        'afternoon_start' => '15:01',
                    ],
                    [
                        'afternoon_end' => '19:00',
                        'afternoon_start' => '17:01',
                    ],
                    [
                        'afternoon_end' => '21:00',
                        'afternoon_start' => '19:01',
                    ],
                ],
            ],
        ]);
        $this->migrator->add('shift.current',"");
    }
};
