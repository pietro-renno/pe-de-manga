# Pé de Manga – Cultura e Afeto

Site institucional do **Ponto de Cultura Pé de Manga** desenvolvido em PHP com banco de dados MySQL.

---

## Requisitos

| Requisito | Versão mínima |
|-----------|---------------|
| PHP       | 7.4+          |
| MySQL     | 5.7+ / MariaDB 10.3+ |
| Extensão PDO + PDO_MySQL | habilitada |

---

## Instalação

### 1. Clone o repositório

```bash
git clone https://github.com/pietro-renno/pe-de-manga.git
cd pe-de-manga
```

### 2. Crie o banco de dados

No **phpMyAdmin** ou via terminal MySQL, execute o arquivo de migração:

```sql
-- No phpMyAdmin: Importar > selecionar database.sql
-- Ou via terminal:
mysql -u root -p < database.sql
```

O script cria o banco `pedemanga`, todas as tabelas e insere os dados de exemplo.

> **Atualizando um banco já existente?** Execute também as migrações incrementais,
> nesta ordem: `add_programacao_mes.sql` (programação do mês + horário dos eventos)
> e `add_programacao_multi.sql` (permite várias imagens de programação por mês).
> Elas não afetam dados existentes.

### 3. Configure a conexão com o banco

Edite o arquivo `includes/db.php` com suas credenciais:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'pedemanga');
define('DB_USER', 'root');     // seu usuário MySQL
define('DB_PASS', '');          // sua senha MySQL
```

### 4. Configure permissões de upload

```bash
chmod 775 data/uploads/
```

### 5. Suba os arquivos para o servidor

Faça upload de todos os arquivos para a raiz do seu domínio ou subpasta via FTP/SFTP.

---

## Estrutura do banco de dados

### Tabela `colaboradores`

| Coluna      | Tipo         | Descrição              |
|-------------|--------------|------------------------|
| id          | INT (PK, AI) | Identificador          |
| nome        | VARCHAR(255) | Nome completo          |
| funcao      | VARCHAR(255) | Cargo / função         |
| descricao   | TEXT         | Texto descritivo       |
| foto        | VARCHAR(255) | Nome do arquivo da foto |
| criado_em   | TIMESTAMP    | Data de cadastro       |

### Tabela `parceiros`

| Coluna    | Tipo         | Descrição              |
|-----------|--------------|------------------------|
| id        | INT (PK, AI) | Identificador          |
| nome      | VARCHAR(255) | Nome da organização    |
| site      | VARCHAR(500) | URL do site            |
| desc      | TEXT         | Descrição              |
| logo      | VARCHAR(255) | Nome do arquivo do logo |
| criado_em | TIMESTAMP    | Data de cadastro       |

### Tabela `galeria`

| Coluna    | Tipo         | Descrição              |
|-----------|--------------|------------------------|
| id        | INT (PK, AI) | Identificador          |
| arquivo   | VARCHAR(255) | Nome do arquivo        |
| descricao | VARCHAR(500) | Legenda da foto        |
| criado_em | TIMESTAMP    | Data de cadastro       |

---

## Estrutura de arquivos

```
pedemanga/
├── admin/
│   ├── login.php          → Tela de login do painel
│   ├── index.php          → Dashboard (contagens via banco)
│   ├── colaboradores.php  → CRUD de colaboradores
│   ├── parceiros.php      → CRUD de parceiros
│   ├── galeria.php        → CRUD da galeria
│   ├── _auth.php          → Guard de sessão + helper de upload
│   ├── _sidebar.php       → Sidebar do painel
│   └── logout.php         → Encerrar sessão
├── assets/
│   ├── css/style.css      → Estilos do site público
│   ├── css/admin.css      → Estilos do painel admin
│   └── js/main.js         → JavaScript do site público
├── data/
│   └── uploads/           → Imagens enviadas (não versionadas)
├── includes/
│   ├── db.php             → Conexão PDO com MySQL
│   ├── config.php         → Configurações e funções de leitura
│   ├── nav.php            → Barra de navegação
│   └── footer.php         → Rodapé
├── database.sql           → Script de criação do banco
├── index.php              → Página inicial
├── quem-somos.php
├── o-que-fazemos.php
├── colaboradores.php
├── parceiros.php
├── galeria.php
├── produtos.php
├── doacoes.php
└── transparencia.php
```

---

## Painel Administrativo

Acesse em: `seu-dominio.com/admin/login.php`

| Campo   | Valor          |
|---------|----------------|
| Usuário | `admin@pedemanga.org`        |
| Senha   | `pedemanga2025`|

> **Importante:** Altere a senha após o primeiro acesso.  
> Gere um novo hash com: `php -r "echo password_hash('nova_senha', PASSWORD_DEFAULT);"`  
> Substitua `ADM_PASS` em `admin/login.php`.

---

## Contato

- **E-mail:** pedemangacpv@gmail.com  
- **Telefone:** (12) 99762-4486  
- **PIX:** financasdopedemanga@gmail.com
