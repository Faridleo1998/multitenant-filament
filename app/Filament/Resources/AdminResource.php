<?php

namespace App\Filament\Resources;

use App\Domain\Admin\Models\Admin;
use App\Filament\Resources\AdminResource\Pages;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Rawilk\FilamentPasswordInput\Password;

class AdminResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Admin::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Settings';

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
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->columnSpanFull()
                    ->disabled(
                        fn(?Admin $record): bool => $record?->is_super_admin ?? false
                    ),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->endsWith('@' . config('system.domain'))
                    ->autocomplete(false)
                    ->columnSpanFull()
                    ->placeholder('jhon_doe@' . config('system.domain'))
                    ->disabled(
                        fn(?Admin $record): bool => $record?->is_super_admin ?? false
                    ),
                Password::make('password')
                    ->autocomplete(false)
                    ->regeneratePassword(notify: false)
                    ->maxLength(16)
                    ->minLength(8)
                    ->dehydrateStateUsing(
                        fn($state): string => Hash::make($state)
                    )
                    ->dehydrated(
                        fn($state): bool => filled($state)
                    )
                    ->required(
                        fn(string $context): bool => $context === 'create'
                    )
                    ->columnSpanFull(),
                Forms\Components\Select::make('roles')
                    ->relationship(
                        'roles',
                        'name',
                        modifyQueryUsing: function (Builder $query, ?Admin $record): Builder {
                            return $record?->is_super_admin
                                ? $query
                                : $query->whereNot('name', 'super_admin');
                        }
                    )
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->columnSpanFull()
                    ->disabled(
                        fn(?Admin $record): bool => $record?->is_super_admin ?? false
                    ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->datetime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalWidth(MaxWidth::Small),
                Tables\Actions\EditAction::make()
                    ->modalWidth(MaxWidth::Small),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAdmins::route('/'),
        ];
    }
}
