import { useEffect, useState } from 'react'
import { useRouter } from 'next/router'
import api from '@/lib/api-client'

export default function Dashboard() {
  const [user, setUser] = useState<any>(null)
  const router = useRouter()

  useEffect(() => {
    const token = typeof window !== 'undefined' ? localStorage.getItem('token') : null
    if (!token) router.push('/login')
    else {
      api.get('/users/me')
        .then((res) => setUser(res.data))
        .catch(() => router.push('/login'))
    }
  }, [router])

  if (!user) return <p>Loading...</p>

  // redirect based on role
  const role = user.role?.name
  if (role === 'Teacher') router.push('/teacher/dashboard')
  if (role === 'Student') router.push('/student/dashboard')
  if (role === 'Parent') router.push('/parent/dashboard')
  if (role === 'Admin') router.push('/admin/dashboard')

  return <p>Redirecting...</p>
}
