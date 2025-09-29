import { useEffect, useState } from 'react'
import { useRouter } from 'next/router'
import api from '@/lib/api-client'


export default function Dashboard() {
const [user, setUser] = useState<any>(null)
const router = useRouter()


useEffect(() => {
const token = localStorage.getItem('token')
if (!token) router.push('/login')
else {
api.get('/users/me')
.then((res) => {
setUser(res.data)
const role = res.data.role.name
if (role === 'Teacher') router.push('/teacher/dashboard')
else if (role === 'Student') router.push('/student/dashboard')
else if (role === 'Parent') router.push('/parent/dashboard')
else if (role === 'Admin') router.push('/admin/dashboard')
})
.catch(() => router.push('/login'))
}
}, [router])


return <p>Loading...</p>
}