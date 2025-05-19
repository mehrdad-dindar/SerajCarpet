<?php

namespace App\Filament\Widgets;

use App\Models\Comment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;

class LatestCommentsWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Comment::query()
                    ->with(['commentable', 'commenter'])
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('commenter.name')
                    ->label('کاربر'),

                Tables\Columns\TextColumn::make('body')
                    ->label('متن کامنت')
                    ->limit(50),

                Tables\Columns\TextColumn::make('commentable_type')
                    ->label('نوع')
                    ->formatStateUsing(function ($record) {
                        return $record->commentable ? class_basename($record->commentable) : '---';
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('ایجاد شده در')
                    ->tooltip(fn (?Model $record) => verta($record->created_at)->format('d F Y - H:i'))

                    ->since(),
            ])
            ->heading('آخرین کامنت‌ها')
            ->emptyStateHeading('هیچ کامنتی یافت نشد');
    }
}
