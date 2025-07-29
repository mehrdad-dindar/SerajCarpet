<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AddressResource\Pages;
use App\Filament\Resources\AddressResource\RelationManagers\CustomerRelationManager;
use App\Filament\Resources\CommentResource\RelationManagers\CommentRelationManager;
use App\Forms\Components\AddressForm;
use App\Models\Address;
use App\Traits\Neshan;
use Dotswan\MapPicker\Fields\Map;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AddressResource extends Resource
{
    use Neshan;

    protected static ?string $model = Address::class;

    protected static ?string $navigationGroup = 'Management';

    protected static ?string $navigationLabel = 'آدرس ها';

    protected static ?string $pluralModelLabel = 'آدرس ها';

    protected static ?string $modelLabel = 'آدرس';

    protected static ?int $navigationSort = 4;

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label(__('Customer Name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('area')
                    ->badge()->color(fn ($state, $record): string => $record ? 'info' : 'danger')
                    ->getStateUsing(fn ($record) => $record ? 'منطقه '.$record->municipality_zone : 'X')
                    ->description(fn ($record) => $record ? 'محله '.$record->neighbourhood : 'فاقد آدرس')
                    ->sortable()
                    ->toggleable()
                    ->alignCenter()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('neighbourhood', 'like', "%{$search}%");
                    })
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('full_address')
                    ->label(__('Full Address'))
                    ->getStateUsing(fn ($record) => $record->getFullAddress()),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('Active Address')),
            ])
            ->actions([
                Tables\Actions\Action::make('location')
                    ->translateLabel()
                    ->color('info')
                    ->form([
                        Forms\Components\Fieldset::make('Address')
                            ->translateLabel()
                            ->schema([
                                Forms\Components\Placeholder::make('State / City')
                                    ->translateLabel()
                                    ->content(fn ($record) => $record->state.' / '.$record->city),
                                Forms\Components\Placeholder::make('Area / Neighbourhood')
                                    ->translateLabel()
                                    ->content(fn ($record) => $record->municipality_zone.' / '.$record->neighbourhood),
                                Forms\Components\Placeholder::make('fullـaddress')
                                    ->label(__('Full Address'))
                                    ->translateLabel()
                                    ->content(function ($record) {
                                        $address = [
                                            $record->address,
                                            $record->no ? 'پلاک '.$record->no : null,
                                            $record->floor ? 'طبقه '.$record->floor : null,
                                            $record->unit ? 'واحد '.$record->unit : null,
                                        ];

                                        return implode(' - ', $address);
                                    }),
                                Forms\Components\Actions::make([
                                    Forms\Components\Actions\Action::make('Directions')
                                        ->translateLabel()
                                        ->icon('heroicon-o-arrow-top-right-on-square')
                                        ->url(function (Model $record): string {
                                            return sprintf(
                                                'https://nshn.ir/?lat=%s&lng=%s',
                                                $record->latitude,
                                                $record->longitude
                                            );
                                        }),
                                ]),
                            ]),
                        Map::make('location')
                            ->label(__('Location'))
                            ->columnSpanFull()
                            ->default(function ($record) {
                                return [
                                    'lat' => $record->latitude,
                                    'lng' => $record->longitude,
                                ];
                            })
                            ->extraStyles([
                                'min-height: 50vh',
                                'border-radius: 16px',
                            ])
                            ->showMarker()
                            ->markerColor('#e45757')
                            ->showFullscreenControl()
                            ->showZoomControl()
                            ->draggable(false)
                            ->detectRetina()
                            ->showMyLocationButton()
                            ->zoom(15)
                            ->tilesUrl('https://tile.openstreetmap.org/{z}/{x}/{y}.png'),
                    ])
                    ->disabledForm()
                    ->icon('heroicon-o-map-pin'),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Split::make([
                    Forms\Components\Section::make('آدرس')
                        ->icon('heroicon-o-map-pin')
                        ->schema(AddressForm::schema()),
                    Forms\Components\Grid::make()
                        ->schema([
                            Forms\Components\Section::make('مشتری')
                                ->icon('heroicon-o-user')
                                ->schema([
                                    Forms\Components\Select::make('customer_id')
                                        ->translateLabel()
                                        ->label('customer')
                                        ->prefixIcon('heroicon-o-user')
                                        ->relationship('customer', 'id_name')
                                        ->searchable()
                                        ->preload()
                                        ->live()
                                        ->createOptionForm([
                                            Forms\Components\Grid::make()
                                                ->schema([
                                                    Forms\Components\TextInput::make('name')
                                                        ->prefixIcon('heroicon-o-user')
                                                        ->label(__('Customer Name'))
                                                        ->required(),
                                                    Forms\Components\TextInput::make('phone')
                                                        ->label(__('Customer Phone'))
                                                        ->prefixIcon('heroicon-o-phone')
                                                        ->unique()
                                                        ->required(),
                                                    Forms\Components\TextInput::make('phone2')
                                                        ->prefixIcon('heroicon-o-phone')
                                                        ->label(__('Customer\'s second contact number')),
                                                ])
                                                ->columns(1),
                                        ])
                                        ->createOptionAction(fn ($action) => $action->modalWidth('sm'))
                                        ->required(),
                                ]),
                            Forms\Components\Section::make('توضیحات آدرس')
                                ->icon('heroicon-o-information-circle')
                                ->schema([
                                    Forms\Components\Textarea::make('description')
                                        ->label(false)
                                        ->rows(4)
                                        ->helperText('در صورت نیاز لطفا توضیحات آدرس را در این قسمت وارد نمایید'),
                                ]),
                        ])->grow(false),
                ])->columnSpanFull(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CustomerRelationManager::class,
            CommentRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAddresses::route('/'),
            'create' => Pages\CreateAddress::route('/create'),
            'edit' => Pages\EditAddress::route('/{record}/edit'),
        ];
    }
}
