<?php

namespace MehrdadDindar\FilamentPorsline\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use MehrdadDindar\FilamentPorsline\Enums\SurveyLanguage;
use MehrdadDindar\FilamentPorsline\Filament\Resources\SurveyResource\Pages;
use MehrdadDindar\FilamentPorsline\Models\Survey;
use MehrdadDindar\FilamentPorsline\Services\PorslineService;

class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'نظرسنجی';

    protected static ?string $modelLabel = 'نظرسنجی';

    protected static ?string $pluralModelLabel = 'نظرسنجی‌ها';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('اطلاعات اصلی')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('عنوان نظرسنجی')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('porsline_id')
                            ->label('شناسه نظرسنجی')
                            // باید سرویس را ایمپورت و نمونه‌سازی کنید و سپس متد را فراخوانی کنید
                            ->options(app(PorslineService::class)->getSurveys())
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->label('توضیحات')
                            ->maxLength(1000)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('language')
                            ->label('زبان')
                            ->options([
                                SurveyLanguage::ENGLISH->value => SurveyLanguage::ENGLISH->getLabel(),
                                SurveyLanguage::PERSIAN->value => SurveyLanguage::PERSIAN->getLabel(),
                                SurveyLanguage::TURKISH->value => SurveyLanguage::TURKISH->getLabel(),
                                SurveyLanguage::ARABIC->value => SurveyLanguage::ARABIC->getLabel(),
                            ])
                            ->default(SurveyLanguage::PERSIAN->value)
                            ->required(),

                        Forms\Components\TextInput::make('folder_id')
                            ->label('شناسه پوشه')
                            ->numeric()
                            ->nullable(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('تنظیمات')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('فعال')
                            ->default(true),

                        Forms\Components\Toggle::make('is_stopped')
                            ->label('متوقف شده')
                            ->default(false),

                        Forms\Components\Toggle::make('is_template')
                            ->label('قالب')
                            ->default(false),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('porsline_id')
                    ->label('شناسه پرسلاین')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('نام')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('submitted_responses')
                    ->label('پاسخ‌ها')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->tooltip(fn (Survey $record) => 'می‌توانید از طریق پرسلاین نسبت به '.($record->is_active ? 'غیرفعالسازی' : 'فعالسازی') . ' پرسشنامه اقدام فرمایید')
                    ->label('فعال')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_date')
                    ->label('تاریخ ایجاد')
                    ->jalaliDateTime('Y-m-d H:i:s')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('فعال'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
//ToDo:                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('view_survey')
                        ->label('مشاهده نظرسنجی')
                        ->url(fn (Survey $record) => $record->survey_url)
                        ->openUrlInNewTab()
                        ->icon('heroicon-o-eye'),

                    Tables\Actions\Action::make('view_report')
                        ->label('گزارش')
                        ->url(fn (Survey $record) => $record->report_url)
                        ->openUrlInNewTab()
                        ->icon('heroicon-o-chart-bar'),

                    Tables\Actions\DeleteAction::make()
                        ->action(function (Survey $record) {
                            $res = (new PorslineService())->deleteSurvey($record->porsline_id);
                            if ($res) {
                                $record->delete();
                            }
                        }),
                ]),
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
            'index' => Pages\ListSurveys::route('/'),
            'view'    => Pages\ViewSurveyResponses::route('/{record}/responses'),
        ];
    }
}
