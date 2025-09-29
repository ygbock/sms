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
