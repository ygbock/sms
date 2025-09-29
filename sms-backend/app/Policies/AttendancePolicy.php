<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Attendance;

class AttendancePolicy
{
    public function create(User $user)
    {
        // Teachers and Admins can record attendance
        $role = optional($user->role)->name;
        return in_array($role, ['Admin', 'Teacher']);
    }

    public function viewReport(User $user)
    {
        // Only Admins (and possibly Teachers) can view admin reports
        $role = optional($user->role)->name;
        return in_array($role, ['Admin', 'Teacher']);
    }

    public function viewOwn(User $user)
    {
        // Students (via their user) and Teachers can view their attendance
        return true;
    }
}
