<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Forms\Components\AddressForm;
use App\Traits\Neshan;
use Dotswan\MapPicker\Fields\Map;
use Filament\Forms;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AddressRelationManager extends RelationManager
{
    use Neshan;
    protected static string $relationship = 'addresses';
    protected static ?string $label = 'آدرس';
    protected static ?string $title = 'آدرس ها';
    protected static ?string $icon = 'heroicon-o-map-pin';

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->recordTitleAttribute('address')
            ->columns([
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label(__('Customer Name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('area')
                    ->badge()->color(fn ($state, $record): string => $record ? 'info' : 'danger')
                    ->getStateUsing(fn ($record) => $record ? 'منطقه '.$record->municipality_zone : 'X')
                    ->description(fn ($record) => $record ? 'محله '.$record->neighbourhood : 'فاقد آدرس')
                    ->sortable()
                    ->toggleable()
                    ->alignCenter()
                    ->searchable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->description)
                    ->translateLabel(),
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
                            ->zoom(15)
                            ->tilesUrl('https://tile.openstreetmap.org/{z}/{x}/{y}.png'),
                    ])
                    ->disabledForm()
                    ->icon('heroicon-o-map-pin'),
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                      return AddressForm::mutate($data);
                    })
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        return AddressForm::mutate($data);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->tabs([
                        Tabs\Tab::make('Address Info')
                            ->icon('heroicon-o-map')
                            ->translateLabel()
                            ->schema(AddressForm::schema()),
                        Tabs\Tab::make('Description')
                            ->icon('heroicon-o-bookmark')
                            ->translateLabel()
                            ->schema([
                                Forms\Components\Textarea::make('description')
                                    ->rows(5)
                                    ->label(__('Address description'))
                                    ->helperText('در صورت نیاز لطفا توضیحات آدرس را در این قسمت وارد نمایید'),
                            ]),
                    ]),
            ]);
    }
}
