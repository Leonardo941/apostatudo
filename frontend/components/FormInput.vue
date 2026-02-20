<template>
  <div>
    <label v-if="label" class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">
      {{ label }} <span v-if="required" class="text-gray-400 dark:text-zinc-500">*</span>
    </label>
    <input
      :value="modelValue"
      @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
      @keyup="$emit('keyup', $event)"
      @keydown="$emit('keydown', $event)"
      :type="type"
      :maxlength="maxlength"
      :max="max"
      :placeholder="placeholder"
      class="block w-full rounded-lg border bg-white dark:bg-zinc-800 text-gray-900 dark:text-zinc-100 placeholder-gray-400 dark:placeholder-zinc-600 focus:border-violet-500 focus:ring-1 focus:ring-violet-500 text-sm px-3 py-2.5 transition-colors"
      :class="error ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-zinc-700'"
    />
    <div v-if="maxlength || error" class="flex justify-between mt-1">
      <span v-if="error" class="text-xs text-red-500 dark:text-red-400">{{ error }}</span>
      <span v-else></span>
      <span v-if="maxlength" class="text-xs text-gray-400 dark:text-zinc-600">{{ String(modelValue).length }}/{{ maxlength }}</span>
    </div>
    <span v-else-if="error" class="text-xs text-red-500 dark:text-red-400 mt-1 block">{{ error }}</span>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  modelValue: string | number
  label?: string
  type?: string
  required?: boolean
  maxlength?: number
  max?: string
  placeholder?: string
  error?: string
}>()

defineEmits<{
  'update:modelValue': [value: string]
  'keyup': [event: KeyboardEvent]
  'keydown': [event: KeyboardEvent]
}>()
</script>
