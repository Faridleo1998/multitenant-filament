<?php

namespace App\Filament\Tenant\Resources;

use App\Domain\Classification\Models\Category;
use App\Filament\Tenant\Resources\CategoryResource\Pages;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CategoryResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';

    protected static ?string $navigationGroup = 'Catalog';

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
                    ->maxLength(50)
                    ->unique(ignoreRecord: true)
                    ->live(onBlur: true)
                    ->afterStateUpdated(
                        function (Set $set, ?string $state, string $context): void {
                            if ($state === null || $state === '' || $context === 'edit') {
                                return;
                            }
                            $set('slug', Str::slug($state));
                        }
                    )
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')
                    ->label(__('labels.is_active'))
                    ->default(true)
                    ->hidden(
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
                    ->label(__('labels.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->limit(20)
                    ->tooltip(
                        function (TextColumn $column): ?string {
                            $state = $column->getState();
                            if (strlen($state) <= $column->getCharacterLimit()) {
                                return null;
                            }

                            return $state;
                        }
                    ),
                Tables\Columns\Iconcolumn::make('is_active')
                    ->label(__('labels.is_active')),
                Tables\Columns\Textcolumn::make('created_at')
                    ->label(__('labels.created_at'))
                    ->sortable()
                    ->datetime(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('labels.is_active')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalWidth(MaxWidth::Small),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCategories::route('/'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('resources.category.singular_label');
    }
}
