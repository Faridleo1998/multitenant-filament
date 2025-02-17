<?php

namespace App\Filament\Tenant\Resources;

use App\Domain\User\Models\User;
use App\Filament\Tenant\Resources\UserResource\Pages;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Rawilk\FilamentPasswordInput\Password;
use STS\FilamentImpersonate\Tables\Actions\Impersonate;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class UserResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = -2;

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('labels.full_name'))
                    ->required()
                    ->maxLength(100)
                    ->columnSpanFull(),
                PhoneInput::make('phone')
                    ->label(__('labels.phone'))
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('email')
                    ->label(__('labels.email'))
                    ->email()
                    ->required()
                    ->maxLength(100)
                    ->unique(ignoreRecord: true)
                    ->columnSpanFull(),
                Password::make('password')
                    ->label(__('labels.password'))
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
                    )
                    ->columnSpanFull(),
                Forms\Components\Select::make('roles')
                    ->relationship(
                        'roles',
                        'name',
                        modifyQueryUsing: function (Builder $query, ?User $record): Builder {
                            return $record?->hasRole('super_admin')
                                ? $query
                                : $query->whereNot('name', 'super_admin');
                        }
                    )
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->columnSpanFull()
                    ->disabled(
                        fn(?User $record): bool => Auth::id() === $record?->id
                    ),

            ])
            ->columns([
                'md' => 2,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Textcolumn::make('name')
                    ->label(__('labels.full_name'))
                    ->searchable(),
                Tables\Columns\Textcolumn::make('email')
                    ->label(__('labels.email'))
                    ->searchable(),
                Tables\columns\Textcolumn::make('phone')
                    ->label(__('labels.phone'))
                    ->placeholder('-'),
                Tables\Columns\Textcolumn::make('created_at')
                    ->label(__('labels.created_at'))
                    ->datetime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Impersonate::make()
                    ->redirectTo('tenant'),
                Tables\Actions\ViewAction::make()
                    ->modalWidth(MaxWidth::Small),
                Tables\Actions\EditAction::make()
                    ->modalWidth(MaxWidth::Small),
                Tables\Actions\DeleteAction::make()
                    ->after(function (
                        User $record,
                    ): void {
                        $tenant = tenancy()->tenant;
                        $tenant->centralUsers()->detach($record->global_id);
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('resources.central_user.singular_label');
    }
}
