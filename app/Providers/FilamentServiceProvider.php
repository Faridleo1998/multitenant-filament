<?php

namespace App\Providers;

use Filament\Actions\CreateAction;
use Filament\Actions\MountableAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\Page;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->actions();
        $this->inputs();
        $this->modals();
        $this->pages();
        $this->tables();
    }

    public function actions(): void
    {
        CreateAction::configureUsing(function (CreateAction $action): void {
            $action->createAnother(false);
        });
    }

    public function inputs(): void
    {
        TextInput::configureUsing(function (TextInput $textInput): void {
            $textInput
                ->dehydrateStateUsing(function (?string $state): ?string {
                    return is_string($state) ? trim($state) : $state;
                });
        });

        Toggle::configureUsing(function (Toggle $toggle): void {
            $toggle->inline(false);
        });
    }

    public function modals(): void
    {
        MountableAction::configureUsing(function (MountableAction $action): void {
            $action->modalFooterActionsAlignment(Alignment::Right);
        });
    }

    public function pages(): void
    {
        Page::formActionsAlignment('right');
    }

    public function tables(): void
    {
        Table::$defaultDateTimeDisplayFormat = 'M j, Y g:i a';

        Table::$defaultDateDisplayFormat = 'M j, Y';

        Table::configureUsing(function (Table $table): void {
            $table->paginated([5, 10, 25, 50])
                ->deferFilters()
                ->filtersApplyAction(
                    fn(Action $action): Action => $action->close(),
                );
        });
    }
}
