# ApostaTudo - Sistema de Profissionais

Sistema de cadastro de profissionais com autenticacao JWT, refresh token e controle de acesso por roles (RBAC).

## Stack

- **Backend**: PHP 8.3 / Laravel 11
- **Frontend**: Nuxt 3 (Vue 3) / Tailwind CSS
- **Banco de Dados**: MySQL 8.0
- **Autenticacao**: JWT (php-open-source-saver/jwt-auth)
- **Containerizacao**: Docker / Docker Compose

## Decisoes Tecnicas

- **Arquitetura em camadas** no backend: Controller -> Service -> Repository, com interfaces para desacoplamento
- **JWT com refresh token opaco**: o access token eh um JWT com TTL de 60 min; o refresh token eh uma string aleatoria armazenada no banco (hash SHA-256) com TTL de 7 dias, permitindo revogacao server-side
- **Role no JWT custom claims**: o frontend le a role diretamente do token decodificado, sem API call extra
- **OPcache habilitado**: bytecode caching para performance otima
- **Config e route cache**: configuracoes e rotas sao cacheadas no startup do container
- **SPA puro (ssr: false)**: simplifica o gerenciamento de tokens no frontend
- **Pinia** para state management: solucao oficial do Vue 3
- **Interceptor de 401**: o composable `useApi` intercepta respostas 401, faz refresh automatico do token e retenta a requisicao original
- **Tema escuro/claro**: toggle com persistencia em localStorage, escuro por padrao

## Pre-requisitos

- Docker e Docker Compose

## Como Rodar

```bash
git clone <url-do-repositorio>
cd apostatudo
docker compose up -d --build
```

Pronto. O backend automaticamente:
- Cria o `.env` a partir do `.env.example` (se nao existir)
- Instala dependencias via Composer
- Gera APP_KEY e JWT_SECRET
- Corrige permissoes do storage
- Aguarda o MySQL ficar pronto
- Executa migrations e seeders
- Cacheia configuracoes e rotas

O frontend automaticamente:
- Instala dependencias via npm
- Executa `nuxt prepare`
- Inicia o servidor de desenvolvimento


## Acessos

| Servico  | URL                        |
|----------|----------------------------|
| Frontend | http://localhost:3000       |
| API      | http://localhost:8000/api   |
| MySQL    | localhost:3306              |

## Usuarios Padrao

| Email                | Senha    | Role  |
|----------------------|----------|-------|
| admin@apostatudo.com | password | ADMIN |
| user@apostatudo.com  | password | USER  |

O usuario ADMIN tem acesso completo ao CRUD. O usuario USER pode apenas visualizar os dados.

## Variaveis de Ambiente

### Backend (`backend/.env`)

| Variavel      | Descricao                    | Padrao               |
|---------------|------------------------------|----------------------|
| DB_HOST       | Host do MySQL                | mysql (container)    |
| DB_PORT       | Porta do MySQL               | 3306                 |
| DB_DATABASE   | Nome do banco                | apostatudo           |
| DB_USERNAME   | Usuario do banco             | apostatudo           |
| DB_PASSWORD   | Senha do banco               | secret               |
| JWT_SECRET    | Chave secreta do JWT         | (gerada via artisan) |
| FRONTEND_URL  | URL do frontend (para CORS)  | http://localhost:3000 |

## Endpoints da API

### Autenticacao

| Metodo | Rota                | Descricao       | Auth |
|--------|---------------------|-----------------|------|
| POST   | /api/auth/login     | Login           | Nao  |
| POST   | /api/auth/refresh   | Refresh token   | Nao  |
| POST   | /api/auth/logout    | Logout          | Sim  |

### Niveis

| Metodo | Rota               | Descricao      | Auth  | Role  |
|--------|--------------------|----------------|-------|-------|
| GET    | /api/niveis        | Listar         | Sim   | Todos |
| GET    | /api/niveis/{id}   | Detalhar       | Sim   | Todos |
| POST   | /api/niveis        | Criar          | Sim   | ADMIN |
| PUT    | /api/niveis/{id}   | Atualizar      | Sim   | ADMIN |
| DELETE | /api/niveis/{id}   | Excluir        | Sim   | ADMIN |

### Profissionais

| Metodo | Rota                     | Descricao | Auth  | Role  |
|--------|--------------------------|-----------|-------|-------|
| GET    | /api/profissionais       | Listar    | Sim   | Todos |
| GET    | /api/profissionais/{id}  | Detalhar  | Sim   | Todos |
| POST   | /api/profissionais       | Criar     | Sim   | ADMIN |
| PUT    | /api/profissionais/{id}  | Atualizar | Sim   | ADMIN |
| DELETE | /api/profissionais/{id}  | Excluir   | Sim   | ADMIN |

## CI/CD

O projeto utiliza **GitHub Actions** para integracao continua. A cada push ou pull request na branch `main`, dois jobs sao executados em paralelo:

- **Backend Tests**: roda os testes PHPUnit com PHP 8.3 e SQLite em memoria
- **Frontend Tests**: roda os testes Vitest com Node.js 22

O workflow esta configurado em `.github/workflows/tests.yml`.

## Estrutura do Projeto

```
apostatudo/
├── backend/
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   ├── Middleware/
│   │   │   └── Requests/
│   │   ├── Models/
│   │   ├── Repositories/
│   │   ├── Services/
│   │   └── Providers/
│   ├── config/
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   └── routes/api.php
├── frontend/
│   ├── components/
│   ├── composables/
│   ├── layouts/
│   ├── middleware/
│   ├── pages/
│   ├── stores/
│   └── types/
├── docker/
│   ├── backend/
│   ├── frontend/
│   └── nginx/
├── docker-compose.yml
└── README.md
```
