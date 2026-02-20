<template>
  <div>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-zinc-100 mb-6">Dashboard</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <NuxtLink
        to="/niveis"
        class="group bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-xl p-6 hover:border-violet-300 dark:hover:border-violet-500/30 transition-colors"
      >
        <h2 class="text-base font-semibold text-gray-900 dark:text-zinc-100 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">Niveis</h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-zinc-500">Gerenciar niveis de profissionais</p>
      </NuxtLink>

      <NuxtLink
        to="/profissionais"
        class="group bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-xl p-6 hover:border-violet-300 dark:hover:border-violet-500/30 transition-colors"
      >
        <h2 class="text-base font-semibold text-gray-900 dark:text-zinc-100 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">Profissionais</h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-zinc-500">Gerenciar cadastro de profissionais</p>
      </NuxtLink>
    </div>
  </div>
</template>
