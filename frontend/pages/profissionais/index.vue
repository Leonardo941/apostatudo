<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-zinc-100">Profissionais</h1>
      <button
        v-if="auth.isAdmin"
        @click="openCreate"
        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white bg-violet-600 hover:bg-violet-700 transition-colors"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Novo Profissional
      </button>
    </div>

    <!-- Search bar -->
    <div class="mb-4">
      <div class="relative w-full sm:w-72">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Buscar por nome..."
          class="w-full pl-10 pr-9 py-2 rounded-lg border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-gray-900 dark:text-zinc-100 text-sm focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition-colors"
        />
        <button
          v-if="searchQuery"
          @click="clearSearch"
          class="absolute right-3 top-1/2 -translate-y-1/2 p-0.5 rounded text-gray-400 dark:text-zinc-500 hover:text-gray-600 dark:hover:text-zinc-300 hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/></svg>
        </button>
      </div>
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
              <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 dark:text-zinc-500 uppercase tracking-wider">Nome</th>
              <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 dark:text-zinc-500 uppercase tracking-wider">Nivel</th>
              <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 dark:text-zinc-500 uppercase tracking-wider">Sexo</th>
              <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 dark:text-zinc-500 uppercase tracking-wider">Data Nasc.</th>
              <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 dark:text-zinc-500 uppercase tracking-wider">Hobby</th>
              <th v-if="auth.isAdmin" class="px-5 py-3 text-right text-xs font-medium text-gray-400 dark:text-zinc-500 uppercase tracking-wider">Acoes</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="prof in profissionais" :key="prof.id" class="border-b border-gray-50 dark:border-zinc-800/50 last:border-0 hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
              <td class="px-5 py-4 max-w-[200px]">
                <div class="flex items-center gap-3">
                  <div class="flex-shrink-0 w-9 h-9 rounded-full flex items-center justify-center text-sm font-medium"
                    :class="prof.sexo === 'F' ? 'bg-pink-100 dark:bg-pink-500/15 text-pink-600 dark:text-pink-400' : 'bg-violet-100 dark:bg-violet-500/15 text-violet-600 dark:text-violet-400'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                  </div>
                  <span class="text-sm font-medium text-gray-900 dark:text-zinc-200 truncate">{{ prof.nome }}</span>
                </div>
              </td>
              <td class="px-5 py-4">
                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-violet-50 dark:bg-violet-500/10 text-violet-700 dark:text-violet-400 border border-violet-200/50 dark:border-violet-500/20">
                  {{ prof.nivel?.nivel }}
                </span>
              </td>
              <td class="px-5 py-4 text-sm text-gray-500 dark:text-zinc-400">{{ sexoLabel(prof.sexo) }}</td>
              <td class="px-5 py-4 text-sm text-gray-500 dark:text-zinc-400 tabular-nums">{{ formatDate(prof.data_nascimento) }}</td>
              <td class="px-5 py-4 text-sm text-gray-500 dark:text-zinc-400 max-w-[150px] truncate">{{ prof.hobby || '-' }}</td>
              <td v-if="auth.isAdmin" class="px-5 py-4">
                <div class="flex items-center justify-end gap-2">
                  <button @click="openEdit(prof)" class="p-2 rounded-lg text-violet-600 dark:text-violet-400 hover:bg-violet-50 dark:hover:bg-violet-500/10 transition-colors" title="Editar">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
                  </button>
                  <button @click="confirmDelete(prof)" class="p-2 rounded-lg text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors" title="Excluir">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="profissionais.length === 0">
              <td :colspan="auth.isAdmin ? 6 : 5" class="px-5 py-12 text-center text-sm text-gray-400 dark:text-zinc-600">
                Nenhum profissional encontrado.
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Mobile cards -->
      <div class="md:hidden space-y-3">
        <div
          v-for="prof in profissionais"
          :key="prof.id"
          class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-xl p-4"
        >
          <div class="flex items-center gap-3 mb-2">
            <div class="flex-shrink-0 w-9 h-9 rounded-full flex items-center justify-center"
              :class="prof.sexo === 'F' ? 'bg-pink-100 dark:bg-pink-500/15 text-pink-600 dark:text-pink-400' : 'bg-violet-100 dark:bg-violet-500/15 text-violet-600 dark:text-violet-400'">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
            </div>
            <p class="flex-1 min-w-0 text-sm font-semibold text-gray-900 dark:text-zinc-100 truncate">{{ prof.nome }}</p>
          </div>

          <div class="mb-3">
            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-violet-50 dark:bg-violet-500/10 text-violet-700 dark:text-violet-400 border border-violet-200/50 dark:border-violet-500/20">
              {{ prof.nivel?.nivel }}
            </span>
          </div>

          <div class="grid grid-cols-3 gap-2 text-xs mb-3">
            <div>
              <span class="text-gray-400 dark:text-zinc-500">Sexo:</span>
              <p class="text-gray-700 dark:text-zinc-300 font-medium">{{ sexoLabel(prof.sexo) }}</p>
            </div>
            <div>
              <span class="text-gray-400 dark:text-zinc-500">Data Nasc.:</span>
              <p class="text-gray-700 dark:text-zinc-300 font-medium tabular-nums">{{ formatDate(prof.data_nascimento) }}</p>
            </div>
            <div class="min-w-0">
              <span class="text-gray-400 dark:text-zinc-500">Hobby:</span>
              <p class="text-gray-700 dark:text-zinc-300 font-medium truncate">{{ prof.hobby || '-' }}</p>
            </div>
          </div>

          <div v-if="auth.isAdmin" class="flex gap-2 pt-3 border-t border-gray-100 dark:border-zinc-800">
            <button
              @click="openEdit(prof)"
              class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-medium text-violet-700 dark:text-violet-400 bg-violet-50 dark:bg-violet-500/10 hover:bg-violet-100 dark:hover:bg-violet-500/20 transition-colors"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
              Editar
            </button>
            <button
              @click="confirmDelete(prof)"
              class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-500/10 hover:bg-red-100 dark:hover:bg-red-500/20 transition-colors"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
              Excluir
            </button>
          </div>
        </div>

        <div v-if="profissionais.length === 0" class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-xl px-4 py-12 text-center text-sm text-gray-400 dark:text-zinc-600">
          Nenhum profissional encontrado.
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
      <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-xl shadow-2xl p-6 z-10 max-w-lg w-full max-h-[90vh] overflow-y-auto">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-zinc-100 mb-4">
          {{ editingProf ? 'Editar Profissional' : 'Novo Profissional' }}
        </h2>
        <form @submit.prevent="validateAndSubmit" novalidate class="space-y-4">
          <FormInput
            v-model="form.nome"
            label="Nome"
            required
            :maxlength="255"
            :error="fieldErrors.nome"
          />

          <FormSelect
            v-model="form.nivel_id"
            label="Nivel"
            required
            placeholder="Selecione um nivel"
            :error="fieldErrors.nivel_id"
          >
            <option v-for="nivel in allNiveis" :key="nivel.id" :value="nivel.id">
              {{ nivel.nivel }}
            </option>
          </FormSelect>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <FormSelect
              v-model="form.sexo"
              label="Sexo"
              required
              placeholder="Selecione"
              :error="fieldErrors.sexo"
            >
              <option value="M">Masculino</option>
              <option value="F">Feminino</option>
              <option value="O">Outro</option>
            </FormSelect>

            <FormInput
              v-model="form.data_nascimento"
              label="Data de Nascimento"
              type="date"
              required
              :max="maxDate"
              :error="fieldErrors.data_nascimento"
            />
          </div>

          <FormInput
            v-model="form.hobby"
            label="Hobby"
            :maxlength="255"
          />

          <div v-if="formError" class="text-red-500 dark:text-red-400 text-sm">{{ formError }}</div>

          <div class="flex justify-end gap-3 pt-1">
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
      title="Excluir Profissional"
      :message="`Deseja excluir o profissional '${deletingProf?.nome}'?`"
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
import type { Profissional, Nivel, PaginatedResponse } from '~/types'

const auth = useAuthStore()
const { apiFetch } = useApi()

const profissionais = ref<Profissional[]>([])
const allNiveis = ref<Nivel[]>([])
const loading = ref(true)
const currentPage = ref(1)
const lastPage = ref(1)
const total = ref(0)
const perPage = ref(15)
const searchQuery = ref('')
let searchDebounce: ReturnType<typeof setTimeout> | null = null

const showModal = ref(false)
const editingProf = ref<Profissional | null>(null)
const form = reactive({
  nome: '',
  nivel_id: '' as string | number,
  sexo: '',
  data_nascimento: '',
  hobby: '',
})
const formError = ref('')
const submitting = ref(false)
const fieldErrors = reactive<Record<string, string>>({})

const maxDate = computed(() => {
  const d = new Date()
  d.setDate(d.getDate() - 1)
  return d.toISOString().split('T')[0]
})

watch(() => form.nome, () => { delete fieldErrors.nome })
watch(() => form.nivel_id, () => { delete fieldErrors.nivel_id })
watch(() => form.sexo, () => { delete fieldErrors.sexo })
watch(() => form.data_nascimento, () => { delete fieldErrors.data_nascimento })

function validateForm(): boolean {
  Object.keys(fieldErrors).forEach(key => delete fieldErrors[key])

  if (!form.nome.trim()) fieldErrors.nome = 'O nome e obrigatorio.'
  if (!form.nivel_id) fieldErrors.nivel_id = 'Selecione um nivel.'
  if (!form.sexo) fieldErrors.sexo = 'Selecione o sexo.'
  if (!form.data_nascimento) fieldErrors.data_nascimento = 'Informe a data de nascimento.'

  return Object.keys(fieldErrors).length === 0
}

function validateAndSubmit() {
  if (!validateForm()) return
  handleSubmit()
}

const showDeleteDialog = ref(false)
const deletingProf = ref<Profissional | null>(null)
const alertMessage = ref('')

function sexoLabel(sexo: string) {
  const labels: Record<string, string> = { M: 'Masculino', F: 'Feminino', O: 'Outro' }
  return labels[sexo] || sexo
}

function formatDate(dateStr: string) {
  if (!dateStr) return '-'
  const [year, month, day] = dateStr.split('-')
  if (!year || !month || !day) return dateStr
  return `${day}/${month}/${year}`
}

async function fetchProfissionais(page = 1) {
  loading.value = true
  try {
    const params = new URLSearchParams({ page: String(page), per_page: String(perPage.value) })
    if (searchQuery.value.trim()) params.set('search', searchQuery.value.trim())
    const data = await apiFetch<PaginatedResponse<Profissional>>(`/profissionais?${params}`)
    profissionais.value = data.data
    currentPage.value = data.meta.current_page
    lastPage.value = data.meta.last_page
    total.value = data.meta.total
  } catch (e: any) {
    console.error('Error loading profissionais:', e)
  } finally {
    loading.value = false
  }
}

function onPageChange(page: number) {
  fetchProfissionais(page)
}

function onPerPageChange(value: number) {
  perPage.value = value
  fetchProfissionais(1)
}

function clearSearch() {
  searchQuery.value = ''
  if (searchDebounce) clearTimeout(searchDebounce)
  fetchProfissionais(1)
}

watch(searchQuery, (val) => {
  if (searchDebounce) clearTimeout(searchDebounce)
  if (val === '') return
  searchDebounce = setTimeout(() => fetchProfissionais(1), 300)
})

async function fetchNiveis() {
  try {
    const data = await apiFetch<PaginatedResponse<Nivel>>('/niveis?per_page=100')
    allNiveis.value = data.data
  } catch (e: any) {
    console.error('Error loading niveis:', e)
  }
}

function openCreate() {
  editingProf.value = null
  form.nome = ''
  form.nivel_id = ''
  form.sexo = ''
  form.data_nascimento = ''
  form.hobby = ''
  formError.value = ''
  Object.keys(fieldErrors).forEach(key => delete fieldErrors[key])
  showModal.value = true
  fetchNiveis()
}

function openEdit(prof: Profissional) {
  editingProf.value = prof
  form.nome = prof.nome
  form.nivel_id = prof.nivel_id
  form.sexo = prof.sexo
  form.data_nascimento = prof.data_nascimento
  form.hobby = prof.hobby || ''
  formError.value = ''
  Object.keys(fieldErrors).forEach(key => delete fieldErrors[key])
  showModal.value = true
  fetchNiveis()
}

async function handleSubmit() {
  submitting.value = true
  formError.value = ''

  const body = {
    nome: form.nome,
    nivel_id: Number(form.nivel_id),
    sexo: form.sexo,
    data_nascimento: form.data_nascimento,
    hobby: form.hobby || null,
  }

  try {
    if (editingProf.value) {
      await apiFetch(`/profissionais/${editingProf.value.id}`, {
        method: 'PUT',
        body,
      })
    } else {
      await apiFetch('/profissionais', {
        method: 'POST',
        body,
      })
    }
    showModal.value = false
    await fetchProfissionais(currentPage.value)
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

function confirmDelete(prof: Profissional) {
  deletingProf.value = prof
  showDeleteDialog.value = true
}

async function handleDelete() {
  if (!deletingProf.value) return

  try {
    await apiFetch(`/profissionais/${deletingProf.value.id}`, { method: 'DELETE' })
    showDeleteDialog.value = false
    deletingProf.value = null
    await fetchProfissionais(currentPage.value)
  } catch (e: any) {
    showDeleteDialog.value = false
    alertMessage.value = e?.data?.message || 'Erro ao excluir.'
  }
}

onMounted(() => fetchProfissionais())
</script>
