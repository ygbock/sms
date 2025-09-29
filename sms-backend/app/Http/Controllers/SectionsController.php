<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;

class SectionsController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Section::class);
        return Section::with('class','teacher')->get();
    }
}
