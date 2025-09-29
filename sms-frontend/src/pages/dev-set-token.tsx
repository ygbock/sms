import { useEffect } from 'react'
import { useRouter } from 'next/router'

export default function DevSetToken() {
  const router = useRouter()

  useEffect(() => {
    // token created by backend seeder for admin via login; generate a new one if needed
    // For convenience, perform a login request from the browser instead of embedding a token.
    const setToken = async () => {
      try {
        const res = await fetch('/api/auth/login', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ email: 'admin@example.com', password: 'password' }),
        })
        const json = await res.json()
        if (json.token) {
          localStorage.setItem('token', json.token)
          router.push('/admin/attendance-report')
        } else {
          alert('Dev login failed')
        }
      } catch (e) {
        // In dev the frontend may be served from a different port; do a cross-origin call
        try {
          const r = await fetch('http://127.0.0.1:8001/api/auth/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email: 'admin@example.com', password: 'password' }),
          })
          const j = await r.json()
          if (j.token) {
            localStorage.setItem('token', j.token)
            router.push('/admin/attendance-report')
          } else alert('Dev login failed')
        } catch (err) {
          alert('Dev helper failed: ' + String(err))
        }
      }
    }
    setToken()
  }, [])

  return <div className="p-6">Setting admin token for dev...</div>
}
