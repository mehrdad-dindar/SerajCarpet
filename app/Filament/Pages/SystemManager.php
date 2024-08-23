<?php

namespace App\Filament\Pages;

use App\Settings\SystemSettings;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class SystemManager extends SettingsPage
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = SystemSettings::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Sms Panel'))
                    ->schema([
                        TextInput::make('sms_panel_username')
                            ->label(__("User Name"))
                            ->hint("Sms Panel User Name")
                            ->required(),
                        TextInput::make('sms_panel_password')
                            ->label(__("Password"))
                            ->hint("Sms Panel Password")
                            ->required(),
                    ])->columns()
            ]);
    }
}
