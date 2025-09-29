import axios from 'axios'
import { getToken, setToken, refreshToken } from './auth'

const api = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL || 'http://127.0.0.1:8001/api',
  withCredentials: true,
})

api.interceptors.request.use((config) => {
  const token = getToken()
  if (token && config.headers) config.headers.Authorization = `Bearer ${token}`
  return config
})

// response interceptor to attempt token refresh once on 401 — queue refreshes
api.interceptors.response.use(
  (res) => res,
  async (error) => {
    const original = error.config
    if (error.response && error.response.status === 401 && !original._retry) {
      original._retry = true
      try {
        const newToken = await refreshToken()
        if (newToken) {
          original.headers = original.headers || {}
          original.headers['Authorization'] = `Bearer ${newToken}`
          return api(original)
        }
      } catch (e) {
        // fallthrough
      }
    }
    return Promise.reject(error)
  }
)

export default api
// src/lib/api-client.ts
/* import axios from 'axios'

const apiClient = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api',
  withCredentials: true,
})

export default apiClient */
