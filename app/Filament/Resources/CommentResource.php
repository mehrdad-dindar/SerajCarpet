<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommentResource\Pages;
use App\Models\Comment;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationGroup = 'Management';

    protected static ?string $navigationLabel = 'نظرات';

    protected static ?string $pluralModelLabel = 'نظرات';

    protected static ?string $modelLabel = 'دیدگاه';

    protected static ?int $navigationSort = 4;

//    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('commentable_type')
                    ->label("کامنت مربوط به :")
                    ->options([
                        'order' => 'سفارش',
                    ])
                    ->required()
                    ->dehydrated()
                    ->live(),

                Forms\Components\Select::make('commentable_id')
                    ->label('شناسه آیتم مورد نظر')
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search, $get) {
                        $type = $get('commentable_type');
                        $model = match ($type) {
                            'order' => Order::class,
                            default => null,
                        };

                        if (! $model) {
                            return [];
                        }

                        return $model::query()
                            ->where('id', 'LIKE', "%{$search}%")
                            ->limit(10)
                            ->get()
                            ->pluck('id')
                            ->toArray();
                    })
                    ->getOptionLabelUsing(function ($value, $get) {
                        $type = $get('commentable_type');

                        $model = match ($type) {
                            'order' => \App\Models\Order::class,
                            default => null,
                        };

                        if (! $model) {
                            return $value;
                        }

                        return $model::find($value)?->id ?? $value;
                    })
                    ->visible(fn ($get) => $get('commentable_type'))
                    ->required(),

                Forms\Components\Hidden::make('commenter_type')
                    ->default(fn () => auth()->user()->getMorphClass()),

                Forms\Components\Hidden::make('commenter_id')
                    ->default(fn () => auth()->id()),

                Forms\Components\Textarea::make('body')
                    ->label("توضیحات")
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('commenter.name')
                    ->label('کاربر'),

                Tables\Columns\TextColumn::make('body')
                    ->label('متن کامنت')
                    ->limit(50),

                Tables\Columns\TextColumn::make('commentable_type_name')
                    ->label('نوع'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('زمان')
                    ->since(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
                    ->label('آیتم‌های حذف شده')
                    ->native(false)
                    ->placeholder('همه آیتم‌ها')
                    ->trueLabel('فقط حذف شده‌ها')
                    ->falseLabel('فقط فعال‌ها'),            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListComments::route('/'),
            'create' => Pages\CreateComment::route('/create'),
            'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
