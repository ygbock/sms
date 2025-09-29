import { useEffect } from 'react'
import { useRouter } from 'next/router'
import { clearToken } from '@/lib/auth'

export default function Logout() {
  const router = useRouter()
  useEffect(() => {
    clearToken()
    router.replace('/login')
  }, [router])
  return <div className="p-4">Logging out...</div>
}
