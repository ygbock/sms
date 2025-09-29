<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Student;

class StudentPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array(optional($user->role)->name, ['Admin','Teacher']);
    }

    public function view(User $user, Student $student): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return optional($user->role)->name === 'Admin';
    }

    public function update(User $user, Student $student): bool
    {
        return optional($user->role)->name === 'Admin';
    }

    public function delete(User $user, Student $student): bool
    {
        return optional($user->role)->name === 'Admin';
    }
}
