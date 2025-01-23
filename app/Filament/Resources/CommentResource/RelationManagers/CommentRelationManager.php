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
use Illuminate\Support\Facades\Auth;

class CommentRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    public static function mutateRelationshipDataBeforeSave(array $data): array
    {
        dd($data);
        $data['commenter_type'] = Auth::user()::class;
        $data['commenter_id'] = Auth::id();

        return $data;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('body')
                    ->required()
                    ->rows(6)
                    ->maxLength(255),
                Forms\Components\SpatieMediaLibraryFileUpload::make('attachment')
                    ->translateLabel()
                    ->multiple()
                    ->reorderable()
//                    ->collection(function ($state) {
//                        dd($state->m);
//                    })
                    ->required(),
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
                Tables\Columns\TextColumn::make('body')
                    ->label(__('Comment')),
                Tables\Columns\TextColumn::make('created_at')
                    ->translateLabel()
                    ->formatStateUsing(fn ($state) => verta($state)->format('Y/m/d H:i')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function ($data) {
                        $data['commenter_id'] = Auth::id();
                        $data['commenter_type'] = Auth::user()::class;
                        return $data;
                    }),
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
