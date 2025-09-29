// pages/admin/attendance-report.tsx
import { useEffect, useState } from 'react'
import apiClient from '@/lib/api-client'
import { AxiosResponse } from 'axios'

interface Section {
  id: string
  name: string
}

interface AttendanceRecord {
  student: { user: { name: string } }
  date: string
  status: string
}

export default function AttendanceReport() {
  const [sections, setSections] = useState<Section[]>([])
  const [selectedSection, setSelectedSection] = useState<string>('')
  const [fromDate, setFromDate] = useState<string>('')
  const [toDate, setToDate] = useState<string>('')
  const [records, setRecords] = useState<AttendanceRecord[]>([])

  useEffect(() => {
    apiClient
      .get('/sections')
      .then((res: AxiosResponse<Section[]>) => setSections(res.data))
      .catch((err: unknown) => {
        if (err instanceof Error) console.error(err.message)
      })
  }, [])

  const loadReport = () => {
    if (!selectedSection || !fromDate || !toDate) {
      alert('Please select all filters')
      return
    }

    apiClient
      .get('/admin/attendance-report', {
        params: { section_id: selectedSection, from: fromDate, to: toDate },
      })
      .then((res: AxiosResponse<AttendanceRecord[]>) => setRecords(res.data))
      .catch((err: unknown) => {
        if (err instanceof Error) console.error(err.message)
      })
  }

  const exportCSV = () => {
    let csv = 'Student,Date,Status\n'
    records.forEach((r) => {
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
          {sections.map((sec) => (
            <option key={sec.id} value={sec.id}>
              {sec.name}
            </option>
          ))}
        </select>

        <input
          type="date"
          value={fromDate}
          onChange={(e) => setFromDate(e.target.value)}
          className="border p-2 rounded"
        />
        <input
          type="date"
          value={toDate}
          onChange={(e) => setToDate(e.target.value)}
          className="border p-2 rounded"
        />

        <button
          onClick={loadReport}
          className="px-4 py-2 bg-blue-600 text-white rounded"
        >
          Load
        </button>
        <button
          onClick={exportCSV}
          className="px-4 py-2 bg-green-600 text-white rounded"
        >
          Export CSV
        </button>
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
