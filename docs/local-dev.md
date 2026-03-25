# Local Development Guide

Passo a passo para subir, testar e validar o projeto localmente.

---

## Requisitos

| Ferramenta | Versão mínima |
| --- | --- |
| PHP | 8.3+ |
| Composer | 2.x |
| Node.js | 22.12+ ou 20.19+ |
| MySQL | 8.x |
| npm | 10.x |

> **Atenção com Node.js:** A versão 22.7.0 gera warnings de engine com Vite 8 e laravel-vite-plugin 3.
> Atualize para `v22.12.0+` para eliminar os warnings:
> ```bash
> nvm install 22.12
> nvm use 22.12
> ```

---

## Configuração inicial (primeira vez)

### 1. Copiar o .env

```bash
cp .env.example .env
php artisan key:generate
```

### 2. Configurar banco de dados no `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=platform_core_app
DB_USERNAME=root
DB_PASSWORD=
```

Criar o banco no MySQL se não existir:

```sql
CREATE DATABASE platform_core_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3. Instalar dependências

```bash
composer install
npm install
```

### 4. Rodar as migrations

```bash
php artisan migrate
```

### 5. Criar usuário de teste

```bash
php artisan tinker
```

Dentro do tinker:

```php
\App\Models\User::factory()->create([
    'email'    => 'admin@test.com',
    'password' => bcrypt('password'),
]);
exit
```

---

## Subindo o ambiente de desenvolvimento

Execute **um único comando** que sobe o servidor PHP, a fila e o Vite juntos:

```bash
composer run dev
```

Isso executa em paralelo:
- `php artisan serve` → app em `http://localhost:8000`
- `php artisan queue:listen` → processamento de jobs
- `npm run dev` → Vite em modo hot-reload

> **Importante:** Aguarde o Vite inicializar por completo (aparece `VITE v8.x ready`) antes de abrir o browser. Acessar antes resulta em `ViteManifestNotFoundException`.

### Alternativa sem Vite (assets estáticos)

Se preferir rodar só o servidor PHP sem o Vite rodando:

```bash
npm run build          # gera public/build/manifest.json
php artisan serve      # sobe apenas o servidor
```

---

## Acessando a aplicação

| URL | Descrição |
| --- | --- |
| `http://localhost:8000/login` | Tela de login |
| `http://localhost:8000/dashboard` | Dashboard principal |
| `http://localhost:8000/core/organizations` | Lista de organizações |
| `http://localhost:8000/core/organizations/create` | Criar nova organização |

Credenciais de teste: `admin@test.com` / `password`

---

## Verificação dos módulos

### Listar módulos ativos

```bash
php artisan module:list
```

Saída esperada:

```
[Enabled] Core         Modules/Core         [0]
[Enabled] Organizations Modules/Organizations [0]
```

### Listar rotas do módulo Organizations

```bash
php artisan route:list --name=core.organizations
```

Saída esperada:

```
GET  core/organizations                      core.organizations.index
GET  core/organizations/create               core.organizations.create
GET  core/organizations/{organization}/edit  core.organizations.edit
```

---

## Rodando os testes

Os testes usam SQLite em memória (configurado no `phpunit.xml`) — não afetam o banco de desenvolvimento.

### Todos os testes

```bash
php artisan test
```

### Só o módulo Organizations

```bash
php artisan test --filter OrganizationTest
```

### Saída esperada dos testes

```
PASS  Modules\Organizations\Tests\Feature\OrganizationTest

Organization model
✓ generates a unique slug from name on create
✓ appends counter to slug when slug already exists
✓ casts status to OrganizationStatus enum
✓ scopeActive filters only active orgs
✓ scopeSearch finds orgs by name

OrganizationService
✓ creates an organization with default active status
✓ attaches owner when provided
✓ soft-deletes an organization

Organization routes
✓ redirects unauthenticated users from index
✓ shows organizations index to authenticated users
✓ shows create form to authenticated users

Tests: 11 passed
```

---

## Limpeza de cache

Execute sempre que mudar configurações, rotas ou views:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
composer dump-autoload
```

Ou tudo de uma vez:

```bash
php artisan optimize:clear && composer dump-autoload
```

---

## Reset completo do banco

```bash
php artisan migrate:fresh
```

Para recriar com usuário de teste:

```bash
php artisan migrate:fresh && php artisan tinker --execute="\App\Models\User::factory()->create(['email'=>'admin@test.com','password'=>bcrypt('password')]);"
```

---

## Diagnóstico rápido de erros comuns

| Erro | Causa | Solução |
| --- | --- | --- |
| `ViteManifestNotFoundException` | Assets não buildados ou Vite não está rodando | Rode `npm run build` ou use `composer run dev` e aguarde o Vite iniciar |
| `No tests found` | `phpunit.xml` não aponta para `Modules/*/tests` | Já corrigido — garanta que o `phpunit.xml` tem `<directory>Modules/*/tests/Feature</directory>` |
| `Class not found` em namespace `Modules\` | Autoload desatualizado | Execute `composer dump-autoload` |
| Rota `core.organizations.*` não encontrada | Módulo desabilitado ou provider não registrado | Verifique `modules_statuses.json` e `php artisan module:list` |
| 500 na página de login (fresh install) | Banco não migrado | Execute `php artisan migrate` |
