import { describe, it, expect, beforeEach, vi } from 'vitest'
import { setActivePinia, createPinia } from 'pinia'
import { useAuthStore } from '~/stores/auth'

// Create a valid JWT for testing (header.payload.signature)
function createTestJwt(payload: Record<string, any>): string {
  const header = btoa(JSON.stringify({ alg: 'HS256', typ: 'JWT' }))
  const body = btoa(JSON.stringify(payload))
  const signature = 'test-signature'
  return `${header}.${body}.${signature}`
}

describe('auth store', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
    localStorage.clear()
  })

  it('starts with no authentication', () => {
    const auth = useAuthStore()

    expect(auth.isAuthenticated).toBe(false)
    expect(auth.isAdmin).toBe(false)
    expect(auth.accessToken).toBeNull()
    expect(auth.user).toBeNull()
  })

  it('setTokens decodes JWT and sets user', () => {
    const auth = useAuthStore()
    const token = createTestJwt({
      sub: 1,
      role: 'ADMIN',
      exp: Math.floor(Date.now() / 1000) + 3600,
    })

    auth.setTokens({ access_token: token, token_type: 'bearer', expires_in: 3600 })

    expect(auth.isAuthenticated).toBe(true)
    expect(auth.isAdmin).toBe(true)
    expect(auth.user?.id).toBe(1)
    expect(auth.user?.role).toBe('ADMIN')
    expect(localStorage.getItem('access_token')).toBe(token)
  })

  it('clearAuth removes all state and localStorage', () => {
    const auth = useAuthStore()
    const token = createTestJwt({
      sub: 1,
      role: 'USER',
      exp: Math.floor(Date.now() / 1000) + 3600,
    })

    auth.setTokens({ access_token: token, token_type: 'bearer', expires_in: 3600 })
    auth.clearAuth()

    expect(auth.isAuthenticated).toBe(false)
    expect(auth.user).toBeNull()
    expect(localStorage.getItem('access_token')).toBeNull()
  })

  it('loadFromStorage restores valid token', () => {
    const auth = useAuthStore()
    const token = createTestJwt({
      sub: 2,
      role: 'USER',
      exp: Math.floor(Date.now() / 1000) + 3600,
    })

    localStorage.setItem('access_token', token)
    auth.loadFromStorage()

    expect(auth.isAuthenticated).toBe(true)
    expect(auth.user?.id).toBe(2)
    expect(auth.user?.role).toBe('USER')
  })

  it('loadFromStorage clears expired token', () => {
    const auth = useAuthStore()
    const token = createTestJwt({
      sub: 1,
      role: 'ADMIN',
      exp: Math.floor(Date.now() / 1000) - 3600,
    })

    localStorage.setItem('access_token', token)
    auth.loadFromStorage()

    expect(auth.isAuthenticated).toBe(false)
    expect(auth.user).toBeNull()
  })

  it('loadFromStorage handles invalid token', () => {
    const auth = useAuthStore()
    localStorage.setItem('access_token', 'invalid-token')

    auth.loadFromStorage()

    expect(auth.isAuthenticated).toBe(false)
    expect(auth.user).toBeNull()
    expect(localStorage.getItem('access_token')).toBeNull()
  })

  it('identifies USER role correctly', () => {
    const auth = useAuthStore()
    const token = createTestJwt({
      sub: 3,
      role: 'USER',
      exp: Math.floor(Date.now() / 1000) + 3600,
    })

    auth.setTokens({ access_token: token, token_type: 'bearer', expires_in: 3600 })

    expect(auth.isAdmin).toBe(false)
    expect(auth.isAuthenticated).toBe(true)
  })
})
