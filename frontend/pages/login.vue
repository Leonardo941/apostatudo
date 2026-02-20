<template>
  <div>
    <NuxtLayout name="auth">
      <div class="w-full max-w-sm px-4">
        <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-xl shadow-lg p-8">
          <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-violet-600 dark:text-violet-400">ApostaTudo</h1>
            <p class="text-gray-500 dark:text-zinc-500 text-sm mt-1">Entre com suas credenciais</p>
          </div>

          <form @submit.prevent="validateAndSubmit" novalidate class="space-y-4">
            <FormInput
              v-model="form.email"
              label="E-mail"
              type="email"
              required
              placeholder="seu@email.com"
              :error="fieldErrors.email"
            />

            <div>
              <FormInput
                v-model="form.password"
                label="Senha"
                type="password"
                required
                placeholder="••••••••"
                :error="fieldErrors.password"
                @keyup="onKeyUp"
                @keydown="onKeyDown"
              />
              <div v-if="capsLockOn" class="flex items-center gap-1.5 mt-1.5 text-xs text-amber-600 dark:text-amber-400">
                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>
                Caps Lock esta ativado
              </div>
            </div>

            <div v-if="formError" class="text-red-500 dark:text-red-400 text-sm">{{ formError }}</div>

            <button
              type="submit"
              :disabled="loading"
              class="w-full py-2.5 px-4 rounded-lg text-sm font-medium text-white bg-violet-600 hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 dark:focus:ring-offset-zinc-900 disabled:opacity-50 transition-colors"
            >
              {{ loading ? 'Entrando...' : 'Entrar' }}
            </button>
          </form>
        </div>
      </div>
    </NuxtLayout>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '~/stores/auth'
import { useTheme } from '~/composables/useTheme'

definePageMeta({
  layout: false,
})

const auth = useAuthStore()
const { init } = useTheme()

onMounted(() => init())

const form = reactive({
  email: '',
  password: '',
})

const loading = ref(false)
const formError = ref('')
const fieldErrors = reactive<Record<string, string>>({})
const capsLockOn = ref(false)

function onKeyUp(e: KeyboardEvent) {
  capsLockOn.value = e.getModifierState('CapsLock')
}
function onKeyDown(e: KeyboardEvent) {
  capsLockOn.value = e.getModifierState('CapsLock')
}

watch(() => form.email, () => { delete fieldErrors.email })
watch(() => form.password, () => { delete fieldErrors.password })

function validateForm(): boolean {
  Object.keys(fieldErrors).forEach(key => delete fieldErrors[key])

  if (!form.email.trim()) fieldErrors.email = 'O e-mail e obrigatorio.'
  else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) fieldErrors.email = 'Informe um e-mail valido.'
  if (!form.password) fieldErrors.password = 'A senha e obrigatoria.'

  return Object.keys(fieldErrors).length === 0
}

function validateAndSubmit() {
  if (!validateForm()) return
  handleLogin()
}

async function handleLogin() {
  loading.value = true
  formError.value = ''

  try {
    await auth.login(form.email, form.password)
    navigateTo('/')
  } catch (e: any) {
    formError.value = e?.data?.message || 'Credenciais invalidas.'
  } finally {
    loading.value = false
  }
}
</script>
