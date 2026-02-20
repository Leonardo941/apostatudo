<template>
  <div>
    <label v-if="label" class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">
      {{ label }} <span v-if="required" class="text-gray-400 dark:text-zinc-500">*</span>
    </label>
    <select
      :value="modelValue"
      @change="$emit('update:modelValue', ($event.target as HTMLSelectElement).value)"
      class="block w-full rounded-lg border bg-white dark:bg-zinc-800 text-gray-900 dark:text-zinc-100 focus:border-violet-500 focus:ring-1 focus:ring-violet-500 text-sm px-3 py-2.5 pr-10 appearance-none bg-no-repeat bg-[length:16px_16px] bg-[position:right_12px_center] transition-colors"
      :class="[
        error ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-zinc-700',
        !modelValue && placeholder ? 'text-gray-400 dark:text-zinc-600' : ''
      ]"
      :style="{ backgroundImage: `url('data:image/svg+xml,${encodeURIComponent(`<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; viewBox=&quot;0 0 20 20&quot; fill=&quot;%23a1a1aa&quot;><path fill-rule=&quot;evenodd&quot; d=&quot;M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z&quot; clip-rule=&quot;evenodd&quot;/></svg>`)}` }"
    >
      <option v-if="placeholder" value="" disabled hidden>{{ placeholder }}</option>
      <slot />
    </select>
    <span v-if="error" class="text-xs text-red-500 dark:text-red-400 mt-1 block">{{ error }}</span>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  modelValue: string | number
  label?: string
  required?: boolean
  placeholder?: string
  error?: string
}>()

defineEmits<{
  'update:modelValue': [value: string]
}>()
</script>

<style>
.dark select option {
  background-color: #27272a;
  color: #e4e4e7;
}
</style>
