<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentsController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Student::class);
        return Student::with('user','section')->get();
    }
}
