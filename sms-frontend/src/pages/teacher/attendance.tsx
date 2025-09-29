import { useEffect, useState } from 'react'
import api from '@/lib/api-client'

export default function AttendancePage() {
  const [sections, setSections] = useState<any[]>([])
  const [selectedSection, setSelectedSection] = useState<string>('')
  const [students, setStudents] = useState<any[]>([])
  const [attendance, setAttendance] = useState<{[key:string]: string}>({})
  const [date, setDate] = useState<string>('')
  const [saving, setSaving] = useState<boolean>(false)
  const [savedMap, setSavedMap] = useState<{[key:string]: string}>({})
  const [rowErrorMap, setRowErrorMap] = useState<{[key:string]: string}>({})
  const [submitError, setSubmitError] = useState<string | null>(null)

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
    // clear per-row error when user changes input
    setRowErrorMap(prev => {
      const copy = { ...prev }
      delete copy[studentId]
      return copy
    })
  }

  const undoAttendance = (studentId: string) => {
    setAttendance(prev => {
      const copy = { ...prev }
      delete copy[studentId]
      return copy
    })
    setRowErrorMap(prev => {
      const copy = { ...prev }
      delete copy[studentId]
      return copy
    })
    setSavedMap(prev => {
      const copy = { ...prev }
      delete copy[studentId]
      return copy
    })
  }

  const retryRow = async (studentId: string) => {
    // attempt to save a single student's attendance record
    const status = attendance[studentId] || 'absent'
    if (!date || !selectedSection) return
    setRowErrorMap(prev => ({ ...prev, [studentId]: 'Retrying...' }))
    try {
      const res = await api.post('/attendance', { records: [{ student_id: studentId, status }], section_id: selectedSection, date })
      // success
      setSavedMap(prev => ({ ...prev, [studentId]: 'Saved' }))
      setRowErrorMap(prev => {
        const copy = { ...prev }
        delete copy[studentId]
        return copy
      })
    } catch (err: any) {
      const msg = err?.response?.data?.message || err?.message || 'Failed'
      setRowErrorMap(prev => ({ ...prev, [studentId]: String(msg) }))
      setSavedMap(prev => ({ ...prev, [studentId]: 'Failed' }))
    }
  }

  const submitAttendance = async () => {
    if (!date) return alert('Please select a date')
    if (!selectedSection) return alert('Please select a section')
    setSubmitError(null)
  setSaving(true)
  setSavedMap({})
  setRowErrorMap({})

    const records = students.map(student => ({ student_id: student.id, status: attendance[student.id] || 'absent' }))

    try {
      const res = await api.post('/attendance', { records, section_id: selectedSection, date })
      const savedIds = Array.isArray(res.data) ? res.data.map((r: any) => String(r.student_id)) : []
      const newSaved: {[key:string]: string} = {}
      for (const s of students) {
        if (savedIds.includes(String(s.id))) newSaved[s.id] = 'Saved'
        else newSaved[s.id] = 'Skipped'
      }
      setSavedMap(newSaved)
      alert('Attendance saved!')
    } catch (err: any) {
      console.error(err)
      // Map server validation errors if available (assume structure: errors: { '<index>': ['msg'] } or per-record)
      const response = err?.response?.data
      if (response && response.errors && typeof response.errors === 'object') {
        // try to map per-student errors
        const remap: {[key:string]: string} = {}
        for (const key of Object.keys(response.errors)) {
          const val = response.errors[key]
          // try to detect student_id in key like records.0.student_id
          const m = key.match(/records\.(\d+)\.student_id/)
          if (m) {
            const idx = Number(m[1])
            const sid = String(students[idx]?.id)
            remap[sid] = Array.isArray(val) ? val.join(', ') : String(val)
          }
        }
        setRowErrorMap(remap)
      }
      const msg = response?.message || err?.message || 'Failed to save attendance'
      setSubmitError(String(msg))
    } finally {
      setSaving(false)
    }
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
                    <label className="sr-only">{`${status} for ${student.user.name}`}</label>
                    <input
                      aria-label={`${status} for ${student.user.name}`}
                      type="radio"
                      name={`attendance-${student.id}`}
                      onChange={() => markAttendance(student.id, status)}
                      checked={attendance[student.id] === status}
                      disabled={saving}
                    />
                  </td>
                ))}
                <td className="border p-2 text-center w-36">
                  <div className="flex flex-col items-center">
                    {saving ? (
                      <span className="text-sm text-gray-500">Saving...</span>
                    ) : rowErrorMap[student.id] ? (
                      <>
                        <span className="text-sm text-red-600">{rowErrorMap[student.id]}</span>
                        <div className="mt-1">
                          <button onClick={() => retryRow(student.id)} className="text-xs text-blue-600">Retry</button>
                          <button onClick={() => undoAttendance(student.id)} className="ml-2 text-xs text-gray-600">Undo</button>
                        </div>
                      </>
                    ) : savedMap[student.id] ? (
                      <>
                        <span className={`text-sm ${savedMap[student.id] === 'Saved' ? 'text-green-600' : 'text-red-600'}`}>{savedMap[student.id]}</span>
                        <div className="mt-1"><button onClick={() => undoAttendance(student.id)} className="text-xs text-gray-600">Undo</button></div>
                      </>
                    ) : (
                      <span className="text-sm text-gray-500">—</span>
                    )}
                  </div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      )}

      {students.length > 0 && (
        <button
          onClick={submitAttendance}
          disabled={saving}
          className={`mt-4 px-4 py-2 rounded ${saving ? 'bg-gray-400 text-gray-700 cursor-not-allowed' : 'bg-blue-600 text-white hover:bg-blue-700'}`}
        >
          {saving ? 'Saving…' : 'Save Attendance'}
        </button>
      )}

      {submitError && (
        <div className="mt-3 text-sm text-red-600">Error: {submitError}</div>
      )}
    </div>
  )
}
