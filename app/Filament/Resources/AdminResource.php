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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Rawilk\FilamentPasswordInput\Password;
use STS\FilamentImpersonate\Tables\Actions\Impersonate;

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
                    ->label(__('labels.full_name'))
                    ->required()
                    ->columnSpanFull()
                    ->disabled(
                        fn(?Admin $record): bool => $record?->is_super_admin ?? false
                    ),
                Forms\Components\TextInput::make('email')
                    ->label(__('labels.email'))
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
                    ->label(__('labels.password'))
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
                        fn(?Admin $record): bool => $record?->is_super_admin || Auth::id() === $record?->id
                    ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('labels.full_name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('labels.email'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->badge()
                    ->color('success')
                    ->separator(','),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('labels.created_at'))
                    ->datetime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Impersonate::make()
                    ->redirectTo('admin'),
                Tables\Actions\ViewAction::make()
                    ->modalWidth(MaxWidth::Small),
                Tables\Actions\EditAction::make()
                    ->modalWidth(MaxWidth::Small),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAdmins::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with(['roles:id,name'])
            ->select(['id', 'name', 'email', 'created_at', 'deleted_at']);

        Auth::user()->is_super_admin ?: $query->whereNot('id', Admin::SUPER_ADMIN_ID);

        if (Gate::allows('restore', Admin::class)) {
            $query->withTrashed();
        }

        return $query;
    }
}
