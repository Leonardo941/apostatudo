import type { FetchOptions } from 'ofetch'
import { useAuthStore } from '~/stores/auth'

export function useApi() {
  const config = useRuntimeConfig()
  const auth = useAuthStore()

  let isRefreshing = false
  let refreshPromise: Promise<void> | null = null

  async function apiFetch<T>(url: string, options: FetchOptions = {}): Promise<T> {
    const headers: Record<string, string> = {
      Accept: 'application/json',
      ...(options.headers as Record<string, string> || {}),
    }

    if (auth.accessToken) {
      headers.Authorization = `Bearer ${auth.accessToken}`
    }

    try {
      return await $fetch<T>(`${config.public.apiBase}${url}`, {
        ...options,
        headers,
        credentials: 'include',
      })
    } catch (error: any) {
      if (error?.response?.status === 401) {
        if (!isRefreshing) {
          isRefreshing = true
          refreshPromise = auth.refresh().finally(() => {
            isRefreshing = false
            refreshPromise = null
          })
        }

        try {
          await refreshPromise
          headers.Authorization = `Bearer ${auth.accessToken}`
          return await $fetch<T>(`${config.public.apiBase}${url}`, {
            ...options,
            headers,
            credentials: 'include',
          })
        } catch {
          auth.clearAuth()
          navigateTo('/login')
          throw error
        }
      }

      throw error
    }
  }

  return { apiFetch }
}
