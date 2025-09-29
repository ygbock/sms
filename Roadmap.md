# Development Roadmap — Laravel + Next.js School Management System

## Phase 1 — Foundation (Backend First)

**Goal:** Establish the Laravel backend as the system’s core.

1. **Project Setup**

   * Initialize Laravel project (API mode).
   * Configure database (PostgreSQL/MySQL).
   * Setup authentication (Laravel Sanctum or Passport for API tokens).
   * Define RBAC (roles: Admin, Teacher, Student, Parent).

2. **Core Entities & Models**

   * Users, Roles, Permissions.
   * Student Profiles, Enrollment, Classes, Subjects.
   * Staff Profiles.

3. **Core Modules (Backend)**

   * Student Information System (SIS).
  * Attendance (basic CRUD).  <!-- DONE: backend endpoints, policy, seeder, and sample data added (2025-09-29) -->
   * Gradebook (simple assessments).
   * Fee Management (invoices, receipts).

4. **APIs**

   * REST/GraphQL endpoints for core modules.
   * JWT-based authentication.

---

## Phase 2 — Frontend Foundations (Next.js Integration)

**Goal:** Provide modern, responsive portals for different user roles.

1. **Next.js Setup**

   * Initialize Next.js with TypeScript + Tailwind.
   * Implement API client for Laravel backend.
   * Setup authentication flow (login, logout, token refresh).

2. **Dashboards**

   * **Admin:** Manage users, classes, fees.
   * **Teacher:** Take attendance, enter grades.
   * **Parent:** View child’s progress, pay fees.
   * **Student:** View timetable, grades, notices.

3. **UI Components**

   * Layout system (sidebar, navbar).
   * Tables (students, grades, attendance).
   * Forms (profile update, grade entry).

---

## Phase 3 — Extended Features

**Goal:** Add advanced modules and improve UX.

1. **Backend (Laravel)**

   * Examinations: exam schedules, results.
   * Timetable generation.
   * Notifications (email/SMS integration).
   * Payroll & HR basics.
   * Library & Inventory system.

2. **Frontend (Next.js)**

   * Rich dashboards with charts (Recharts, Chart.js).
   * Messaging system (teacher ↔ parent).
   * Attendance analytics.
   * Exportable reports (CSV, PDF).

3. **Mobile Responsiveness**

   * Optimize all dashboards for smartphones.
   * Consider PWA features (push notifications, offline mode).

---

## Phase 4 — Scaling & Advanced Integrations

**Goal:** Ensure scalability, introduce real-time and predictive features.

1. **Real-Time Features**

   * Laravel Echo + Redis + WebSockets for live attendance and notifications.
   * Next.js subscription hooks for instant UI updates.

2. **Scalability Improvements**

   * Dockerize Laravel & Next.js.
   * Deploy with Kubernetes or cloud services.
   * Load testing & performance tuning.

3. **Integrations**

   * Payment gateways (Stripe/local).
   * Learning Management Systems (Moodle, Canvas).
   * Government reporting APIs.

4. **Advanced Analytics**

   * Predictive insights (student performance forecasting).
   * Early warning system for at-risk students.

---

## Phase 5 — Mobile Apps & AI Add-ons (Optional)

**Goal:** Expand usability and intelligence.

1. **Mobile Apps**

   * React Native or Flutter apps for parents/teachers/students.
   * Consume Laravel APIs (same as web).

2. **AI/ML Features**

   * Automated grading assistance.
   * Chatbot for FAQs and parent inquiries.
   * Personalized learning recommendations.

---

## Ongoing

* Continuous Integration & Deployment (CI/CD) via GitHub Actions.
* Monitoring (Grafana, Prometheus, Sentry).
* Regular security audits.

---

✅ Following this roadmap, you’ll start with a stable Laravel backend, gradually integrate Next.js portals, and finally scale into advanced, modern features.

# Entity Relationship Diagram (ERD) — School Management System

Below is a conceptual ERD for the multi-level school management system (Primary, Junior, Senior). It shows core entities, their attributes, and relationships.

---

## Core Entities & Relationships

```
+-------------------+        +-------------------+
|      School       |1     n|     Campus        |
+-------------------+        +-------------------+
| id                |        | id                |
| name              |        | name              |
| address           |        | school_id (FK)    |
+-------------------+        +-------------------+
          1 |                          1 |
            | n                          | n
            v                            v
+-------------------+        +-------------------+
|   AcademicYear    |1     n|      Term         |
+-------------------+        +-------------------+
| id                |        | id                |
| year_start        |        | name              |
| year_end          |        | start_date        |
| campus_id (FK)    |        | end_date          |
+-------------------+        | academic_year_id  |
                             +-------------------+

+-------------------+        +-------------------+
|      Class        |1     n|     Section       |
+-------------------+        +-------------------+
| id                |        | id                |
| name              |        | name              |
| level (Primary/..) |        | class_id (FK)    |
| campus_id (FK)    |        +-------------------+
+-------------------+

+-------------------+        +-------------------+
|     Subject       |        | TeacherSubject    |
+-------------------+        +-------------------+
| id                |        | id                |
| name              |        | teacher_id (FK)   |
| code              |        | subject_id (FK)   |
| class_id (FK)     |        | section_id (FK)   |
+-------------------+        +-------------------+

+-------------------+        +-------------------+
|     Student       |        |    Enrollment     |
+-------------------+        +-------------------+
| id                |        | id                |
| user_id (FK)      |        | student_id (FK)   |
| admission_no      |        | section_id (FK)   |
| dob               |        | academic_year_id  |
| guardian_id (FK)  |        +-------------------+
+-------------------+

+-------------------+        +-------------------+
|    Guardian       |        |    Attendance     |
+-------------------+        +-------------------+
| id                |        | id                |
| user_id (FK)      |        | enrollment_id (FK)|
| relation          |        | date              |
+-------------------+        | status (P/A/L)    |
                             +-------------------+

+-------------------+        +-------------------+
|    Assessment     |        |       Grade       |
+-------------------+        +-------------------+
| id                |        | id                |
| subject_id (FK)   |        | assessment_id (FK)|
| teacher_id (FK)   |        | student_id (FK)   |
| term_id (FK)      |        | marks             |
| type (Exam/Test)  |        | grade_letter      |
+-------------------+        +-------------------+

+-------------------+        +-------------------+
|      Invoice      |        |     Payment       |
+-------------------+        +-------------------+
| id                |        | id                |
| student_id (FK)   |        | invoice_id (FK)   |
| amount            |        | amount            |
| due_date          |        | payment_date      |
| status            |        | method            |
+-------------------+        +-------------------+

+-------------------+        +-------------------+
|   StaffProfile    |        |     User          |
+-------------------+        +-------------------+
| id                |        | id                |
| user_id (FK)      |        | name              |
| employee_no       |        | email             |
| role (Teacher, ..)|        | password_hash     |
+-------------------+        | role_id (FK)      |
                             +-------------------+

+-------------------+
|       Role        |
+-------------------+
| id                |
| name              |
| permissions       |
+-------------------+
```

---

## Key Notes

* **User** is the base entity (login credentials, profile info). Roles differentiate Admin, Teacher, Student, Guardian.
* **Student ↔ Enrollment ↔ Section** manages where a student belongs each year.
* **Guardian** linked to student via foreign key.
* **TeacherSubject** links teachers to subjects & sections.
* **Assessments** and **Grades** track performance.
* **Invoices** and **Payments** manage finance.
* **Attendance** links to Enrollment for daily presence.

---

✅ This ERD provides a foundation for database design. It can be extended with HR (payroll), Library, Transport, and Asset modules as needed.

# Sprint 0 & Sprint 1 — Kickoff & Initial Implementation

**Context:** Acting as the lead developer, this document translates the roadmap into an actionable developer plan and the concrete steps to start implementation for the Laravel backend + Next.js frontend School Management System.

---

## Sprint Goals

* **Sprint 0 (Kickoff & Foundation):** Project scaffolding, repo setup, baseline conventions, CI, and initial Laravel API in API mode with Auth and core models.
* **Sprint 1 (MVP Core):** Implement core SIS models and endpoints (Users, Students, Enrollments, Classes/Sections), teacher auth, basic attendance API, and a Next.js skeleton with auth flow and basic dashboards.

---

## Deliverables (Sprint 0)

* GitHub org & repos: `sms-backend` (Laravel), `sms-frontend` (Next.js), `infra` (deployment manifests).
* Development conventions & CONTRIBUTING.md.
* CI pipeline (GitHub Actions) for tests and linting.
* Initial Laravel project (API): auth, RBAC seed, migrations for Users, Roles, Students, Classes, Sections.
* Initial Next.js project with TypeScript + Tailwind and API client scaffold.

---

## Repo Structure (recommended)

### sms-backend (Laravel)

```
/app
  /Http
    /Controllers
  /Models
  /Policies
/config
/database
  /migrations
  /seeders
/routes
  api.php
/tests
Dockerfile
docker-compose.yml
README.md
```

### sms-frontend (Next.js)

```
/pages
  /api (client-side only)
/components
/lib
  /api-client.ts
/styles
tailwind.config.js
next.config.js
README.md
```

---

## Concrete Terminal Commands (copy & run locally)

### Backend (Laravel - API mode)

```bash
# create project
composer create-project laravel/laravel sms-backend --prefer-dist
cd sms-backend
# install sanctum for SPA/API auth
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
# create env and app key
cp .env.example .env
php artisan key:generate
```

### Frontend (Next.js + TypeScript + Tailwind)

```bash
npx create-next-app@latest sms-frontend --typescript
cd sms-frontend
# install Tailwind
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
# install axios or react-query
npm install axios
```

---

## Initial Database Migrations (examples)

### users migration (Laravel default)

* id, name, email, password, role_id, created_at, updated_at

### roles migration

* id, name (Admin, SchoolAdmin, Teacher, Student, Parent), permissions (json)

### students migration

* id, user_id (FK), admission_no, dob, guardian_id, current_section_id

### classes & sections

* classes: id, name, level (primary/junior/senior), campus_id
* sections: id, name, class_id, teacher_id

Be sure to add foreign keys and indexes for common lookup columns.

---

## Seeders

* Create `RolesSeeder` (Admin, Teacher, Student, Parent) and a `SuperAdmin` user.
* Create sample Class, Section, and a small set of students for local dev/testing.

---

## API Design (Initial endpoints)

* `POST /api/auth/register` — register (admins only via invite)
* `POST /api/auth/login` — login (returns token)
* `GET /api/users/me` — current user
* `GET /api/students` — list (RBAC-filtered)
* `POST /api/students` — create student
* `GET /api/classes` — list classes
* `GET /api/sections` — list sections
* `POST /api/attendance` — record attendance (payload: enrollment_id, date, status)

Provide OpenAPI later; for now implement REST controllers and tests.

---

## Authentication & RBAC

* Use **Laravel Sanctum** for API tokens (SPA friendly).
* Create a `Role` model and a `Permission` concept (either via `spatie/laravel-permission` or custom simple JSON permissions on Role).
* Middleware `EnsureUserHasRole` + policy classes for resources (StudentPolicy, AttendancePolicy).

---

## Next.js Frontend Skeleton

* Auth pages: /login, /logout, /register (invite flow for admins).
* Layouts: `AdminLayout`, `TeacherLayout`, `ParentLayout`.
* Example pages: `/teacher/dashboard`, `/parent/child/123`, `/admin/users`.
* API client: axios instance with token interceptor and refresh logic.

---

## First 10 Actionable Tasks (Sprint 0)

1. Create GitHub repos and invite team members & CI bot.
2. Initialize Laravel repo and push initial commit (composer create-project).
3. Initialize Next.js repo and push initial commit.
4. Add CONTRIBUTING.md, CODE_OF_CONDUCT, LICENSE.
5. Add GitHub Actions for PHP and Node (basic lint & test).
6. Implement Roles table + RolesSeeder + SuperAdmin user.
7. Implement Users migration + model + basic auth endpoints.
8. Implement Classes & Sections migrations + models + seed data.
9. Implement Students migration + Enrollment model + seed data.
10. Build Next.js auth flow and fetch `/api/users/me` to render role-specific layout.

---

## Development Conventions

* Follow PSR-12 for PHP. Use `php-cs-fixer` or `laravel Pint`.
* Use ESLint + Prettier for frontend; TypeScript strict mode on.
* Write tests: PHPUnit for backend, Jest/React Testing Library for frontend.
* Branch strategy: `main` (protected) -> feature branches `feat/<ticket>` -> PR -> code review -> merge.

---

## CI / CD (starter)

* GitHub Actions pipeline: `lint`, `test`, `build` for both repos.
* On merge to `main`, run `build` and deploy image to container registry.
* Use separate staging & production environments in infra repo (Helm charts or simple k8s manifests).

---

## Security & Compliance Notes

* Hash passwords (bcrypt), enforce strong passwords for staff accounts.
* 2FA for admin roles (time-based or SMS) later.
* Mask PII in logs. Use TLS everywhere.

---

## Acceptance Criteria (for Sprint 1 core features)

* SuperAdmin can log in and create Users (Teacher, Parent, Student).
* Teachers can log in and see a cohort of their assigned sections.
* Admin can create Classes and Sections via API and frontend forms.
* Attendance can be recorded via the API and visible on the teacher dashboard.
* End-to-end flows have basic tests covering critical paths.

---

## Next Steps I Can Do Now (choose one)

* Generate the initial `composer.json`, `package.json`, and Dockerfile templates.
* Create starter migrations and seeders for Roles, Users, Classes, Sections, Students.
* Generate a boilerplate Next.js app with auth pages and axios client.

Pick one and I will create the initial code scaffolding right here.

Here’s how the provided JSON configuration breaks down into individual project files and their contents:

---

📂 **Backend (Laravel)**

**`backend/composer.json`**
```json
{
  "name": "school-management-system/backend",
  "description": "Laravel backend for School Management System",
  "type": "project",
  "require": {
    "php": "^8.1",
    "laravel/framework": "^12.0",
    "laravel/sanctum": "^4.2",
    "spatie/laravel-permission": "^5.10"
  },
  "autoload": {
    "psr-4": {
      "App\\": "app/"
    }
  },
  "scripts": {
    "post-root-package-install": [
      "php -r \"file_exists('.env') || copy('.env.example', '.env');\""
    ],
    "post-create-project-cmd": [
      "php artisan key:generate"
    ]
  }
}
```

**`backend/Dockerfile`**
```dockerfile
FROM php:8.1-fpm
WORKDIR /var/www/html
COPY . .
RUN apt-get update \
  && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip git unzip \
  && docker-php-ext-install pdo_mysql mbstring gd \
  && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install
CMD ["php-fpm"]
EXPOSE 9000
```

---

📂 **Frontend (Next.js)**

**`frontend/package.json`**
```json
{
  "name": "sms-frontend",
  "version": "0.1.0",
  "private": true,
  "scripts": {
    "dev": "next dev",
    "build": "next build",
    "start": "next start",
    "lint": "next lint"
  },
  "dependencies": {
    "next": "13.4.10",
    "react": "18.2.0",
    "react-dom": "18.2.0",
    "axios": "^1.4.0"
  },
  "devDependencies": {
    "typescript": "^5.1.3",
    "tailwindcss": "^3.3.2",
    "postcss": "^8.4.24",
    "autoprefixer": "^10.4.14",
    "eslint": "^8.44.0",
    "eslint-config-next": "13.4.10"
  }
}
```

**`frontend/Dockerfile`**
```dockerfile
FROM node:18-alpine
WORKDIR /usr/src/app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build
EXPOSE 3000
CMD ["npm", "start"]
```

---

✅ Now you have the backend (`composer.json`, `Dockerfile`) and frontend (`package.json`, `Dockerfile`) split into their respective files.

# Step 2 (starter Laravel migrations & seeders) next

<?php
// database/migrations/2023_01_01_000000_create_roles_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->json('permissions')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('roles');
    }
};

// database/migrations/2023_01_01_000001_create_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->foreignId('role_id')->constrained('roles');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('users');
    }
};

// database/migrations/2023_01_01_000002_create_classes_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('level', ['primary', 'junior', 'senior']);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('classes');
    }
};

// database/migrations/2023_01_01_000003_create_sections_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('class_id')->constrained('classes');
            $table->foreignId('teacher_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('sections');
    }
};

// database/migrations/2023_01_01_000004_create_students_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('admission_no')->unique();
            $table->date('dob');
            $table->foreignId('section_id')->nullable()->constrained('sections');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('students');
    }
};

// database/seeders/RolesSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder {
    public function run(): void {
        DB::table('roles')->insert([
            ['name' => 'Admin', 'permissions' => json_encode(['*'])],
            ['name' => 'Teacher', 'permissions' => json_encode(['manage_attendance', 'view_students'])],
            ['name' => 'Student', 'permissions' => json_encode(['view_grades', 'view_attendance'])],
            ['name' => 'Parent', 'permissions' => json_encode(['view_child_records'])],
        ]);
    }
}

// database/seeders/UsersSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder {
    public function run(): void {
        DB::table('users')->insert([
            [
                'name' => 'Super Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role_id' => 1,
            ],
        ]);
    }
}

// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        $this->call([
            RolesSeeder::class,
            UsersSeeder::class,
        ]);
    }
}

# step 3 (boilerplate Next.js app with auth pages and axios client)

// pages/_app.tsx
import '@/styles/globals.css'
import type { AppProps } from 'next/app'

export default function App({ Component, pageProps }: AppProps) {
  return <Component {...pageProps} />
}

// lib/api-client.ts
import axios from 'axios'

const api = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api',
  withCredentials: true,
})

api.interceptors.request.use((config) => {
  if (typeof window !== 'undefined') {
    const token = localStorage.getItem('token')
    if (token) config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

export default api

// pages/login.tsx
import { useState } from 'react'
import { useRouter } from 'next/router'
import api from '@/lib/api-client'

export default function Login() {
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const router = useRouter()

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    try {
      const res = await api.post('/auth/login', { email, password })
      localStorage.setItem('token', res.data.token)
      router.push('/dashboard')
    } catch (err) {
      alert('Login failed')
    }
  }

  return (
    <div className="flex items-center justify-center min-h-screen bg-gray-100">
      <form onSubmit={handleSubmit} className="bg-white p-6 rounded shadow-md w-80">
        <h1 className="text-xl font-bold mb-4">Login</h1>
        <input
          type="email"
          placeholder="Email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          className="w-full p-2 mb-3 border rounded"
        />
        <input
          type="password"
          placeholder="Password"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
          className="w-full p-2 mb-3 border rounded"
        />
        <button
          type="submit"
          className="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700"
        >
          Login
        </button>
      </form>
    </div>
  )
}

// pages/dashboard.tsx
import { useEffect, useState } from 'react'
import { useRouter } from 'next/router'
import api from '@/lib/api-client'

export default function Dashboard() {
  const [user, setUser] = useState<any>(null)
  const router = useRouter()

  useEffect(() => {
    const token = localStorage.getItem('token')
    if (!token) router.push('/login')
    else {
      api.get('/users/me')
        .then((res) => setUser(res.data))
        .catch(() => router.push('/login'))
    }
  }, [router])

  if (!user) return <p>Loading...</p>

  return (
    <div className="p-6">
      <h1 className="text-2xl font-bold">Welcome, {user.name}</h1>
      <p>Role: {user.role.name}</p>
    </div>
  )
}

// styles/globals.css
@tailwind base;
@tailwind components;
@tailwind utilities;

# onnect the backend endpoints (Laravel auth + /users/me) so this frontend can already perform a full login flow?

<?php
// routes/api.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users/me', [UserController::class, 'me']);
});

// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user->load('role'),
        ]);
    }
}

// app/Http/Controllers/UserController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function me(Request $request)
    {
        return $request->user()->load('role');
    }
}

// app/Models/User.php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role_id'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}

// app/Models/Role.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'permissions'];

    protected $casts = [
        'permissions' => 'array'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}

# Role-based dashboards (e.g. Teacher, Student, Parent) so the login redirects users to their specific portal

// pages/dashboard.tsx
import { useEffect, useState } from 'react'
import { useRouter } from 'next/router'
import api from '@/lib/api-client'

export default function Dashboard() {
  const [user, setUser] = useState<any>(null)
  const router = useRouter()

  useEffect(() => {
    const token = localStorage.getItem('token')
    if (!token) router.push('/login')
    else {
      api.get('/users/me')
        .then((res) => {
          setUser(res.data)
          const role = res.data.role.name
          if (role === 'Teacher') router.push('/teacher/dashboard')
          else if (role === 'Student') router.push('/student/dashboard')
          else if (role === 'Parent') router.push('/parent/dashboard')
          else if (role === 'Admin') router.push('/admin/dashboard')
        })
        .catch(() => router.push('/login'))
    }
  }, [router])

  return <p>Loading...</p>
}

// pages/teacher/dashboard.tsx
export default function TeacherDashboard() {
  return (
    <div className="p-6">
      <h1 className="text-2xl font-bold">Teacher Dashboard</h1>
      <p>Manage classes, take attendance, and view students.</p>
    </div>
  )
}

// pages/student/dashboard.tsx
export default function StudentDashboard() {
  return (
    <div className="p-6">
      <h1 className="text-2xl font-bold">Student Dashboard</h1>
      <p>View attendance, grades, and assignments.</p>
    </div>
  )
}

// pages/parent/dashboard.tsx
export default function ParentDashboard() {
  return (
    <div className="p-6">
      <h1 className="text-2xl font-bold">Parent Dashboard</h1>
      <p>Track your child’s progress, attendance, and fees.</p>
    </div>
  )
}

// pages/admin/dashboard.tsx
export default function AdminDashboard() {
  return (
    <div className="p-6">
      <h1 className="text-2xl font-bold">Admin Dashboard</h1>
      <p>Manage users, classes, sections, and reports.</p>
    </div>
  )
}

# Scaffold attendance management (Teacher → mark attendance → Student/Parent/Admin view) as the next feature
<?php
// database/migrations/2023_01_02_000005_create_attendance_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('teacher_id')->constrained('users');
            $table->foreignId('section_id')->constrained('sections');
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'late', 'excused']);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('attendance');
    }
};

// database/migrations/2023_01_02_000006_add_teacher_roles_to_sections.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('sections', function (Blueprint $table) {
            $table->foreignId('form_master_id')->nullable()->constrained('users');
        });

        Schema::create('section_subject_teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('sections');
            $table->foreignId('teacher_id')->constrained('users');
            $table->string('subject');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::table('sections', function (Blueprint $table) {
            $table->dropColumn('form_master_id');
        });
        Schema::dropIfExists('section_subject_teachers');
    }
};

// app/Http/Controllers/AttendanceController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Section;

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'section_id' => 'required|exists:sections,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
        ]);

        $attendance = Attendance::create([
            'student_id' => $data['student_id'],
            'section_id' => $data['section_id'],
            'teacher_id' => $request->user()->id,
            'date' => $data['date'],
            'status' => $data['status'],
        ]);

        return response()->json($attendance, 201);
    }

    public function index(Request $request, $sectionId)
    {
        $section = Section::with(['students.user'])->findOrFail($sectionId);
        return response()->json($section->students);
    }
}

// app/Models/Attendance.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['student_id','teacher_id','section_id','date','status'];

    public function student() { return $this->belongsTo(Student::class); }
    public function teacher() { return $this->belongsTo(User::class, 'teacher_id'); }
    public function section() { return $this->belongsTo(Section::class); }
}

// routes/api.php
use App\Http\Controllers\AttendanceController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/attendance', [AttendanceController::class, 'store']);
    Route::get('/sections/{id}/attendance', [AttendanceController::class, 'index']);
});


# Add the Next.js teacher UI (attendance form: select section, subject, mark students present/absent/late/excused)
// pages/teacher/attendance.tsx
import { useEffect, useState } from 'react'
import api from '@/lib/api-client'

export default function AttendancePage() {
  const [sections, setSections] = useState<any[]>([])
  const [selectedSection, setSelectedSection] = useState<string>('')
  const [students, setStudents] = useState<any[]>([])
  const [attendance, setAttendance] = useState<{[key:string]: string}>({})
  const [date, setDate] = useState<string>('')

  useEffect(() => {
    api.get('/sections')
      .then(res => setSections(res.data))
      .catch(err => console.error(err))
  }, [])

  const loadStudents = (sectionId: string) => {
    setSelectedSection(sectionId)
    api.get(`/sections/${sectionId}/attendance`)
      .then(res => setStudents(res.data))
      .catch(err => console.error(err))
  }

  const markAttendance = (studentId: string, status: string) => {
    setAttendance(prev => ({ ...prev, [studentId]: status }))
  }

  const submitAttendance = async () => {
    if (!date) return alert('Please select a date')
    for (const student of students) {
      await api.post('/attendance', {
        student_id: student.id,
        section_id: selectedSection,
        date,
        status: attendance[student.id] || 'absent'
      })
    }
    alert('Attendance saved!')
  }

  return (
    <div className="p-6">
      <h1 className="text-2xl font-bold mb-4">Take Attendance</h1>

      <div className="mb-4">
        <label className="block mb-1">Date:</label>
        <input
          type="date"
          value={date}
          onChange={(e) => setDate(e.target.value)}
          className="border p-2 rounded"
        />
      </div>

      <div className="mb-4">
        <label className="block mb-1">Select Section:</label>
        <select
          onChange={(e) => loadStudents(e.target.value)}
          value={selectedSection}
          className="border p-2 rounded"
        >
          <option value="">-- Select Section --</option>
          {sections.map(section => (
            <option key={section.id} value={section.id}>
              {section.name}
            </option>
          ))}
        </select>
      </div>

      {students.length > 0 && (
        <table className="min-w-full border">
          <thead>
            <tr>
              <th className="border p-2 text-left">Student</th>
              <th className="border p-2">Present</th>
              <th className="border p-2">Absent</th>
              <th className="border p-2">Late</th>
              <th className="border p-2">Excused</th>
            </tr>
          </thead>
          <tbody>
            {students.map(student => (
              <tr key={student.id}>
                <td className="border p-2">{student.user.name}</td>
                {['present','absent','late','excused'].map(status => (
                  <td key={status} className="border p-2 text-center">
                    <input
                      type="radio"
                      name={`attendance-${student.id}`}
                      onChange={() => markAttendance(student.id, status)}
                      checked={attendance[student.id] === status}
                    />
                  </td>
                ))}
              </tr>
            ))}
          </tbody>
        </table>
      )}

      {students.length > 0 && (
        <button
          onClick={submitAttendance}
          className="mt-4 px-4 py-2 bg-blue-600 text-white rounded"
        >
          Save Attendance
        </button>
      )}
    </div>
  )
}

# Build the Parent & Student attendance view pages so they can track attendance history

// pages/student/attendance.tsx
import { useEffect, useState } from 'react'
import api from '@/lib/api-client'

export default function StudentAttendance() {
  const [records, setRecords] = useState<any[]>([])

  useEffect(() => {
    api.get('/me/attendance')
      .then(res => setRecords(res.data))
      .catch(err => console.error(err))
  }, [])

  return (
    <div className="p-6">
      <h1 className="text-2xl font-bold mb-4">My Attendance</h1>
      <table className="min-w-full border">
        <thead>
          <tr>
            <th className="border p-2">Date</th>
            <th className="border p-2">Status</th>
          </tr>
        </thead>
        <tbody>
          {records.map((rec, i) => (
            <tr key={i}>
              <td className="border p-2">{rec.date}</td>
              <td className="border p-2 capitalize">{rec.status}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  )
}

// pages/parent/attendance.tsx
import { useEffect, useState } from 'react'
import api from '@/lib/api-client'

export default function ParentAttendance() {
  const [children, setChildren] = useState<any[]>([])

  useEffect(() => {
    api.get('/parent/children/attendance')
      .then(res => setChildren(res.data))
      .catch(err => console.error(err))
  }, [])

  return (
    <div className="p-6">
      <h1 className="text-2xl font-bold mb-4">Children Attendance Records</h1>
      {children.map(child => (
        <div key={child.id} className="mb-6">
          <h2 className="text-xl font-semibold mb-2">{child.user.name}</h2>
          <table className="min-w-full border">
            <thead>
              <tr>
                <th className="border p-2">Date</th>
                <th className="border p-2">Status</th>
              </tr>
            </thead>
            <tbody>
              {child.attendance.map((rec: any, i: number) => (
                <tr key={i}>
                  <td className="border p-2">{rec.date}</td>
                  <td className="border p-2 capitalize">{rec.status}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      ))}
    </div>
  )
}

# create an Admin dashboard attendance report (filter by section/date range, export to CSV/PDF)
// pages/admin/attendance-report.tsx
import { useEffect, useState } from 'react'
import api from '@/lib/api-client'

export default function AttendanceReport() {
  const [sections, setSections] = useState<any[]>([])
  const [selectedSection, setSelectedSection] = useState<string>('')
  const [fromDate, setFromDate] = useState<string>('')
  const [toDate, setToDate] = useState<string>('')
  const [records, setRecords] = useState<any[]>([])

  useEffect(() => {
    api.get('/sections')
      .then(res => setSections(res.data))
      .catch(err => console.error(err))
  }, [])

  const loadReport = () => {
    if (!selectedSection || !fromDate || !toDate) return alert('Please select all filters')
    api.get(`/admin/attendance-report`, {
      params: { section_id: selectedSection, from: fromDate, to: toDate }
    })
      .then(res => setRecords(res.data))
      .catch(err => console.error(err))
  }

  const exportCSV = () => {
    let csv = 'Student,Date,Status\n'
    records.forEach(r => {
      csv += `${r.student.user.name},${r.date},${r.status}\n`
    })
    const blob = new Blob([csv], { type: 'text/csv' })
    const url = window.URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = 'attendance_report.csv'
    a.click()
  }

  return (
    <div className="p-6">
      <h1 className="text-2xl font-bold mb-4">Attendance Report</h1>

      <div className="flex space-x-4 mb-4">
        <select
          value={selectedSection}
          onChange={(e) => setSelectedSection(e.target.value)}
          className="border p-2 rounded"
        >
          <option value="">-- Select Section --</option>
          {sections.map(sec => (
            <option key={sec.id} value={sec.id}>{sec.name}</option>
          ))}
        </select>

        <input type="date" value={fromDate} onChange={(e) => setFromDate(e.target.value)} className="border p-2 rounded" />
        <input type="date" value={toDate} onChange={(e) => setToDate(e.target.value)} className="border p-2 rounded" />

        <button onClick={loadReport} className="px-4 py-2 bg-blue-600 text-white rounded">Load</button>
        <button onClick={exportCSV} className="px-4 py-2 bg-green-600 text-white rounded">Export CSV</button>
      </div>

      {records.length > 0 && (
        <table className="min-w-full border">
          <thead>
            <tr>
              <th className="border p-2">Student</th>
              <th className="border p-2">Date</th>
              <th className="border p-2">Status</th>
            </tr>
          </thead>
          <tbody>
            {records.map((r, i) => (
              <tr key={i}>
                <td className="border p-2">{r.student.user.name}</td>
                <td className="border p-2">{r.date}</td>
                <td className="border p-2 capitalize">{r.status}</td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </div>
  )
}

# add PDF export (with Laravel backend generating the PDF)
// pages/admin/attendance-report.tsx
import { useEffect, useState } from 'react'
import api from '@/lib/api-client'

export default function AttendanceReport() {
  const [sections, setSections] = useState<any[]>([])
  const [selectedSection, setSelectedSection] = useState<string>('')
  const [fromDate, setFromDate] = useState<string>('')
  const [toDate, setToDate] = useState<string>('')
  const [records, setRecords] = useState<any[]>([])

  useEffect(() => {
    api.get('/sections')
      .then(res => setSections(res.data))
      .catch(err => console.error(err))
  }, [])

  const loadReport = () => {
    if (!selectedSection || !fromDate || !toDate) return alert('Please select all filters')
    api.get(`/admin/attendance-report`, {
      params: { section_id: selectedSection, from: fromDate, to: toDate }
    })
      .then(res => setRecords(res.data))
      .catch(err => console.error(err))
  }

  const exportCSV = () => {
    let csv = 'Student,Date,Status\n'
    records.forEach(r => {
      csv += `${r.student.user.name},${r.date},${r.status}\n`
    })
    const blob = new Blob([csv], { type: 'text/csv' })
    const url = window.URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = 'attendance_report.csv'
    a.click()
  }

  const exportPDF = () => {
    if (!selectedSection || !fromDate || !toDate) return alert('Please select all filters')
    api.get(`/admin/attendance-report/pdf`, {
      params: { section_id: selectedSection, from: fromDate, to: toDate },
      responseType: 'blob'
    })
      .then(res => {
        const url = window.URL.createObjectURL(new Blob([res.data], { type: 'application/pdf' }))
        const a = document.createElement('a')
        a.href = url
        a.download = 'attendance_report.pdf'
        a.click()
      })
      .catch(err => console.error(err))
  }

  return (
    <div className="p-6">
      <h1 className="text-2xl font-bold mb-4">Attendance Report</h1>

      <div className="flex space-x-4 mb-4">
        <select
          value={selectedSection}
          onChange={(e) => setSelectedSection(e.target.value)}
          className="border p-2 rounded"
        >
          <option value="">-- Select Section --</option>
          {sections.map(sec => (
            <option key={sec.id} value={sec.id}>{sec.name}</option>
          ))}
        </select>

        <input type="date" value={fromDate} onChange={(e) => setFromDate(e.target.value)} className="border p-2 rounded" />
        <input type="date" value={toDate} onChange={(e) => setToDate(e.target.value)} className="border p-2 rounded" />

        <button onClick={loadReport} className="px-4 py-2 bg-blue-600 text-white rounded">Load</button>
        <button onClick={exportCSV} className="px-4 py-2 bg-green-600 text-white rounded">Export CSV</button>
        <button onClick={exportPDF} className="px-4 py-2 bg-red-600 text-white rounded">Export PDF</button>
      </div>

      {records.length > 0 && (
        <table className="min-w-full border">
          <thead>
            <tr>
              <th className="border p-2">Student</th>
              <th className="border p-2">Date</th>
              <th className="border p-2">Status</th>
            </tr>
          </thead>
          <tbody>
            {records.map((r, i) => (
              <tr key={i}>
                <td className="border p-2">{r.student.user.name}</td>
                <td className="border p-2">{r.date}</td>
                <td className="border p-2 capitalize">{r.status}</td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </div>
  )
}

# add charts/graphs (e.g. attendance percentage per section or student) to the Admin dashboard for quick insights
// pages/admin/attendance-report.tsx
import { useEffect, useState } from 'react'
import api from '@/lib/api-client'
import { PieChart, Pie, Cell, Tooltip, Legend, ResponsiveContainer } from 'recharts'

export default function AttendanceReport() {
  const [sections, setSections] = useState<any[]>([])
  const [selectedSection, setSelectedSection] = useState<string>('')
  const [fromDate, setFromDate] = useState<string>('')
  const [toDate, setToDate] = useState<string>('')
  const [records, setRecords] = useState<any[]>([])
  const [summary, setSummary] = useState<any[]>([])

  useEffect(() => {
    api.get('/sections')
      .then(res => setSections(res.data))
      .catch(err => console.error(err))
  }, [])

  const loadReport = () => {
    if (!selectedSection || !fromDate || !toDate) return alert('Please select all filters')
    api.get(`/admin/attendance-report`, {
      params: { section_id: selectedSection, from: fromDate, to: toDate }
    })
      .then(res => {
        setRecords(res.data)
        generateSummary(res.data)
      })
      .catch(err => console.error(err))
  }

  const exportCSV = () => {
    let csv = 'Student,Date,Status\n'
    records.forEach(r => {
      csv += `${r.student.user.name},${r.date},${r.status}\n`
    })
    const blob = new Blob([csv], { type: 'text/csv' })
    const url = window.URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = 'attendance_report.csv'
    a.click()
  }

  const generateSummary = (data: any[]) => {
    const counts: any = { Present: 0, Absent: 0, Late: 0 }
    data.forEach(r => {
      const status = r.status.charAt(0).toUpperCase() + r.status.slice(1)
      if (counts[status] !== undefined) counts[status] += 1
    })
    const summaryData = Object.keys(counts).map(k => ({ name: k, value: counts[k] }))
    setSummary(summaryData)
  }

  const COLORS = ['#4CAF50', '#F44336', '#FF9800']

  return (
    <div className="p-6">
      <h1 className="text-2xl font-bold mb-4">Attendance Report</h1>

      <div className="flex space-x-4 mb-4">
        <select
          value={selectedSection}
          onChange={(e) => setSelectedSection(e.target.value)}
          className="border p-2 rounded"
        >
          <option value="">-- Select Section --</option>
          {sections.map(sec => (
            <option key={sec.id} value={sec.id}>{sec.name}</option>
          ))}
        </select>

        <input type="date" value={fromDate} onChange={(e) => setFromDate(e.target.value)} className="border p-2 rounded" />
        <input type="date" value={toDate} onChange={(e) => setToDate(e.target.value)} className="border p-2 rounded" />

        <button onClick={loadReport} className="px-4 py-2 bg-blue-600 text-white rounded">Load</button>
        <button onClick={exportCSV} className="px-4 py-2 bg-green-600 text-white rounded">Export CSV</button>
      </div>

      {summary.length > 0 && (
        <div className="mb-6 w-full h-64">
          <h2 className="text-xl font-semibold mb-2">Attendance Summary</h2>
          <ResponsiveContainer>
            <PieChart>
              <Pie
                data={summary}
                dataKey="value"
                nameKey="name"
                cx="50%"
                cy="50%"
                outerRadius={80}
                label
              >
                {summary.map((entry, index) => (
                  <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]} />
                ))}
              </Pie>
              <Tooltip />
              <Legend />
            </PieChart>
          </ResponsiveContainer>
        </div>
      )}

      {records.length > 0 && (
        <table className="min-w-full border">
          <thead>
            <tr>
              <th className="border p-2">Student</th>
              <th className="border p-2">Date</th>
              <th className="border p-2">Status</th>
            </tr>
          </thead>
          <tbody>
            {records.map((r, i) => (
              <tr key={i}>
                <td className="border p-2">{r.student.user.name}</td>
                <td className="border p-2">{r.date}</td>
                <td className="border p-2 capitalize">{r.status}</td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </div>
  )
}

# add an overall section average attendance % at the top of the report for quick comparison between sections
// pages/admin/attendance-report.tsx
import { useEffect, useState } from 'react'
import api from '@/lib/api-client'

export default function AttendanceReport() {
  const [sections, setSections] = useState<any[]>([])
  const [selectedSection, setSelectedSection] = useState<string>('')
  const [fromDate, setFromDate] = useState<string>('')
  const [toDate, setToDate] = useState<string>('')
  const [records, setRecords] = useState<any[]>([])
  const [sectionAverage, setSectionAverage] = useState<number | null>(null)

  useEffect(() => {
    api.get('/sections')
      .then(res => setSections(res.data))
      .catch(err => console.error(err))
  }, [])

  const loadReport = () => {
    if (!selectedSection || !fromDate || !toDate) return alert('Please select all filters')
    api.get(`/admin/attendance-report`, {
      params: { section_id: selectedSection, from: fromDate, to: toDate }
    })
      .then(res => {
        setRecords(res.data)
        calculateAverage(res.data)
      })
      .catch(err => console.error(err))
  }

  const exportCSV = () => {
    let csv = 'Student,Date,Status\n'
    records.forEach(r => {
      csv += `${r.student.user.name},${r.date},${r.status}\n`
    })
    const blob = new Blob([csv], { type: 'text/csv' })
    const url = window.URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = 'attendance_report.csv'
    a.click()
  }

  const calculateAverage = (data: any[]) => {
    if (data.length === 0) return setSectionAverage(null)
    let presentCount = 0
    data.forEach(r => {
      if (r.status.toLowerCase() === 'present') presentCount++
    })
    const avg = (presentCount / data.length) * 100
    setSectionAverage(Number(avg.toFixed(1)))
  }

  return (
    <div className="p-6">
      <h1 className="text-2xl font-bold mb-4">Attendance Report</h1>

      <div className="flex space-x-4 mb-4">
        <select
          value={selectedSection}
          onChange={(e) => setSelectedSection(e.target.value)}
          className="border p-2 rounded"
        >
          <option value="">-- Select Section --</option>
          {sections.map(sec => (
            <option key={sec.id} value={sec.id}>{sec.name}</option>
          ))}
        </select>

        <input type="date" value={fromDate} onChange={(e) => setFromDate(e.target.value)} className="border p-2 rounded" />
        <input type="date" value={toDate} onChange={(e) => setToDate(e.target.value)} className="border p-2 rounded" />

        <button onClick={loadReport} className="px-4 py-2 bg-blue-600 text-white rounded">Load</button>
        <button onClick={exportCSV} className="px-4 py-2 bg-green-600 text-white rounded">Export CSV</button>
      </div>

      {sectionAverage !== null && (
        <div className="mb-4 p-3 bg-gray-100 rounded">
          <h2 className="text-lg font-semibold">Section Average Attendance: {sectionAverage}%</h2>
        </div>
      )}

      {records.length > 0 && (
        <table className="min-w-full border">
          <thead>
            <tr>
              <th className="border p-2">Student</th>
              <th className="border p-2">Date</th>
              <th className="border p-2">Status</th>
            </tr>
          </thead>
          <tbody>
            {records.map((r, i) => (
              <tr key={i}>
                <td className="border p-2">{r.student.user.name}</td>
                <td className="border p-2">{r.date}</td>
                <td className="border p-2 capitalize">{r.status}</td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </div>
  )
}

# add a multi-section comparison chart (bar/line chart showing average attendance % across all sections)

// pages/admin/attendance-report.tsx
import { useEffect, useState } from 'react'
import api from '@/lib/api-client'
import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, LineChart, Line, Legend } from 'recharts'

export default function AttendanceReport() {
  const [sections, setSections] = useState<any[]>([])
  const [selectedSection, setSelectedSection] = useState<string>('')
  const [fromDate, setFromDate] = useState<string>('')
  const [toDate, setToDate] = useState<string>('')
  const [records, setRecords] = useState<any[]>([])
  const [sectionAverages, setSectionAverages] = useState<any[]>([])
  const [attendanceTrends, setAttendanceTrends] = useState<any[]>([])

  useEffect(() => {
    api.get('/sections')
      .then(res => setSections(res.data))
      .catch(err => console.error(err))
  }, [])

  const loadReport = () => {
    if (!selectedSection || !fromDate || !toDate) return alert('Please select all filters')
    api.get(`/admin/attendance-report`, {
      params: { section_id: selectedSection, from: fromDate, to: toDate }
    })
      .then(res => setRecords(res.data))
      .catch(err => console.error(err))

    // Load section averages for comparison
    api.get(`/admin/attendance-section-averages`, {
      params: { from: fromDate, to: toDate }
    })
      .then(res => setSectionAverages(res.data))
      .catch(err => console.error(err))

    // Load attendance trends over time
    api.get(`/admin/attendance-trends`, {
      params: { section_id: selectedSection, from: fromDate, to: toDate }
    })
      .then(res => setAttendanceTrends(res.data))
      .catch(err => console.error(err))
  }

  const exportCSV = () => {
    let csv = 'Student,Date,Status\n'
    records.forEach(r => {
      csv += `${r.student.user.name},${r.date},${r.status}\n`
    })
    const blob = new Blob([csv], { type: 'text/csv' })
    const url = window.URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = 'attendance_report.csv'
    a.click()
  }

  const calculateAverage = () => {
    if (records.length === 0) return null
    let total = records.length
    let present = records.filter(r => r.status === 'present').length
    return ((present / total) * 100).toFixed(2)
  }

  return (
    <div className="p-6">
      <h1 className="text-2xl font-bold mb-4">Attendance Report</h1>

      <div className="flex space-x-4 mb-4">
        <select
          value={selectedSection}
          onChange={(e) => setSelectedSection(e.target.value)}
          className="border p-2 rounded"
        >
          <option value="">-- Select Section --</option>
          {sections.map(sec => (
            <option key={sec.id} value={sec.id}>{sec.name}</option>
          ))}
        </select>

        <input type="date" value={fromDate} onChange={(e) => setFromDate(e.target.value)} className="border p-2 rounded" />
        <input type="date" value={toDate} onChange={(e) => setToDate(e.target.value)} className="border p-2 rounded" />

        <button onClick={loadReport} className="px-4 py-2 bg-blue-600 text-white rounded">Load</button>
        <button onClick={exportCSV} className="px-4 py-2 bg-green-600 text-white rounded">Export CSV</button>
      </div>

      {records.length > 0 && (
        <>
          <div className="mb-4">
            <h2 className="text-lg font-semibold">Section Average Attendance: {calculateAverage()}%</h2>
          </div>

          <table className="min-w-full border mb-6">
            <thead>
              <tr>
                <th className="border p-2">Student</th>
                <th className="border p-2">Date</th>
                <th className="border p-2">Status</th>
              </tr>
            </thead>
            <tbody>
              {records.map((r, i) => (
                <tr key={i}>
                  <td className="border p-2">{r.student.user.name}</td>
                  <td className="border p-2">{r.date}</td>
                  <td className="border p-2 capitalize">{r.status}</td>
                </tr>
              ))}
            </tbody>
          </table>

          {sectionAverages.length > 0 && (
            <div className="h-80 mb-6">
              <h2 className="text-lg font-semibold mb-2">Multi-Section Comparison</h2>
              <ResponsiveContainer width="100%" height="100%">
                <BarChart data={sectionAverages}>
                  <CartesianGrid strokeDasharray="3 3" />
                  <XAxis dataKey="section" />
                  <YAxis />
                  <Tooltip />
                  <Bar dataKey="average" fill="#3b82f6" />
                </BarChart>
              </ResponsiveContainer>
            </div>
          )}

          {attendanceTrends.length > 0 && (
            <div className="h-80">
              <h2 className="text-lg font-semibold mb-2">Attendance Trends Over Time</h2>
              <ResponsiveContainer width="100%" height="100%">
                <LineChart data={attendanceTrends}>
                  <CartesianGrid strokeDasharray="3 3" />
                  <XAxis dataKey="date" />
                  <YAxis />
                  <Tooltip />
                  <Legend />
                  <Line type="monotone" dataKey="present" stroke="#10b981" name="Present" />
                  <Line type="monotone" dataKey="absent" stroke="#ef4444" name="Absent" />
                  <Line type="monotone" dataKey="late" stroke="#f59e0b" name="Late" />
                </LineChart>
              </ResponsiveContainer>
            </div>
          )}
        </>
      )}
    </div>
  )
}

# include a trend line chart over time (e.g., weekly/monthly attendance trends)
// pages/admin/attendance-report.tsx
import { useEffect, useState } from 'react'
import api from '@/lib/api-client'
import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, LineChart, Line, Legend } from 'recharts'

export default function AttendanceReport() {
  const [sections, setSections] = useState<any[]>([])
  const [selectedSection, setSelectedSection] = useState<string>('')
  const [fromDate, setFromDate] = useState<string>('')
  const [toDate, setToDate] = useState<string>('')
  const [records, setRecords] = useState<any[]>([])
  const [sectionAverages, setSectionAverages] = useState<any[]>([])
  const [attendanceTrends, setAttendanceTrends] = useState<any[]>([])

  useEffect(() => {
    api.get('/sections')
      .then(res => setSections(res.data))
      .catch(err => console.error(err))
  }, [])

  const loadReport = () => {
    if (!selectedSection || !fromDate || !toDate) return alert('Please select all filters')
    api.get(`/admin/attendance-report`, {
      params: { section_id: selectedSection, from: fromDate, to: toDate }
    })
      .then(res => setRecords(res.data))
      .catch(err => console.error(err))

    // Load section averages for comparison
    api.get(`/admin/attendance-section-averages`, {
      params: { from: fromDate, to: toDate }
    })
      .then(res => setSectionAverages(res.data))
      .catch(err => console.error(err))

    // Load attendance trends over time
    api.get(`/admin/attendance-trends`, {
      params: { section_id: selectedSection, from: fromDate, to: toDate }
    })
      .then(res => setAttendanceTrends(res.data))
      .catch(err => console.error(err))
  }

  const exportCSV = () => {
    let csv = 'Student,Date,Status\n'
    records.forEach(r => {
      csv += `${r.student.user.name},${r.date},${r.status}\n`
    })
    const blob = new Blob([csv], { type: 'text/csv' })
    const url = window.URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = 'attendance_report.csv'
    a.click()
  }

  const calculateAverage = () => {
    if (records.length === 0) return null
    let total = records.length
    let present = records.filter(r => r.status === 'present').length
    return ((present / total) * 100).toFixed(2)
  }

  return (
    <div className="p-6">
      <h1 className="text-2xl font-bold mb-4">Attendance Report</h1>

      <div className="flex space-x-4 mb-4">
        <select
          value={selectedSection}
          onChange={(e) => setSelectedSection(e.target.value)}
          className="border p-2 rounded"
        >
          <option value="">-- Select Section --</option>
          {sections.map(sec => (
            <option key={sec.id} value={sec.id}>{sec.name}</option>
          ))}
        </select>

        <input type="date" value={fromDate} onChange={(e) => setFromDate(e.target.value)} className="border p-2 rounded" />
        <input type="date" value={toDate} onChange={(e) => setToDate(e.target.value)} className="border p-2 rounded" />

        <button onClick={loadReport} className="px-4 py-2 bg-blue-600 text-white rounded">Load</button>
        <button onClick={exportCSV} className="px-4 py-2 bg-green-600 text-white rounded">Export CSV</button>
      </div>

      {records.length > 0 && (
        <>
          <div className="mb-4">
            <h2 className="text-lg font-semibold">Section Average Attendance: {calculateAverage()}%</h2>
          </div>

          <table className="min-w-full border mb-6">
            <thead>
              <tr>
                <th className="border p-2">Student</th>
                <th className="border p-2">Date</th>
                <th className="border p-2">Status</th>
              </tr>
            </thead>
            <tbody>
              {records.map((r, i) => (
                <tr key={i}>
                  <td className="border p-2">{r.student.user.name}</td>
                  <td className="border p-2">{r.date}</td>
                  <td className="border p-2 capitalize">{r.status}</td>
                </tr>
              ))}
            </tbody>
          </table>

          {sectionAverages.length > 0 && (
            <div className="h-80 mb-6">
              <h2 className="text-lg font-semibold mb-2">Multi-Section Comparison</h2>
              <ResponsiveContainer width="100%" height="100%">
                <BarChart data={sectionAverages}>
                  <CartesianGrid strokeDasharray="3 3" />
                  <XAxis dataKey="section" />
                  <YAxis />
                  <Tooltip />
                  <Bar dataKey="average" fill="#3b82f6" />
                </BarChart>
              </ResponsiveContainer>
            </div>
          )}

          {attendanceTrends.length > 0 && (
            <div className="h-80">
              <h2 className="text-lg font-semibold mb-2">Attendance Trends Over Time</h2>
              <ResponsiveContainer width="100%" height="100%">
                <LineChart data={attendanceTrends}>
                  <CartesianGrid strokeDasharray="3 3" />
                  <XAxis dataKey="date" />
                  <YAxis />
                  <Tooltip />
                  <Legend />
                  <Line type="monotone" dataKey="present" stroke="#10b981" name="Present" />
                  <Line type="monotone" dataKey="absent" stroke="#ef4444" name="Absent" />
                  <Line type="monotone" dataKey="late" stroke="#f59e0b" name="Late" />
                </LineChart>
              </ResponsiveContainer>
            </div>
          )}
        </>
      )}
    </div>
  )
}

# Add the ability to toggle between daily, weekly, and monthly views for the trend chart
// NOTE: Fixed duplicate identifier issue by renaming the exported axios instance to `apiClient`.
// Also updated all imports to use `apiClient` to avoid accidental redeclaration of `api`.
// Added simple test stubs and jest config to help catch regressions.

// pages/_app.tsx
import '@/styles/globals.css'
import type { AppProps } from 'next/app'

export default function App({ Component, pageProps }: AppProps) {
  return <Component {...pageProps} />
}

// lib/api-client.ts
import axios from 'axios'

// Use a specific name to avoid collisions with variables named `api` elsewhere.
const apiClient = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api',
  withCredentials: true,
})

// Keep the interceptor light and defensive about SSR (only run in browser).
apiClient.interceptors.request.use((config: any) => {
  if (typeof window !== 'undefined') {
    const token = localStorage.getItem('token')
    if (token) {
      config.headers = config.headers || {}
      config.headers.Authorization = `Bearer ${token}`
    }
  }
  return config
})

export default apiClient

// pages/login.tsx
import React, { useState } from 'react'
import { useRouter } from 'next/router'
import apiClient from '@/lib/api-client'

export default function Login() {
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const router = useRouter()

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    try {
      const res = await apiClient.post('/auth/login', { email, password })
      // Persist token and navigate to dashboard
      if (typeof window !== 'undefined') {
        localStorage.setItem('token', res.data.token)
      }
      router.push('/dashboard')
    } catch (err) {
      // In production surface a friendly message; for dev keep it simple
      alert('Login failed — check credentials or backend connectivity')
    }
  }

  return (
    <div className="flex items-center justify-center min-h-screen bg-gray-100">
      <form onSubmit={handleSubmit} className="bg-white p-6 rounded shadow-md w-80">
        <h1 className="text-xl font-bold mb-4">Login</h1>
        <input
          type="email"
          placeholder="Email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          className="w-full p-2 mb-3 border rounded"
          required
        />
        <input
          type="password"
          placeholder="Password"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
          className="w-full p-2 mb-3 border rounded"
          required
        />
        <button
          type="submit"
          className="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700"
        >
          Login
        </button>
      </form>
    </div>
  )
}

// pages/dashboard.tsx
import React, { useEffect, useState } from 'react'
import { useRouter } from 'next/router'
import apiClient from '@/lib/api-client'

export default function Dashboard() {
  const [user, setUser] = useState<any>(null)
  const router = useRouter()

  useEffect(() => {
    // Access localStorage only in browser
    if (typeof window === 'undefined') return

    const token = localStorage.getItem('token')
    if (!token) {
      router.push('/login')
      return
    }

    apiClient
      .get('/users/me')
      .then((res) => setUser(res.data))
      .catch(() => router.push('/login'))
  }, [router])

  if (!user) return <p>Loading...</p>

  return (
    <div className="p-6">
      <h1 className="text-2xl font-bold">Welcome, {user.name}</h1>
      <p>Role: {user.role?.name ?? '—'}</p>
    </div>
  )
}

// styles/globals.css
@tailwind base;
@tailwind components;
@tailwind utilities;

// -----------------------------
// Tests (simple stubs)
// Place these files under __tests__ or your test directory. They require Jest + @testing-library/react setup.
// -----------------------------

// jest.config.js
module.exports = {
  preset: 'ts-jest',
  testEnvironment: 'jsdom',
  setupFilesAfterEnv: ['./jest.setup.js'],
  moduleNameMapper: {
    '^@/(.*)$': '<rootDir>/$1',
  },
}

// jest.setup.js
import '@testing-library/jest-dom'

// __tests__/login.test.tsx
import React from 'react'
import { render, screen } from '@testing-library/react'
import Login from '../pages/login'

// mock next/router to avoid runtime errors inside tests
jest.mock('next/router', () => ({
  useRouter: () => ({ push: jest.fn() }),
}))

test('renders login form with email, password and button', () => {
  render(<Login />)
  expect(screen.getByPlaceholderText(/email/i)).toBeInTheDocument()
  expect(screen.getByPlaceholderText(/password/i)).toBeInTheDocument()
  expect(screen.getByRole('button', { name: /login/i })).toBeInTheDocument()
})

// __tests__/dashboard.test.tsx
import React from 'react'
import { render, screen } from '@testing-library/react'
import Dashboard from '../pages/dashboard'

jest.mock('next/router', () => ({
  useRouter: () => ({ push: jest.fn() }),
}))

// mock api-client to return a known user
jest.mock('../lib/api-client', () => ({
  default: {
    get: () => Promise.resolve({ data: { name: 'Test User', role: { name: 'Teacher' } } }),
  },
}))

test('dashboard loads and displays user name', async () => {
  render(<Dashboard />)
  // initially shows loading
  expect(screen.getByText(/loading/i)).toBeInTheDocument()
  // wait for next tick to allow effect to resolve
  await new Promise((r) => setTimeout(r, 0))
  expect(screen.getByText(/welcome, test user/i)).toBeInTheDocument()
})

/*
  NOTES on the fix:
  - The root cause was a naming collision: some environments (or when files are concatenated accidentally)
    will throw a "Identifier 'api' has already been declared" if the same identifier is declared multiple times
    in the global or module scope. Renaming the axios instance to `apiClient` reduces the chance of such a collision
    and makes the intent explicit.

  - If you still see the error after this change, search your codebase for other declarations named `api` and
    either rename them or import the shared client from `lib/api-client` so there's a single source of truth.

  - If you prefer a named export instead of default, we can change the client to `export const apiClient` and
    update imports accordingly.

  Next steps I can take for you (choose one or tell me something else):
  1) Rename to a named export (`export const apiClient`) across the project.
  2) Add a simple lint rule (ESLint) to prevent duplicate declarations named `api` in the same scope.
  3) Wire up the test scripts in package.json and run tests here (I will provide commands and expected outputs).
*/
