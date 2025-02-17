<?php

namespace App\Domain\Classification\Policies;

use App\Domain\Classification\Models\Tag;
use App\Domain\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class TagPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_tag');
    }

    public function view(User $user, Tag $record): bool
    {
        $canNotUpdate = Gate::denies('update', $record);
        $canView = $user->can('view_tag');

        return $canNotUpdate && $canView;
    }

    public function create(User $user): bool
    {
        return $user->can('create_tag');
    }

    public function update(User $user): bool
    {
        return $user->can('update_tag');
    }

    public function delete(User $user): bool
    {
        return $user->can('delete_tag');
    }
}
