<?php

namespace App\Domain\Classification\Policies;

use App\Domain\Classification\Models\Keyword;
use App\Domain\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class KeywordPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_keyword');
    }

    public function view(User $user, Keyword $record): bool
    {
        $canNotUpdate = Gate::denies('update', $record);
        $canView = $user->can('view_keyword');

        return $canNotUpdate && $canView;
    }

    public function create(User $user): bool
    {
        return $user->can('create_keyword');
    }

    public function update(User $user): bool
    {
        return $user->can('update_keyword');
    }

    public function delete(User $user): bool
    {
        return $user->can('delete_keyword');
    }
}
