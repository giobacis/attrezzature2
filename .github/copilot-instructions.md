## Purpose
Give quick, actionable context for AI coding agents working on this PHP app (Scuola Fantoni • Gestione Attrezzature).

## Quick start (local dev)
- Primary environment: XAMPP/Apache on Windows. Place the repo under `C:\xampp\htdocs\attrezzature2` and start Apache + MySQL.
- Install PHP dependencies: run in PowerShell inside the project root:
  ```powershell
  cd C:\xampp\htdocs\attrezzature2
  composer install
  ```
- Alternative lightweight server for quick tests (note: base-path logic assumes a webroot; preferred: XAMPP):
  ```powershell
  cd C:\xampp\htdocs\attrezzature2
  php -S localhost:8000 -t .
  # then open http://localhost:8000/index.php
  ```

## High-level architecture
- `pages/`: public app pages (UI, routing-like entry points). Example: `pages/catalogo.php`, `pages/login.php`.
- `includes/`: shared helpers and UI fragments (session boot, auth helpers, `user_badge.php`). Key helpers:
  - `includes/session_boot.php` — session initialization and cookie flags.
  - `includes/auth_config.php` — `app_base_path()` and `redirect_after_login()` (roles: `ADMIN`, `IT`, `USER`).
  - `includes/user_badge.php` — shows login badge and computes `baseToRoot` when inside `pages/`.
- `config.php`: PDO/MySQL connection used across pages.
- `sql/` and top-level `.sql` files: DB seeds/migrations (`sql/users.sql`, `patch_movmg.sql`).
- `vendor/` and `composer.json`: third-party deps (PHPMailer).

Data flow / why things are structured this way
- Pages call `require` on small includes rather than a single front controller; this is a simple file-per-page app.
- `app_base_path()` strips a trailing `/pages` so pages can compute links relative to the application root — many includes rely on this behavior.

Project-specific conventions and gotchas
- Session and auth:
  - Session is always booted via `includes/session_boot.php`. Use `$_SESSION['email']` and `$_SESSION['role']` to detect authentication.
  - Roles: `ADMIN`, `IT`, default `USER`. Use `redirect_after_login()` (in `includes/auth_config.php`) for post-login routing.
- Base-path & link generation:
  - `app_base_path()` (see `includes/auth_config.php`) returns `/` or `/attrezzature2` depending on where the app is served.
  - `includes/user_badge.php` sets `$baseToRoot = '../'` if inside `pages/`; follow that pattern for relative links.
- Email domains:
  - Registration limits domains via the `ALLOWED_DOMAINS` array in `includes/auth_config.php`.
- Database:
  - DB connection in `config.php` uses PDO and throws exceptions. Update credentials there for local DB.
  - Use `sql/users.sql` and `patch_movmg.sql` to seed or patch the DB via phpMyAdmin or `mysql` CLI.
- Duplicate / similar files:
  - There are both `includes/auth_check.php` and `includes/auth_config.php` with overlapping helpers; prefer `includes/auth_config.php` (it is referenced in `index.php`).

Integration points
- PHPMailer via Composer (see `composer.json`). SMTP settings live in `includes/smtp_config.php` (and `smtp_config.example.php`).
- MySQL (local via XAMPP) — the app expects a database named `db_attrezzature` by default.

Debugging and developer workflows
- To see runtime errors during development, enable `display_errors` (php.ini) or add temporarily at top of `index.php`:
  ```php
  ini_set('display_errors', '1');
  error_reporting(E_ALL);
  ```
- Database import (PowerShell example):
  ```powershell
  mysql -u root -p db_attrezzature < C:\xampp\htdocs\attrezzature2\sql\users.sql
  ```
- Composer: `composer install` to fetch PHPMailer.
- Tests: repository has no automated test suite; use manual testing via the UI and sanitised test records.

Files to inspect for common tasks (quick references)
- DB config: `config.php`
- Session + cookies: `includes/session_boot.php`
- Auth & base-path: `includes/auth_config.php`
- UI include for headers/login: `includes/user_badge.php`, `includes/header.php`, `includes/footer.php`
- Pages: `pages/*.php` (most feature code lives here)
- Mail: `includes/smtp_config.php`, `pages/test_email.php`

Editing & committing guidance for AI agents
- Preserve existing include/require structure; avoid converting to a framework or single controller.
- When adding links or includes from inside `pages/`, follow `baseToRoot` convention (`'../'`) or use `app_base_path()`.
- When changing DB credentials, update only `config.php`. When adding DB fields, include SQL migration in `sql/` and update relevant `pages/*.php`.

What I couldn't discover automatically
- How the production environment is deployed (assumed XAMPP/Apache or similar). Confirm preferred webserver and whether HTTPS is enforced.

If anything is missing or you want more detail (e.g. mapping of routes → files, common edit patterns for email notifications or the booking flow), tell me which area to expand.
