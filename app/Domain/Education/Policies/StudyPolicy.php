<?php

namespace App\Domain\Education\Policies;

use App\Domain\Education\Models\Study;
use App\Domain\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class StudyPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_study');
    }

    public function view(User $user, Study $record): bool
    {
        $canNotUpdate = Gate::denies('update', $record);
        $canView = $user->can('view_study');

        return $canNotUpdate && $canView;
    }

    public function create(User $user): bool
    {
        return $user->can('create_study');
    }

    public function update(User $user): bool
    {
        return $user->can('update_study');
    }

    public function delete(User $user): bool
    {
        return $user->can('delete_study');
    }
}
