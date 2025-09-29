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
        $this->authorize('create', Attendance::class);

        // Support bulk submission: either a single record (student_id + status)
        // or an array of records under `records`.
        $payload = $request->all();

        // Normalize input
        if (isset($payload['records']) && is_array($payload['records'])) {
            $records = $payload['records'];
            $sectionId = $payload['section_id'] ?? null;
            $date = $payload['date'] ?? null;
        } else {
            $records = [[
                'student_id' => $payload['student_id'] ?? null,
                'status' => $payload['status'] ?? null,
                'section_id' => $payload['section_id'] ?? null,
            ]];
            $date = $payload['date'] ?? null;
            $sectionId = $payload['section_id'] ?? null;
        }

        if (!$sectionId) {
            return response()->json(['message' => 'section_id is required'], 422);
        }
        if (!$date) {
            return response()->json(['message' => 'date is required'], 422);
        }

        // Ensure teacher is allowed to mark this section (teachers only for their sections)
        $user = $request->user();
        $role = optional($user->role)->name;
        if ($role === 'Teacher') {
            $section = Section::find($sectionId);
            if (!$section || $section->teacher_id !== $user->id) {
                return response()->json(['message' => 'Forbidden: not your section'], 403);
            }
        }

        $created = [];
        DB::beginTransaction();
        try {
            foreach ($records as $rec) {
                $studentId = $rec['student_id'] ?? null;
                $status = $rec['status'] ?? null;

                if (!$studentId || !$status) continue;

                $student = Student::find($studentId);
                if (!$student) continue;

                // Ensure student belongs to the section
                if ($student->section_id != $sectionId) {
                    // skip or fail — we choose to skip invalid student entries
                    continue;
                }

                // Upsert attendance for the date/student to avoid duplicates
                $att = Attendance::updateOrCreate(
                    ['student_id' => $studentId, 'date' => $date],
                    [
                        'section_id' => $sectionId,
                        'teacher_id' => $user->id,
                        'status' => $status,
                    ]
                );
                $created[] = $att;
            }
            DB::commit();
        } catch (
            Exception $e
        ) {
            DB::rollBack();
            return response()->json(['message' => 'Error saving attendance', 'error' => $e->getMessage()], 500);
        }

        return response()->json($created, 201);
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
        $this->authorize('viewReport', Attendance::class);
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
        $this->authorize('viewReport', Attendance::class);
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
        $this->authorize('viewReport', Attendance::class);
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
        $this->authorize('viewOwn', Attendance::class);
        // find student by user
        $student = Student::where('user_id', $request->user()->id)->first();
        if (!$student) return response()->json([], 200);

        $records = Attendance::where('student_id', $student->id)->orderBy('date', 'desc')->get();
        return response()->json($records);
    }

    // parent view: children attendance (simple placeholder)
    public function parentChildrenAttendance(Request $request)
    {
        $user = $request->user();
        $role = optional($user->role)->name;
        if ($role !== 'Parent' && $role !== 'Guardian') {
            return response()->json([], 200);
        }

        // return only students this guardian is linked to
        $students = $user->children()->with(['user', 'attendance'])->get();
        return response()->json($students);
    }
}
