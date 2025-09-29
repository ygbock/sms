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