# School Media Requirements

## 1. Overview
- Web application for managing school media content (news/posts) with gated admin area.
- Built on CakePHP 5.2 skeleton, PHP 8.1+, Bootstrap-like layout replaced by TailwindCSS output located under `webroot/css`.

## 2. Technology Stack
- Backend: CakePHP 5.2, `cakephp/authentication`, `cakephp/migrations`, `mobiledetect/mobiledetectlib`.
- Frontend: Tailwind CSS 3.x compiled via `npm run dev` / `npm run build`.
- Tooling: Composer for PHP dependencies, npm for frontend tooling, PHPUnit, PHPStan, Psalm, PHP_CodeSniffer.

## 3. Functional Requirements
### 3.1 User Accounts & Authentication
- Users can register via `/register` with required fields: name (max 120 chars), email (unique, valid), password (>=8 chars), role (`student`, `teacher`, `admin`), active flag defaults true.
- Passwords are hashed using CakePHP's `DefaultPasswordHasher` before persistence.
- Login available at `/login` accepting GET and POST; successful login redirects to previously requested URL or `/admin/dashboard`.
- Users can log out via `/logout`, which clears the authentication session.
- Unauthenticated access is permitted only for `login` and `register`; all other actions require authentication hooks configured through `Authentication.Authentication` component.

### 3.2 Posts Management
- CRUD interface for posts (`PostsController`):
  - `index`: paginated list ordered by `created` descending.
  - `view`: view individual post by numeric ID or slug; rejects missing/invalid identifiers.
  - `add`: create new post; flashes success/error and redirects to index on success.
  - `edit`: update post (PATCH/POST/PUT) and redirect to view on success.
  - `delete`: POST/DELETE only; redirects to index after attempting removal.
- Posts schema requirements:
  - `title` (string, <=200, required)
  - `slug` (string, <=200, required, globally unique)
  - `body` (text, required)
  - `published` (boolean, optional, default false)
  - Timestamp behavior auto-manages `created` and `modified`.
- Provide finder `findPublished` to fetch only published posts ordered by recency; intended for public feeds such as `/school-media/*`.

### 3.3 Routing & Navigation
- Base routes (`config/routes.php`):
  - `/` → `Pages::display('home')`.
  - `/login`, `/logout`, `/register` mapped to `UsersController`.
  - `/school-media/*` mapped to `Posts::view` for slug-based access.
- Admin prefix scope (`/admin`) routes to `DashboardController::index` with fallback routes for future admin modules.

## 4. Data Validation & Integrity Rules
- Users: validation ensures presence and format for `name`, `email`, `password`, `role`. Unique constraint enforced on `email`.
- Posts: validation ensures presence/length for `title`, `slug`, `body`; optional boolean `published`. Unique constraint enforced on `slug`.

## 5. Non-Functional Requirements
- Security: enforce hashed passwords, restrict unauthenticated access to sensitive endpoints, flash generic error messages.
- Performance: Pagination for post listing; leverage CakePHP ORM query builders for filtering.
- Maintainability: Follow CakePHP MVC conventions; keep controllers thin and business logic in Tables/Entities.
- Internationalization: Utilize `__()` helper for user-facing strings (e.g., flash messages) to ease translation.

## 6. Tooling & Operational Requirements
- Environment configuration via `config/app_local.php`; include datasource creds, security salts.
- Database migrations managed with `bin/cake migrations` (Migrations plugin included).
- Local development server: `bin/cake server -p 8765`.
- Quality commands:
  - `composer test` (PHPUnit)
  - `composer cs-check` / `composer cs-fix` (PHP_CodeSniffer)
  - `vendor/bin/phpstan analyse`, `vendor/bin/psalm`
- Frontend build:
  - `npm install`, then `npm run dev` (watch) or `npm run build` (minified CSS).

## 7. Future Considerations
- Implement role-based authorization (e.g., restrict post management to `admin`/`teacher`).
- Extend `Posts` with media attachments or categories.
- Add REST API scope under `/api` if headless access is required (scaffolding ready via Cake routes).

