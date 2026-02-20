<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-zinc-100">Niveis</h1>
      <button
        v-if="auth.isAdmin"
        @click="openCreate"
        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white bg-violet-600 hover:bg-violet-700 transition-colors"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Novo Nivel
      </button>
    </div>

    <div v-if="loading" class="flex items-center justify-center py-12">
      <svg class="animate-spin h-6 w-6 text-violet-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
    </div>

    <div v-else>
      <!-- Desktop table -->
      <div class="hidden md:block bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-xl overflow-hidden">
        <table class="w-full">
          <thead>
            <tr class="border-b border-gray-100 dark:border-zinc-800">
              <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 dark:text-zinc-500 uppercase tracking-wider">ID</th>
              <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 dark:text-zinc-500 uppercase tracking-wider">Nivel</th>
              <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 dark:text-zinc-500 uppercase tracking-wider">Profissionais</th>
              <th v-if="auth.isAdmin" class="px-5 py-3 text-right text-xs font-medium text-gray-400 dark:text-zinc-500 uppercase tracking-wider">Acoes</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="nivel in niveis" :key="nivel.id" class="border-b border-gray-50 dark:border-zinc-800/50 last:border-0 hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
              <td class="px-5 py-4 text-sm text-gray-500 dark:text-zinc-500 tabular-nums">{{ nivel.id }}</td>
              <td class="px-5 py-4 text-sm font-medium text-gray-900 dark:text-zinc-200 max-w-[250px] truncate">{{ nivel.nivel }}</td>
              <td class="px-5 py-4 text-sm text-gray-500 dark:text-zinc-500">{{ nivel.profissionais_count ?? 0 }}</td>
              <td v-if="auth.isAdmin" class="px-5 py-4">
                <div class="flex items-center justify-end gap-2">
                  <button @click="openEdit(nivel)" class="p-2 rounded-lg text-violet-600 dark:text-violet-400 hover:bg-violet-50 dark:hover:bg-violet-500/10 transition-colors" title="Editar">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
                  </button>
                  <button @click="confirmDelete(nivel)" class="p-2 rounded-lg text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors" title="Excluir">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="niveis.length === 0">
              <td :colspan="auth.isAdmin ? 4 : 3" class="px-5 py-12 text-center text-sm text-gray-400 dark:text-zinc-600">
                Nenhum nivel encontrado.
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Mobile cards -->
      <div class="md:hidden space-y-3">
        <div
          v-for="nivel in niveis"
          :key="nivel.id"
          class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-xl p-4"
        >
          <div class="flex items-center justify-between mb-2">
            <p class="text-sm font-semibold text-gray-900 dark:text-zinc-100 truncate flex-1 min-w-0 mr-3">{{ nivel.nivel }}</p>
            <span class="text-xs text-gray-400 dark:text-zinc-500 flex-shrink-0">{{ nivel.profissionais_count ?? 0 }} profissionais</span>
          </div>

          <div v-if="auth.isAdmin" class="flex gap-2 pt-3 border-t border-gray-100 dark:border-zinc-800">
            <button
              @click="openEdit(nivel)"
              class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-medium text-violet-700 dark:text-violet-400 bg-violet-50 dark:bg-violet-500/10 hover:bg-violet-100 dark:hover:bg-violet-500/20 transition-colors"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
              Editar
            </button>
            <button
              @click="confirmDelete(nivel)"
              class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-500/10 hover:bg-red-100 dark:hover:bg-red-500/20 transition-colors"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
              Excluir
            </button>
          </div>
        </div>

        <div v-if="niveis.length === 0" class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-xl px-4 py-12 text-center text-sm text-gray-400 dark:text-zinc-600">
          Nenhum nivel encontrado.
        </div>
      </div>

      <Pagination
        :current-page="currentPage"
        :last-page="lastPage"
        :total="total"
        :per-page="perPage"
        @page-change="onPageChange"
        @per-page-change="onPerPageChange"
      />
    </div>

    <!-- Modal Create/Edit -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="showModal = false"></div>
      <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-xl shadow-2xl p-6 z-10 max-w-md w-full">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-zinc-100 mb-4">
          {{ editingNivel ? 'Editar Nivel' : 'Novo Nivel' }}
        </h2>
        <form @submit.prevent="validateAndSubmit" novalidate>
          <FormInput
            v-model="form.nivel"
            label="Nivel"
            required
            :maxlength="255"
            :error="fieldErrors.nivel"
          />
          <div v-if="formError" class="mt-2 text-red-500 dark:text-red-400 text-sm">{{ formError }}</div>
          <div class="mt-5 flex justify-end gap-3">
            <button
              type="button"
              @click="showModal = false"
              class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-zinc-300 bg-gray-100 dark:bg-zinc-800 rounded-lg hover:bg-gray-200 dark:hover:bg-zinc-700 transition-colors"
            >
              Cancelar
            </button>
            <button
              type="submit"
              :disabled="submitting"
              class="px-4 py-2 text-sm font-medium text-white bg-violet-600 rounded-lg hover:bg-violet-700 disabled:opacity-50 transition-colors"
            >
              {{ submitting ? 'Salvando...' : 'Salvar' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <ConfirmDialog
      :show="showDeleteDialog"
      title="Excluir Nivel"
      :message="`Deseja excluir o nivel '${deletingNivel?.nivel}'?`"
      @confirm="handleDelete"
      @cancel="showDeleteDialog = false"
    />

    <!-- Alert dialog -->
    <div v-if="alertMessage" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="alertMessage = ''"></div>
      <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-xl shadow-2xl p-6 z-10 max-w-sm w-full">
        <div class="flex items-start gap-3 mb-4">
          <div class="flex-shrink-0 w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-500/15 flex items-center justify-center">
            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>
          </div>
          <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-zinc-100">Acao nao permitida</h3>
            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">{{ alertMessage }}</p>
          </div>
        </div>
        <div class="flex justify-end">
          <button
            @click="alertMessage = ''"
            class="px-4 py-2 text-sm font-medium text-white bg-violet-600 rounded-lg hover:bg-violet-700 transition-colors"
          >
            Entendi
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '~/stores/auth'
import { useApi } from '~/composables/useApi'
import type { Nivel, PaginatedResponse } from '~/types'

const auth = useAuthStore()
const { apiFetch } = useApi()

const niveis = ref<Nivel[]>([])
const loading = ref(true)
const currentPage = ref(1)
const lastPage = ref(1)
const total = ref(0)
const perPage = ref(15)

const showModal = ref(false)
const editingNivel = ref<Nivel | null>(null)
const form = reactive({ nivel: '' })
const formError = ref('')
const submitting = ref(false)
const fieldErrors = reactive<Record<string, string>>({})

watch(() => form.nivel, () => { delete fieldErrors.nivel })

function validateForm(): boolean {
  Object.keys(fieldErrors).forEach(key => delete fieldErrors[key])
  if (!form.nivel.trim()) fieldErrors.nivel = 'O nome do nivel e obrigatorio.'
  else if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(form.nivel)) fieldErrors.nivel = 'O nome do nivel deve conter apenas letras e espacos.'
  return Object.keys(fieldErrors).length === 0
}

function validateAndSubmit() {
  if (!validateForm()) return
  handleSubmit()
}

const showDeleteDialog = ref(false)
const deletingNivel = ref<Nivel | null>(null)
const alertMessage = ref('')

async function fetchNiveis(page = 1) {
  loading.value = true
  try {
    const data = await apiFetch<PaginatedResponse<Nivel>>(`/niveis?page=${page}&per_page=${perPage.value}`)
    niveis.value = data.data
    currentPage.value = data.meta.current_page
    lastPage.value = data.meta.last_page
    total.value = data.meta.total
  } catch (e: any) {
    console.error('Error loading niveis:', e)
  } finally {
    loading.value = false
  }
}

function onPageChange(page: number) {
  fetchNiveis(page)
}

function onPerPageChange(value: number) {
  perPage.value = value
  fetchNiveis(1)
}

function openCreate() {
  editingNivel.value = null
  form.nivel = ''
  formError.value = ''
  Object.keys(fieldErrors).forEach(key => delete fieldErrors[key])
  showModal.value = true
}

function openEdit(nivel: Nivel) {
  editingNivel.value = nivel
  form.nivel = nivel.nivel
  formError.value = ''
  Object.keys(fieldErrors).forEach(key => delete fieldErrors[key])
  showModal.value = true
}

async function handleSubmit() {
  submitting.value = true
  formError.value = ''

  try {
    if (editingNivel.value) {
      await apiFetch(`/niveis/${editingNivel.value.id}`, {
        method: 'PUT',
        body: { nivel: form.nivel },
      })
    } else {
      await apiFetch('/niveis', {
        method: 'POST',
        body: { nivel: form.nivel },
      })
    }
    showModal.value = false
    await fetchNiveis(currentPage.value)
  } catch (e: any) {
    const errors = e?.data?.errors
    if (errors) {
      Object.entries(errors).forEach(([key, msgs]: [string, any]) => {
        fieldErrors[key] = Array.isArray(msgs) ? msgs[0] : msgs
      })
    } else {
      formError.value = e?.data?.message || 'Erro ao salvar.'
    }
  } finally {
    submitting.value = false
  }
}

function confirmDelete(nivel: Nivel) {
  deletingNivel.value = nivel
  showDeleteDialog.value = true
}

async function handleDelete() {
  if (!deletingNivel.value) return

  try {
    await apiFetch(`/niveis/${deletingNivel.value.id}`, { method: 'DELETE' })
    showDeleteDialog.value = false
    deletingNivel.value = null
    await fetchNiveis(currentPage.value)
  } catch (e: any) {
    showDeleteDialog.value = false
    alertMessage.value = e?.data?.message || 'Erro ao excluir.'
  }
}

onMounted(() => fetchNiveis())
</script>
