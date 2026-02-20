<template>
  <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-3 px-1">
    <!-- Per page selector + info -->
    <div class="flex items-center gap-3 text-sm text-gray-500 dark:text-zinc-500">
      <div class="flex items-center gap-1.5">
        <label class="text-xs">Itens por pagina:</label>
        <select
          :value="perPage"
          @change="onPerPageChange(Number(($event.target as HTMLSelectElement).value))"
          class="rounded-md border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-gray-700 dark:text-zinc-300 text-xs px-2 py-1 pr-7 appearance-none bg-no-repeat bg-[length:12px_12px] bg-[position:right_6px_center] transition-colors"
          :style="{ backgroundImage: `url('data:image/svg+xml,${encodeURIComponent(`<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; viewBox=&quot;0 0 20 20&quot; fill=&quot;%23a1a1aa&quot;><path fill-rule=&quot;evenodd&quot; d=&quot;M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z&quot; clip-rule=&quot;evenodd&quot;/></svg>`)}` }"
        >
          <option v-for="opt in perPageOptions" :key="opt" :value="opt">{{ opt }}</option>
        </select>
      </div>
      <span class="hidden sm:inline">
        {{ total }} registros
      </span>
    </div>

    <!-- Navigation -->
    <nav v-if="lastPage > 1" class="inline-flex items-center gap-1">
      <!-- First -->
      <button
        :disabled="currentPage <= 1"
        @click="$emit('page-change', 1)"
        class="p-1.5 rounded-lg disabled:opacity-30 transition-colors"
        :class="navBtnClass"
        title="Primeira pagina"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m18.75 4.5-7.5 7.5 7.5 7.5m-6-15L5.25 12l7.5 7.5"/></svg>
      </button>

      <!-- Previous -->
      <button
        :disabled="currentPage <= 1"
        @click="$emit('page-change', currentPage - 1)"
        class="p-1.5 rounded-lg disabled:opacity-30 transition-colors"
        :class="navBtnClass"
        title="Pagina anterior"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
      </button>

      <!-- Page numbers -->
      <button
        v-for="page in pages"
        :key="page"
        @click="$emit('page-change', page)"
        :class="[
          page === currentPage
            ? 'bg-violet-600 text-white'
            : navBtnClass,
          'inline-flex items-center justify-center w-8 h-8 rounded-lg text-sm font-medium transition-colors'
        ]"
      >
        {{ page }}
      </button>

      <!-- Next -->
      <button
        :disabled="currentPage >= lastPage"
        @click="$emit('page-change', currentPage + 1)"
        class="p-1.5 rounded-lg disabled:opacity-30 transition-colors"
        :class="navBtnClass"
        title="Proxima pagina"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
      </button>

      <!-- Last -->
      <button
        :disabled="currentPage >= lastPage"
        @click="$emit('page-change', lastPage)"
        class="p-1.5 rounded-lg disabled:opacity-30 transition-colors"
        :class="navBtnClass"
        title="Ultima pagina"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5"/></svg>
      </button>
    </nav>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = withDefaults(defineProps<{
  currentPage: number
  lastPage: number
  total: number
  perPage?: number
  perPageOptions?: number[]
}>(), {
  perPage: 15,
  perPageOptions: () => [5, 10, 15, 25, 50],
})

const emit = defineEmits<{
  'page-change': [page: number]
  'per-page-change': [perPage: number]
}>()

const navBtnClass = 'text-gray-700 dark:text-zinc-400 bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-800'

const pages = computed(() => {
  const maxVisible = 5
  const range: number[] = []
  let start = Math.max(1, props.currentPage - Math.floor(maxVisible / 2))
  let end = start + maxVisible - 1

  if (end > props.lastPage) {
    end = props.lastPage
    start = Math.max(1, end - maxVisible + 1)
  }

  for (let i = start; i <= end; i++) {
    range.push(i)
  }
  return range
})

function onPerPageChange(value: number) {
  emit('per-page-change', value)
}
</script>
