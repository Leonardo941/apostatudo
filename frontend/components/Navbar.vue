<template>
  <nav class="bg-white dark:bg-zinc-900 border-b border-gray-200 dark:border-zinc-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-14">
        <div class="flex items-center gap-8">
          <NuxtLink to="/" class="text-lg font-bold text-violet-600 dark:text-violet-400 hover:text-violet-700 dark:hover:text-violet-300 transition-colors">
            ApostaTudo
          </NuxtLink>

          <div class="hidden sm:flex items-center gap-1">
            <NuxtLink
              to="/niveis"
              class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors"
              :class="$route.path.startsWith('/niveis')
                ? 'bg-violet-50 dark:bg-violet-500/15 text-violet-700 dark:text-violet-300'
                : 'text-gray-600 dark:text-zinc-400 hover:text-gray-900 dark:hover:text-zinc-200 hover:bg-gray-50 dark:hover:bg-zinc-800'"
            >
              Niveis
            </NuxtLink>
            <NuxtLink
              to="/profissionais"
              class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors"
              :class="$route.path.startsWith('/profissionais')
                ? 'bg-violet-50 dark:bg-violet-500/15 text-violet-700 dark:text-violet-300'
                : 'text-gray-600 dark:text-zinc-400 hover:text-gray-900 dark:hover:text-zinc-200 hover:bg-gray-50 dark:hover:bg-zinc-800'"
            >
              Profissionais
            </NuxtLink>
          </div>
        </div>

        <div class="flex items-center gap-3">
          <button
            @click="toggle()"
            class="p-2 rounded-md text-gray-500 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors"
            :title="isDark ? 'Tema claro' : 'Tema escuro'"
          >
            <svg v-if="isDark" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
          </button>

          <span
            class="hidden sm:inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full"
            :class="auth.isAdmin
              ? 'bg-violet-100 dark:bg-violet-500/15 text-violet-700 dark:text-violet-400 border border-violet-200 dark:border-violet-500/25'
              : 'bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-400 border border-gray-200 dark:border-zinc-700'"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
            {{ auth.user?.role }}
          </span>

          <button
            @click="handleLogout"
            class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-zinc-400 hover:text-red-600 dark:hover:text-red-400 font-medium transition-colors"
            title="Sair"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
            <span class="hidden sm:inline">Sair</span>
          </button>
        </div>
      </div>

      <!-- Mobile nav -->
      <div class="sm:hidden flex items-center gap-2 pb-3">
        <NuxtLink
          to="/niveis"
          class="text-sm font-medium px-3 py-1.5 rounded-md transition-colors"
          :class="$route.path.startsWith('/niveis')
            ? 'bg-violet-50 dark:bg-violet-500/15 text-violet-700 dark:text-violet-300'
            : 'text-gray-500 dark:text-zinc-400'"
        >
          Niveis
        </NuxtLink>
        <NuxtLink
          to="/profissionais"
          class="text-sm font-medium px-3 py-1.5 rounded-md transition-colors"
          :class="$route.path.startsWith('/profissionais')
            ? 'bg-violet-50 dark:bg-violet-500/15 text-violet-700 dark:text-violet-300'
            : 'text-gray-500 dark:text-zinc-400'"
        >
          Profissionais
        </NuxtLink>
        <span
          class="ml-auto inline-flex items-center gap-1 text-[10px] font-medium px-2 py-0.5 rounded-full"
          :class="auth.isAdmin
            ? 'bg-violet-100 dark:bg-violet-500/15 text-violet-700 dark:text-violet-400'
            : 'bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-400'"
        >
          {{ auth.user?.role }}
        </span>
      </div>
    </div>
  </nav>

  <!-- Breadcrumb -->
  <div v-if="breadcrumbs.length > 1" class="bg-gray-50 dark:bg-zinc-950 border-b border-gray-100 dark:border-zinc-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
      <nav class="flex items-center gap-1.5 text-xs text-gray-400 dark:text-zinc-600">
        <template v-for="(crumb, i) in breadcrumbs" :key="i">
          <svg v-if="i > 0" class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
          <NuxtLink
            v-if="crumb.to && i < breadcrumbs.length - 1"
            :to="crumb.to"
            class="hover:text-gray-600 dark:hover:text-zinc-400 transition-colors"
          >
            {{ crumb.label }}
          </NuxtLink>
          <span v-else class="text-gray-600 dark:text-zinc-400 font-medium">{{ crumb.label }}</span>
        </template>
      </nav>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '~/stores/auth'
import { useTheme } from '~/composables/useTheme'

const auth = useAuthStore()
const { isDark, toggle } = useTheme()
const route = useRoute()

const breadcrumbs = computed(() => {
  const crumbs: { label: string; to?: string }[] = [
    { label: 'Inicio', to: '/' },
  ]

  const path = route.path
  if (path.startsWith('/niveis')) {
    crumbs.push({ label: 'Niveis', to: '/niveis' })
  } else if (path.startsWith('/profissionais')) {
    crumbs.push({ label: 'Profissionais', to: '/profissionais' })
  }

  return crumbs
})

async function handleLogout() {
  await auth.logout()
  navigateTo('/login')
}
</script>
