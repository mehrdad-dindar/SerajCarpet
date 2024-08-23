<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SmsPatternResource\Pages;
use App\Filament\Resources\SmsPatternResource\RelationManagers;
use App\Models\SmsPattern;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SmsPatternResource extends Resource
{
    protected static ?string $model = SmsPattern::class;

    protected static ?string $navigationGroup = 'System Setting';
    protected static ?string $navigationLabel = 'الگو‌های پیامک';
    protected static ?string $pluralModelLabel = "الگو‌های پیامک";
    protected static ?string $modelLabel = 'الگو';

    protected static ?int $navigationSort = 1;

//    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make("title")
                    ->translateLabel()
                    ->required(),
                Forms\Components\TextInput::make("code")
                    ->label(__("Pattern Code"))
                    ->numeric()
                    ->hint('کد عددی که سامانه پیامک برای الگو ایجاد کرده')
                    ->required(),
                Forms\Components\Textarea::make('body')
                    ->label(__("Pattern Body"))
                    ->rows(10)
                    ->required(),
                KeyValue::make('params')
                    ->hint('اگر متن شما دارای متغییر می‌باشد با استفاده از ورودی زیر آنها را مشخص کنید')
                    ->translateLabel(),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->tooltip(fn (Model $record): string => $record->body)
                    ->translateLabel(),
                TextColumn::make("code")
                    ->label(__("Pattern Code")),
                TextColumn::make('params')
                ->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSmsPatterns::route('/'),
            'create' => Pages\CreateSmsPattern::route('/create'),
            'edit' => Pages\EditSmsPattern::route('/{record}/edit'),
        ];
    }
}
