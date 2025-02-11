<?php

namespace App\Filament\Resources;

use App\Domain\Admin\Models\Admin;
use App\Filament\Resources\AdminResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Rawilk\FilamentPasswordInput\Password;

class AdminResource extends Resource
{
    protected static ?string $model = Admin::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->endsWith('@' . config('system.domain'))
                    ->autocomplete(false)
                    ->columnSpanFull()
                    ->placeholder('jhon_doe@' . config('system.domain')),
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
