<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

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
        // return students in a section with user relation
        $section = Section::with(['students.user'])->findOrFail($sectionId);
        return response()->json($section->students);
    }

    // admin report
    public function attendanceReport(Request $request)
    {
        $params = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'from' => 'required|date',
            'to' => 'required|date',
        ]);

        $records = Attendance::with(['student.user'])
            ->where('section_id', $params['section_id'])
            ->whereBetween('date', [$params['from'], $params['to']])
            ->orderBy('date')
            ->get();

        return response()->json($records);
    }

    public function attendanceSectionAverages(Request $request)
    {
        $params = $request->validate(['from' => 'required|date', 'to' => 'required|date']);
        $from = $params['from'];
        $to = $params['to'];

        $averages = DB::table('attendance')
            ->join('sections', 'attendance.section_id', '=', 'sections.id')
            ->select('sections.id as section', 'sections.name as section_name', DB::raw("ROUND(100.0 * SUM(CASE WHEN attendance.status = 'present' THEN 1 ELSE 0 END) / COUNT(*),2) as average"))
            ->whereBetween('attendance.date', [$from, $to])
            ->groupBy('sections.id', 'sections.name')
            ->get();

        return response()->json($averages);
    }

    public function attendanceTrends(Request $request)
    {
        $params = $request->validate(['section_id' => 'required|exists:sections,id', 'from' => 'required|date', 'to' => 'required|date']);
        $section = $params['section_id'];
        $from = $params['from'];
        $to = $params['to'];

        $trends = DB::table('attendance')
            ->select('date', DB::raw("SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present"), DB::raw("SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent"), DB::raw("SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late"))
            ->where('section_id', $section)
            ->whereBetween('date', [$from, $to])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json($trends);
    }

    // student view: my attendance
    public function myAttendance(Request $request)
    {
        // find student by user
        $student = Student::where('user_id', $request->user()->id)->first();
        if (!$student) return response()->json([], 200);

        $records = Attendance::where('student_id', $student->id)->orderBy('date', 'desc')->get();
        return response()->json($records);
    }

    // parent view: children attendance (simple placeholder)
    public function parentChildrenAttendance(Request $request)
    {
        // This assumes a guardian-child relation not yet implemented; return empty
        return response()->json([]);
    }
}
