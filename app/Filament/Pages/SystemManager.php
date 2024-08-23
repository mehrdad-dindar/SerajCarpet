<?php

namespace App\Filament\Pages;

use App\Settings\SystemSettings;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Livewire\Attributes\Title;

class SystemManager extends SettingsPage
{
    use HasPageShield;

    protected static ?string $navigationLabel = 'مدیریت سیستم';
    protected static ?string $title = "مدیریت سیستم";

    protected static string $settings = SystemSettings::class;
    protected static ?string $navigationGroup = 'System Setting';
    protected static ?int $navigationSort = 1;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Sms Panel'))
                    ->schema([
                        TextInput::make('sms_panel_username')
                            ->label(__("User Name"))
                            ->hint(__("Sms Panel User Name"))
                            ->required(),
                        TextInput::make('sms_panel_password')
                            ->label(__("Password"))
                            ->hint(__("Sms Panel Password"))
                            ->required(),
                    ])->columns()
            ]);
    }
}
