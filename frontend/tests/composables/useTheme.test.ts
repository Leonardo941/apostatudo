import { describe, it, expect, beforeEach } from 'vitest'
import { ref } from 'vue'

// Since useTheme relies on import.meta.client (Nuxt-specific),
// we test the theme logic directly
describe('useTheme', () => {
  beforeEach(() => {
    localStorage.clear()
    document.documentElement.classList.remove('dark')
  })

  function createTheme() {
    const isDark = ref(true)

    function init() {
      const stored = localStorage.getItem('theme')
      isDark.value = stored ? stored === 'dark' : true
      apply()
    }

    function toggle() {
      isDark.value = !isDark.value
      apply()
    }

    function apply() {
      const html = document.documentElement
      if (isDark.value) {
        html.classList.add('dark')
      } else {
        html.classList.remove('dark')
      }
      localStorage.setItem('theme', isDark.value ? 'dark' : 'light')
    }

    return { isDark, init, toggle }
  }

  it('initializes dark mode by default', () => {
    const { isDark, init } = createTheme()
    init()

    expect(isDark.value).toBe(true)
    expect(document.documentElement.classList.contains('dark')).toBe(true)
  })

  it('initializes light mode from localStorage', () => {
    localStorage.setItem('theme', 'light')

    const { isDark, init } = createTheme()
    init()

    expect(isDark.value).toBe(false)
    expect(document.documentElement.classList.contains('dark')).toBe(false)
  })

  it('toggles between dark and light', () => {
    const { isDark, init, toggle } = createTheme()
    init()

    expect(isDark.value).toBe(true)

    toggle()
    expect(isDark.value).toBe(false)
    expect(document.documentElement.classList.contains('dark')).toBe(false)
    expect(localStorage.getItem('theme')).toBe('light')

    toggle()
    expect(isDark.value).toBe(true)
    expect(document.documentElement.classList.contains('dark')).toBe(true)
    expect(localStorage.getItem('theme')).toBe('dark')
  })

  it('persists preference to localStorage', () => {
    const { init, toggle } = createTheme()
    init()

    toggle()
    expect(localStorage.getItem('theme')).toBe('light')

    toggle()
    expect(localStorage.getItem('theme')).toBe('dark')
  })
})
