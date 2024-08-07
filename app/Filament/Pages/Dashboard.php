<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected function getHeaderActions(): array
    {
        return [
            Action::make('action')
                ->label('Update Profile')
                ->form([
                    TextInput::make('name')
                        ->autofocus()
                        ->required(),
                ])
                ->action(function (array $data){
                    $recipient = User::find(1);
                    $res = Notification::make()
                        ->title('Saved successfully'.implode(', ', $data))
                        ->success()
                        ->sendToDatabase($recipient)
                        ->broadcast($recipient);
dd($res);
                }),
        ];
    }
}
