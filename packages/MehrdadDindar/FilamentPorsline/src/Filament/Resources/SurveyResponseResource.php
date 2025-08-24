<?php

namespace MehrdadDindar\FilamentPorsline\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use MehrdadDindar\FilamentPorsline\Models\SurveyResponse;

class SurveyResponseResource extends Resource
{
    protected static ?string $model = SurveyResponse::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'نظرسنجی';

    protected static ?string $modelLabel = 'پاسخ نظرسنجی';

    protected static ?string $pluralModelLabel = 'پاسخ‌های نظرسنجی';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('اطلاعات پاسخ‌دهنده')
                    ->schema([
                        Forms\Components\Select::make('survey_id')
                            ->relationship('survey', 'name')
                            ->label('نظرسنجی')
                            ->required()
                            ->searchable(),

                        Forms\Components\TextInput::make('responder_name')
                            ->label('نام پاسخ‌دهنده')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('responder_email')
                            ->label('ایمیل')
                            ->email()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('responder_phone')
                            ->label('شماره تلفن')
                            ->tel()
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('زمان‌بندی')
                    ->schema([
                        Forms\Components\DateTimePicker::make('start_time')
                            ->label('زمان شروع'),

                        Forms\Components\DateTimePicker::make('submit_time')
                            ->label('زمان ارسال'),

                        Forms\Components\DateTimePicker::make('last_edit_time')
                            ->label('آخرین ویرایش'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('اطلاعات پاسخ')
                    ->schema([
                        Forms\Components\TextInput::make('score')
                            ->label('امتیاز')
                            ->numeric()
                            ->nullable(),

                        Forms\Components\Toggle::make('is_complete')
                            ->label('کامل')
                            ->default(false),

                        Forms\Components\Toggle::make('is_spam')
                            ->label('اسپم')
                            ->default(false),

                        Forms\Components\KeyValue::make('data')
                            ->label('داده‌های پاسخ')
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('survey.name')
                    ->label('نظرسنجی')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('responder_name')
                    ->label('نام')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('responder_email')
                    ->label('ایمیل')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('responder_phone')
                    ->label('تلفن')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('score')
                    ->label('امتیاز')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('duration')
                    ->label('مدت زمان')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_complete')
                    ->label('کامل')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_spam')
                    ->label('اسپم')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('submit_time')
                    ->label('زمان ارسال')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('survey_id')
                    ->relationship('survey', 'name')
                    ->label('نظرسنجی'),

                Tables\Filters\TernaryFilter::make('is_complete')
                    ->label('کامل'),

                Tables\Filters\TernaryFilter::make('is_spam')
                    ->label('اسپم'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => \MehrdadDindar\FilamentPorsline\Filament\Resources\SurveyResponseResource\Pages\ListSurveyResponses::route('/'),
            'create' => \MehrdadDindar\FilamentPorsline\Filament\Resources\SurveyResponseResource\Pages\CreateSurveyResponse::route('/create'),
            'view' => \MehrdadDindar\FilamentPorsline\Filament\Resources\SurveyResponseResource\Pages\ViewSurveyResponse::route('/{record}'),
            'edit' => \MehrdadDindar\FilamentPorsline\Filament\Resources\SurveyResponseResource\Pages\EditSurveyResponse::route('/{record}/edit'),
        ];
    }
} 