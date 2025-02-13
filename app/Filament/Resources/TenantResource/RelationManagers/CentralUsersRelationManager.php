<?php

namespace App\Filament\Resources\TenantResource\RelationManagers;

use App\Domain\Tenant\Actions\CreateTenantUserAction;
use App\Domain\Tenant\Actions\RemoveTenantUserAction;
use App\Domain\User\Models\CentralUser;
use App\Domain\User\Models\User;
use App\Filament\Resources\CentralUserResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class CentralUsersRelationManager extends RelationManager
{
    protected static string $relationship = 'centralUsers';

    protected static ?string $icon = 'heroicon-o-user-group';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('resources.central_user.plural_label');
    }

    public function form(Form $form): Form
    {
        return CentralUserResource::form($form);
    }

    public function table(Table $table): Table
    {
        $tenantRoles = $this->getOwnerRecord()->run(function () {
            return Role::select('id', 'name', 'guard_name')->get();
        });

        return $table
            ->recordTitleAttribute('email')
            ->modelLabel(__('resources.central_user.singular_label'))
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('phone')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('roles')
                    ->state(
                        function (CentralUser $record): ?string {
                            return $this->getOwnerRecord()->run(
                                function () use ($record): string {
                                    $user = User::where('global_id', $record->global_id)->first();

                                    return $user ? $user->getRoleNames()->implode(', ') : '-';
                                }
                            );
                        }
                    )
                    ->badge()
                    ->color('success')
                    ->separator(','),
                Tables\Columns\TextColumn::make('created_at')
                    ->datetime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->attachAnother(false)
                    ->preloadRecordSelect()
                    ->form(
                        function (AttachAction $action) use ($tenantRoles): array {
                            return [
                                $action->getRecordSelect(),
                                Forms\Components\Select::make('roles')
                                    ->label(__('filament-shield::filament-shield.resource.label.role'))
                                    ->searchable()
                                    ->multiple()
                                    ->preload()
                                    ->options($tenantRoles->pluck('name', 'id'))
                                    ->required(),
                            ];
                        }
                    )
                    ->after(
                        function (
                            array $data,
                            RelationManager $livewire,
                            CentralUser $record,
                            CreateTenantUserAction $createTenantUserAction
                        ): void {
                            $rolesIds = $data['roles'];
                            $tenant = $livewire->getOwnerRecord();
                            $createTenantUserAction->execute($tenant, $record, $rolesIds);
                        }
                    )
                    ->visible(Gate::allows('attachCentralUser', $this->getOwnerRecord())),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make()
                    ->after(
                        function (
                            RelationManager $livewire,
                            CentralUser $record,
                            RemoveTenantUserAction $removeTenantUserAction
                        ): void {
                            $tenant = $livewire->getOwnerRecord();
                            $removeTenantUserAction->execute($tenant, $record);
                        }
                    )
                    ->visible(Gate::allows('detachCentralUser', $this->getOwnerRecord())),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make()
                        ->after(
                            function (
                                RelationManager $livewire,
                                Collection $records,
                                RemoveTenantUserAction $removeTenantUserAction
                            ): void {
                                $tenant = $livewire->getOwnerRecord();
                                $removeTenantUserAction->execute($tenant, $records);
                            }
                        )
                        ->visible(Gate::allows('detachCentralUser', $this->getOwnerRecord())),
                ]),
            ]);
    }
}
