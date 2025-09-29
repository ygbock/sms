<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Section;

class SectionPolicy
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
