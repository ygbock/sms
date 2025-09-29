<?php
namespace App\Policies;

use App\Models\User;
use App\Models\SchoolClass;

class SchoolClassPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array(optional($user->role)->name, ['Admin','Teacher']);
    }

    public function create(User $user): bool
    {
        return optional($user->role)->name === 'Admin';
    }
}
