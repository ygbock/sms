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


