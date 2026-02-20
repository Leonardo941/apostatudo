import { defineStore } from 'pinia'
import { jwtDecode } from 'jwt-decode'
import type { AuthResponse, User } from '~/types'

interface JwtPayload {
  sub: number
  role: string
  exp: number
}

export const useAuthStore = defineStore('auth', {
  state: () => ({
    accessToken: null as string | null,
    user: null as User | null,
  }),

  getters: {
    isAuthenticated: (state) => !!state.accessToken,
    isAdmin: (state) => state.user?.role === 'ADMIN',
  },

  actions: {
    async login(email: string, password: string) {
      const config = useRuntimeConfig()

      const data = await $fetch<AuthResponse>(`${config.public.apiBase}/auth/login`, {
        method: 'POST',
        body: { email, password },
        credentials: 'include',
      })

      this.setTokens(data)
    },

    async refresh() {
      const config = useRuntimeConfig()

      try {
        const data = await $fetch<AuthResponse>(`${config.public.apiBase}/auth/refresh`, {
          method: 'POST',
          credentials: 'include',
        })

        this.setTokens(data)
      } catch {
        this.clearAuth()
        throw new Error('Refresh failed')
      }
    },

    async logout() {
      const config = useRuntimeConfig()

      try {
        await $fetch(`${config.public.apiBase}/auth/logout`, {
          method: 'POST',
          headers: { Authorization: `Bearer ${this.accessToken}` },
          credentials: 'include',
        })
      } catch {
        // Ignore errors on logout
      }

      this.clearAuth()
    },

    setTokens(data: AuthResponse) {
      this.accessToken = data.access_token

      const decoded = jwtDecode<JwtPayload>(data.access_token)
      this.user = {
        id: decoded.sub,
        nome: '',
        email: '',
        role: decoded.role,
      }

      localStorage.setItem('access_token', data.access_token)
    },

    loadFromStorage() {
      const accessToken = localStorage.getItem('access_token')

      if (accessToken) {
        try {
          const decoded = jwtDecode<JwtPayload>(accessToken)

          if (decoded.exp * 1000 > Date.now()) {
            this.accessToken = accessToken
            this.user = {
              id: decoded.sub,
              nome: '',
              email: '',
              role: decoded.role,
            }
          } else {
            this.accessToken = null
          }
        } catch {
          this.clearAuth()
        }
      }
    },

    clearAuth() {
      this.accessToken = null
      this.user = null
      localStorage.removeItem('access_token')
    },
  },
})
