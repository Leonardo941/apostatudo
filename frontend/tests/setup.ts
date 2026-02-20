import { vi } from 'vitest'
import { ref, reactive, watch, onMounted, computed } from 'vue'

// Mock Nuxt auto-imports
vi.stubGlobal('ref', ref)
vi.stubGlobal('reactive', reactive)
vi.stubGlobal('watch', watch)
vi.stubGlobal('onMounted', onMounted)
vi.stubGlobal('computed', computed)

// Mock Nuxt's useState composable
vi.stubGlobal('useState', (key: string, init?: () => any) => {
  return ref(init ? init() : undefined)
})

// Mock Nuxt's useRuntimeConfig
vi.stubGlobal('useRuntimeConfig', () => ({
  public: {
    apiBase: 'http://localhost:8000/api',
  },
}))

// Mock Nuxt's $fetch
vi.stubGlobal('$fetch', vi.fn())

// Mock Nuxt's navigateTo
vi.stubGlobal('navigateTo', vi.fn())

// Mock Nuxt's definePageMeta
vi.stubGlobal('definePageMeta', vi.fn())

// Mock Nuxt's useRoute
vi.stubGlobal('useRoute', () => ({
  path: '/',
  params: {},
  query: {},
}))
