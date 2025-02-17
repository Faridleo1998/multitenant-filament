<?php

namespace App\Domain\Classification\Policies;

use App\Domain\Classification\Models\Category;
use App\Domain\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_category');
    }

    public function view(User $user, Category $record): bool
    {
        $canNotUpdate = Gate::denies('update', $record);
        $canView = $user->can('view_category');

        return $canNotUpdate && $canView;
    }

    public function create(User $user): bool
    {
        return $user->can('create_category');
    }

    public function update(User $user): bool
    {
        return $user->can('update_category');
    }

    public function delete(User $user): bool
    {
        return $user->can('delete_category');
    }
}
