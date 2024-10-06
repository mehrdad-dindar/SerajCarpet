<?php

namespace App\Filament\Resources\CommentResource\RelationManagers;

use App\Models\Comment;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CommentRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('body')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('body')
            ->columns([
                Tables\Columns\TextColumn::make('commenter.name')
                    ->label(__('Name'))
                ->description(function (Comment $comment) {
                    $commenter = $comment->commenter;
                    if ($commenter instanceof User) {
                        return __('Panel user');
                    } elseif ($commenter instanceof Driver) {
                        return __('Driver');
                    } elseif ($commenter instanceof Customer) {
                        return __('Customer');
                    }
                    return null;
                }),
                Tables\Columns\TextColumn::make('body'),
                Tables\Columns\TextColumn::make('created_at')
                ->formatStateUsing(fn($state) => verta($state)->format('Y/m/d H:i')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
