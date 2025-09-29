<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;

use App\Models\Student;
use App\Models\Section;
use App\Models\SchoolClass;

use App\Policies\StudentPolicy;
use App\Policies\SectionPolicy;
use App\Policies\SchoolClassPolicy;
use App\Policies\AttendancePolicy;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->registerPolicies();
    }

    protected function registerPolicies(): void
    {
        Gate::policy(Student::class, StudentPolicy::class);
        Gate::policy(Section::class, SectionPolicy::class);
        Gate::policy(SchoolClass::class, SchoolClassPolicy::class);
    Gate::policy(\App\Models\Attendance::class, AttendancePolicy::class);
    }
}
