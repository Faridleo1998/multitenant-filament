<?php

namespace App\Filament\Resources;

use App\Domain\Tenant\Models\Tenant;
use App\Filament\Resources\TenantResource\Pages;
use App\Filament\Resources\TenantResource\RelationManagers\CentralUsersRelationManager;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Nnjeim\World\Models\City;
use Nnjeim\World\Models\Country;
use Nnjeim\World\Models\State;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class TenantResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Tenant::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'attach_central_user',
            'detach_central_user',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('resources.tenant.sections.information'))
                    ->icon('heroicon-o-identification')
                    ->columns([
                        'md' => 3,
                    ])
                    ->schema([
                        Forms\Components\TextInput::make('identification')
                            ->label(__('labels.identification'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->autocomplete(false),
                        Forms\Components\TextInput::make('name')
                            ->label(__('labels.name'))
                            ->required()
                            ->maxLength(100)
                            ->live(onBlur: true)
                            ->afterStateUpdated(
                                function (Set $set, ?string $state, string $context): void {
                                    if ($state === null || $state === '' || $context === 'edit') {
                                        return;
                                    }
                                    $set('domain', Str::slug($state));
                                }
                            ),
                        Forms\Components\TextInput::make('domain')
                            ->label(__('labels.domain'))
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->notIn([config('system.domain'), 'http://', 'https://', '.'])
                            ->prefix('https://')
                            ->suffix('.' . config('system.domain')),

                        PhoneInput::make('phone')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->label(__('labels.email'))
                            ->email()
                            ->nullable(),
                    ]),
                Forms\Components\Section::make(__('resources.tenant.sections.location'))
                    ->icon('heroicon-o-map-pin')
                    ->columns([
                        'md' => 3,
                    ])
                    ->schema([
                        Forms\Components\TextInput::make('address')
                            ->label(__('labels.address'))
                            ->nullable()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('country_id')
                            ->label(__('labels.country'))
                            ->options(
                                Country::select(['id', 'name'])
                                    ->pluck('name', 'id')
                                    ->toArray()
                            )
                            ->optionsLimit(10)
                            ->searchable(['name'])
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (Set $set): void {
                                $set('state_id', null);
                                $set('city_id', null);
                            }),
                        Forms\Components\Select::make('state_id')
                            ->label(__('labels.state'))
                            ->options(
                                fn(Get $get): Collection => State::select(['id', 'name'])
                                    ->where('country_id', $get('country_id'))
                                    ->pluck('name', 'id')
                            )
                            ->optionsLimit(10)
                            ->searchable(['name'])
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn(Set $set) => $set('city_id', null)),
                        Forms\Components\Select::make('city_id')
                            ->label(__('labels.city'))
                            ->options(
                                fn(Get $get): Collection => City::select(['id', 'name'])
                                    ->where('state_id', $get('state_id'))
                                    ->pluck('name', 'id')
                            )
                            ->optionsLimit(10)
                            ->searchable()
                            ->preload()
                            ->live(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->copyable()
                    ->toggleable()
                    ->hidden(! Auth::user()->is_super_admin),
                Tables\Columns\TextColumn::make('identification')
                    ->label(__('labels.identification'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('labels.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('domain.domain')
                    ->label(__('labels.domain'))
                    ->badge()
                    ->color('success')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('labels.email'))
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('labels.phone')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('labels.created_at'))
                    ->datetime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make(__('actions.named.view', [
                        'name' => strtolower(__('labels.site')),
                    ]))
                        ->icon('heroicon-m-globe-alt')
                        ->color('info')
                        ->url(
                            fn(Tenant $record): string => 'https://' . $record->load('domains')->domains->first()?->domain
                        )
                        ->openUrlInNewTab(),
                ]),

            ]);
    }

    public static function getRelations(): array
    {
        return [
            CentralUsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'view' => Pages\ViewTenant::route('/{record}'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
        ];
    }
}
