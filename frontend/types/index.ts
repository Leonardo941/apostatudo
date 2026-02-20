export interface User {
  id: number
  nome: string
  email: string
  role: string
}

export interface Role {
  id: number
  nome: string
}

export interface Nivel {
  id: number
  nivel: string
  profissionais_count?: number
}

export interface Profissional {
  id: number
  nivel_id: number
  nome: string
  sexo: 'M' | 'F' | 'O'
  data_nascimento: string
  hobby: string | null
  nivel?: Nivel
}

export interface AuthResponse {
  access_token: string
  token_type: string
  expires_in: number
}

export interface PaginationMeta {
  current_page: number
  per_page: number
  total: number
  last_page: number
}

export interface PaginatedResponse<T> {
  data: T[]
  meta: PaginationMeta
}
