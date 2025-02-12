<?php

namespace App\Filament\Resources\CentralUserResource\RelationManagers;

use App\Domain\Tenant\Actions\CreateTenantUserAction;
use App\Domain\Tenant\Actions\RemoveTenantUserAction;
use App\Domain\Tenant\Models\Tenant;
use App\Filament\Resources\TenantResource;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class TenantsRelationManager extends RelationManager
{
    protected static string $relationship = 'tenants';

    protected static ?string $icon = 'heroicon-o-home-modern';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return ucfirst(TenantResource::getPluralModelLabel());
    }

    public function form(Form $form): Form
    {
        return TenantResource::form($form);
    }

    public function table(Table $table): Table
    {
        return TenantResource::table($table)
            ->recordTitleAttribute('name')
            ->modelLabel(ucfirst(TenantResource::getModelLabel()))
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->attachAnother(false)
                    ->preloadRecordSelect()
                    ->after(
                        function (
                            RelationManager $livewire,
                            Tenant $tenant,
                            CreateTenantUserAction $createTenantUserAction
                        ): void {
                            $centralUser = $livewire->getOwnerRecord();
                            $createTenantUserAction->execute($tenant, $centralUser);
                        }
                    )
                    ->visible(Gate::allows('attachTenant', $this->getOwnerRecord())),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DetachAction::make()
                    ->after(
                        function (
                            RelationManager $livewire,
                            Tenant $tenant,
                            RemoveTenantUserAction $removeTenantUserAction
                        ): void {
                            $centralUser = $livewire->getOwnerRecord();
                            $removeTenantUserAction->execute($tenant, $centralUser);
                        }
                    )
                    ->visible(Gate::allows('detachTenant', $this->getOwnerRecord())),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make()
                        ->after(
                            function (
                                RelationManager $livewire,
                                Collection $tenants,
                                RemoveTenantUserAction $removeTenantUserAction
                            ): void {
                                $centralUser = $livewire->getOwnerRecord();
                                $removeTenantUserAction->execute($tenants, $centralUser);
                            }
                        )
                        ->visible(Gate::allows('detachTenant', $this->getOwnerRecord())),
                ]),
            ]);
    }
}
