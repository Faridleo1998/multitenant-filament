<?php

namespace App\Filament\Resources;

use App\Domain\User\Models\CentralUser;
use App\Filament\Resources\CentralUserResource\Pages;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Rawilk\FilamentPasswordInput\Password;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class CentralUserResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = CentralUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'restore',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(100),
                        PhoneInput::make('phone'),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true),
                        Password::make('password')
                            ->autocomplete(false)
                            ->regeneratePassword(notify: false)
                            ->copyable()
                            ->maxLength(16)
                            ->minLength(8)
                            ->afterStateHydrated(
                                function (Forms\Components\TextInput $component): void {
                                    $component->state('');
                                }
                            )
                            ->dehydrateStateUsing(
                                fn(?string $state): string => Hash::make($state)
                            )
                            ->dehydrated(
                                fn(?string $state): mixed => filled($state)
                            )
                            ->required(
                                fn(string $context): bool => $context === 'create'
                            ),
                    ])
                    ->columns([
                        'md' => 2,
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Textcolumn::make('name')
                    ->searchable(),
                Tables\Columns\Textcolumn::make('email')
                    ->searchable(),
                Tables\columns\Textcolumn::make('phone')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('tenants_count')
                    ->counts('tenants')
                    ->badge()
                    ->color('success')
                    ->alignCenter(),
                Tables\Columns\Textcolumn::make('created_at')
                    ->datetime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
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
            'index' => Pages\ListCentralUsers::route('/'),
            'create' => Pages\CreateCentralUser::route('/create'),
            'view' => Pages\ViewCentralUser::route('/{record}'),
            'edit' => Pages\EditCentralUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (Gate::allows('restore', CentralUser::class)) {
            $query->withTrashed();
        }

        return $query;
    }

    public static function getModelLabel(): string
    {
        return __('resources.central_user.singular_label');
    }
}
