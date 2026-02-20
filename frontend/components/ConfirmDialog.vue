<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="$emit('cancel')"></div>
    <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-xl shadow-2xl p-6 z-10 max-w-sm w-full">
      <h3 class="text-lg font-semibold text-gray-900 dark:text-zinc-100 mb-2">{{ title }}</h3>
      <p class="text-sm text-gray-500 dark:text-zinc-400 mb-5">{{ message }}</p>
      <div class="flex justify-end gap-3">
        <button
          @click="$emit('cancel')"
          class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-zinc-300 bg-gray-100 dark:bg-zinc-800 rounded-lg hover:bg-gray-200 dark:hover:bg-zinc-700 transition-colors"
        >
          Cancelar
        </button>
        <button
          @click="$emit('confirm')"
          class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors"
        >
          Confirmar
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  show: boolean
  title: string
  message: string
}>()

defineEmits<{
  confirm: []
  cancel: []
}>()
</script>
