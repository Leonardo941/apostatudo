export function useTheme() {
  const isDark = useState('theme-dark', () => true)

  function init() {
    if (!import.meta.client) return
    const stored = localStorage.getItem('theme')
    isDark.value = stored ? stored === 'dark' : true
    apply()
  }

  function toggle() {
    isDark.value = !isDark.value
    apply()
  }

  function apply() {
    if (!import.meta.client) return
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
