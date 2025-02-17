<?php

namespace App\Filament\Tenant\Resources;

use App\Domain\Education\Models\Profession;
use App\Filament\Tenant\Resources\ProfessionResource\Pages;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;

class ProfessionResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Profession::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Education';

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
                    ->label(__('labels.name'))
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Textcolumn::make('name')
                    ->label(__('labels.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\Textcolumn::make('created_at')
                    ->label(__('labels.created_at'))
                    ->sortable()
                    ->datetime(),
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
            'index' => Pages\ManageProfessions::route('/'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('resources.profession.singular_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resources.profession.plural_label');
    }
}
