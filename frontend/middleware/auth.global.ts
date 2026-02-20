import { useAuthStore } from '~/stores/auth'

let loaded = false

export default defineNuxtRouteMiddleware((to) => {
  if (!import.meta.client) return

  const auth = useAuthStore()

  if (!loaded) {
    auth.loadFromStorage()
    loaded = true
  }

  if (to.path !== '/login' && !auth.isAuthenticated) {
    return navigateTo('/login')
  }

  if (to.path === '/login' && auth.isAuthenticated) {
    return navigateTo('/')
  }
})
