<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolClass;

class ClassesController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', SchoolClass::class);
        return SchoolClass::with('sections')->get();
    }
}
