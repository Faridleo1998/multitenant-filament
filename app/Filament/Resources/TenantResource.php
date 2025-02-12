<?php

namespace App\Filament\Resources;

use App\Domain\Tenant\Models\Tenant;
use App\Filament\Resources\TenantResource\Pages;
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
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->autocomplete(false),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->autocomplete(false),
                        PhoneInput::make('phone')
                            ->required(),
                        Forms\Components\TextInput::make('email_contact')
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
                            ->nullable()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('country_id')
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
                    ->toggleable()
                    ->searchable()
                    ->hidden(! Auth::user()->is_super_admin),
                Tables\Columns\TextColumn::make('identification')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('created_at')
                    ->datetime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'view' => Pages\ViewTenant::route('/{record}'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
        ];
    }
}
