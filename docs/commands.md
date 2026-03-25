# Commands Reference

---

## Setup Completo — Passo a Passo

### Ambiente Local (primeira vez)

Execute os comandos abaixo **em ordem**:

```bash
# 1. Instalar dependências PHP
composer install

# 2. Copiar arquivo de ambiente e gerar chave
cp .env.example .env
php artisan key:generate

# 3. Configurar banco de dados no .env
#    DB_CONNECTION=mysql
#    DB_HOST=127.0.0.1
#    DB_PORT=3306
#    DB_DATABASE=platform_core_app
#    DB_USERNAME=root
#    DB_PASSWORD=

# 4. Publicar config e migrations do spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

# 5. Rodar todas as migrations (base + módulos)
php artisan migrate

# 6. Criar link para storage público (necessário para uploads)
php artisan storage:link

# 7. Rodar seeders (roles, permissions, super-admin)
php artisan db:seed

# 8. Instalar dependências JS e compilar assets
npm install
npm run build

# 9. Iniciar servidor de desenvolvimento
composer run dev
```

> O comando `composer run dev` inicia `php artisan serve`, `npm run dev`, `php artisan queue:listen` e `php artisan pail` ao mesmo tempo.

---

### Ambiente de Produção (deploy)

```bash
# 1. Instalar dependências PHP (sem dev, otimizado)
composer install --no-dev --optimize-autoloader

# 2. Configurar o .env de produção (copiar e editar manualmente)
cp .env.example .env
php artisan key:generate

# 3. Publicar config do spatie (apenas na primeira vez)
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

# 4. Rodar migrations em produção
php artisan migrate --force

# 5. Rodar seeders
php artisan db:seed --force

# 6. Criar link do storage
php artisan storage:link

# 7. Compilar assets para produção
npm ci
npm run build

# 8. Cachear configurações, rotas e views
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 9. Limpar cache de permissões do spatie (se necessário)
php artisan permission:cache-reset
```

> Em produção, **nunca** use `migrate:fresh` — isso apaga todos os dados.

---

### Após adicionar um novo módulo

```bash
# Regenerar o autoloader com o novo module
composer dump-autoload -o

# Rodar as migrations do novo módulo
php artisan migrate

# Re-rodar seeders se o módulo adicionou novas permissões
php artisan db:seed --class=RolesAndPermissionsSeeder

# Verificar se o módulo está carregado
php artisan module:list
```

---

### Atualizar permissões sem resetar dados

O `RolesAndPermissionsSeeder` é idempotente — pode ser executado quantas vezes precisar:

```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
```

Usa `firstOrCreate` internamente. Não duplica roles nem permissions já existentes.

---

### Resetar ambiente local (desenvolvimento)

```bash
# Apaga todas as tabelas e recria do zero
php artisan migrate:fresh --seed
```

> Use apenas em desenvolvimento. Destrói todos os dados.

---

## Referência de Comandos

---

### `composer install`

Instala as dependências PHP definidas em `composer.json` e `composer.lock`.

```bash
composer install                      # desenvolvimento (inclui pacotes dev)
composer install --no-dev --optimize-autoloader   # produção
```

---

### `composer dump-autoload -o`

Regenera o autoloader otimizado. **Obrigatório** sempre que um novo módulo for adicionado.

```bash
composer dump-autoload -o
```

**Quando usar:**

- Novo módulo adicionado em `Modules/`
- Classe não encontrada mesmo existindo no local correto
- Após mover ou renomear diretórios de módulos

O flag `-o` gera um classmap otimizado. Em desenvolvimento pode-se omitir para rebuild mais rápido.

---

### `php artisan key:generate`

Gera a `APP_KEY` no `.env`. Necessário uma única vez na configuração inicial.

```bash
php artisan key:generate
```

---

### `php artisan vendor:publish`

Publica arquivos de configuração e migrations de pacotes externos.

```bash
# spatie/laravel-permission (obrigatório na primeira vez)
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

Gera:

- `config/permission.php`
- `database/migrations/xxxx_create_permission_tables.php`

---

### `php artisan migrate`

Executa todas as migrations pendentes — base e todos os módulos habilitados.

```bash
php artisan migrate
php artisan migrate --force                # forçar em produção
php artisan migrate:fresh                  # apagar e recriar (dev only)
php artisan migrate:fresh --seed           # apagar, recriar e seeder
php artisan migrate:status                 # ver status de cada migration
```

---

### `php artisan module:migrate [ModuleName]`

Executa migrations de um módulo específico apenas.

```bash
php artisan module:migrate FeatureFlags
php artisan module:migrate FeatureFlags --force
```

---

### `php artisan db:seed`

Executa todos os seeders registrados em `DatabaseSeeder`.

```bash
php artisan db:seed
php artisan db:seed --class=RolesAndPermissionsSeeder
php artisan db:seed --force   # produção
```

**Seeders disponíveis:**

- `RolesAndPermissionsSeeder` — cria 5 roles, todas as permissões `core.*`, atribui `super-admin` ao primeiro usuário

---

### `php artisan storage:link`

Cria o symlink `public/storage → storage/app/public`. Necessário para uploads com disco `public`.

```bash
php artisan storage:link
php artisan storage:link --force   # sobrescrever link existente
```

---

### `php artisan optimize:clear`

Limpa todos os caches: config, rotas, views, eventos e classes compiladas.

```bash
php artisan optimize:clear
```

**Quando usar:** Após alterações em `.env`, config ou rotas que não estão surtindo efeito.

---

### `php artisan config:cache` / `route:cache` / `view:cache`

Compila e cacheia arquivos para performance em produção.

```bash
php artisan config:cache   # compilar configs
php artisan route:cache    # compilar rotas
php artisan view:cache     # compilar Blade
php artisan event:cache    # compilar event listeners
```

> **Não usar em desenvolvimento** — impede que alterações em `.env` e código tenham efeito imediato.

---

### `php artisan module:list`

Lista todos os módulos registrados com status (Enabled/Disabled).

```bash
php artisan module:list
```

---

### `php artisan route:list`

Lista todas as rotas registradas.

```bash
php artisan route:list
php artisan route:list --name=core.   # filtrar por prefixo de nome
php artisan route:list --path=core/   # filtrar por prefixo de URL
```

---

### `php artisan module:make [ModuleName]`

Scaffolda um novo módulo em `Modules/[ModuleName]/`.

```bash
php artisan module:make Dashboard
php artisan module:make --plain Dashboard   # sem stubs extras
```

Após criar, executar `composer dump-autoload -o` para registrar o PSR-4 do novo módulo.

---

### `php artisan module:enable / module:disable`

Habilita ou desabilita um módulo sem removê-lo.

```bash
php artisan module:enable FeatureFlags
php artisan module:disable FeatureFlags
```

Altera `modules_statuses.json`. O módulo desabilitado não é carregado no boot.

---

### `php artisan permission:cache-reset`

Limpa o cache de roles e permissions do spatie.

```bash
php artisan permission:cache-reset
```

**Quando usar:** Após atualizar permissões em produção ou após rodar seeders em ambiente com cache ativo.

---

## Assets

### `npm install` / `npm ci`

```bash
npm install       # desenvolvimento (atualiza package-lock.json)
npm ci            # produção / CI (respeita o lock file exatamente)
```

### `npm run dev`

Inicia o servidor Vite com hot module replacement.

```bash
npm run dev
```

### `npm run build`

Compila e minifica assets para produção.

```bash
npm run build
```

---

## Desenvolvimento

### `composer run dev`

Inicia todos os processos de desenvolvimento em paralelo.

```bash
composer run dev
```

Executa simultaneamente:

- `php artisan serve` — servidor em `http://localhost:8000`
- `npm run dev` — Vite HMR
- `php artisan queue:listen --tries=1` — worker de filas
- `php artisan pail` — log viewer em tempo real

---

## Testes

### `./vendor/bin/pest`

Executa a suite de testes com Pest.

```bash
./vendor/bin/pest
./vendor/bin/pest --filter OrganizationTest
./vendor/bin/pest Modules/Organizations/tests/
./vendor/bin/pest --coverage   # com cobertura (requer Xdebug ou pcov)
```

> Os testes usam o banco `platform_core_app_test` (MySQL). Criar antes de rodar:

```bash
php artisan tinker --execute="DB::statement('CREATE DATABASE IF NOT EXISTS platform_core_app_test')"
```

---

## Code Quality

### `./vendor/bin/pint`

Formata código PHP conforme o estilo do Laravel (PSR-12 + regras customizadas).

```bash
./vendor/bin/pint                    # corrigir todos os arquivos
./vendor/bin/pint --test             # verificar sem corrigir
./vendor/bin/pint Modules/Settings/  # escopo de um módulo
```
