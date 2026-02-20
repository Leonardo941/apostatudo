<template>
  <NuxtLayout>
    <NuxtPage />
  </NuxtLayout>
</template>

<script setup lang="ts">
import { useTheme } from '~/composables/useTheme'

const { init } = useTheme()

onMounted(() => init())
</script>
