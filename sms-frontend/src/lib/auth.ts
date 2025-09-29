// Small auth helper: token storage and refresh queue
export const TOKEN_KEY = 'token'

export function getToken(): string | null {
  if (typeof window === 'undefined') return null
  return localStorage.getItem(TOKEN_KEY)
}

export function setToken(token: string | null) {
  if (typeof window === 'undefined') return
  if (token) localStorage.setItem(TOKEN_KEY, token)
  else localStorage.removeItem(TOKEN_KEY)
}

export function clearToken() {
  if (typeof window === 'undefined') return
  localStorage.removeItem(TOKEN_KEY)
}

// Refresh queue promise to avoid parallel refreshes
let refreshPromise: Promise<string | null> | null = null

export async function refreshToken(): Promise<string | null> {
  if (typeof window === 'undefined') return null
  if (refreshPromise) return refreshPromise

  refreshPromise = (async () => {
    try {
      const base = process.env.NEXT_PUBLIC_API_URL || 'http://127.0.0.1:8001'
      const res = await fetch(base + '/api/auth/refresh', {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${getToken() || ''}`,
          'Content-Type': 'application/json',
        },
        credentials: 'include',
      })
      if (!res.ok) {
        // failed to refresh — clear token
        setToken(null)
        refreshPromise = null
        return null
      }
      const j = await res.json()
      if (j && j.token) {
        setToken(j.token)
        const t = j.token
        refreshPromise = null
        return t
      }
      setToken(null)
      refreshPromise = null
      return null
    } catch (e) {
      setToken(null)
      refreshPromise = null
      return null
    }
  })()

  return refreshPromise
}
