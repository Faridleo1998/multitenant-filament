<?php

namespace App\Filament\Tenant\Resources;

use App\Domain\Classification\Models\Tag;
use App\Filament\Tenant\Resources\TagResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Catalog';

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
            'index' => Pages\ManageTags::route('/'),
        ];
    }
}
