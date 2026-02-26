<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Campus CRM (Laravel + Filament)

Campus CRM is bootstrapped as a CRM starter inspired by Creatio-style sales workflows.

Included modules:

- Accounts
- Contacts
- Leads
- Opportunities
- Activities (tasks/calls/emails/meetings)
- Users
- Roles
- Permissions
- Instructors
- Students
- Courses
- Topics
- Quizzes (with nested Questions and Answer Options)
- Enrollments
- Quiz Attempts
- Certifications

Auth and API:

- Session auth for Filament admin
- Laravel Passport for API OAuth2 (`/oauth/*` routes)

### Quick start

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Open the admin panel:

- URL: `http://127.0.0.1:8000/admin`
- Email: `admin@crm.local`
- Password: `admin12345`

Admin language switch:

- Use the user menu in `/admin` to switch between English and Ukrainian.
- Sidebar resource names and CRUD form/table labels are localized for Ukrainian.

Default role seeded:

- `Super Admin` (assigned to `admin@crm.local`)

## SPAs

Student SPA source:

- `/spa/courses`

Student SPA build output:

- `/public/spa/courses`

Home SPA source (copy of `kruk.in.ua`):

- `/spa/home`

Home SPA build output:

- `/public/spa/home`

Useful commands from project root:

```bash
npm run spa:install
npm run spa:dev
npm run spa:build
npm run spa:home:build
```

Routing behavior:

- `/admin/*` is reserved for Laravel Filament admin
- `/` serves Home SPA (`public/spa/home/index.html`) by default, and can be overridden by CMS page with slug `home`
- `/courses/*` serves Student SPA shell from `public/spa/courses/index.html`

## CMS Module

Filament includes `CMS Module -> CMS Pages` for managing page content.

- Homepage is stored as CMS page with slug `home`
- CMS page supports full `HTML`, optional custom `CSS`, custom `JS`, and meta fields
- API endpoint for active pages: `GET /api/cms/pages/{slug}`

Structured Home CMS resources are also available in admin:

- `Settings` (logo, who/mission, section titles, footer)
- `Top Menu`
- `Courses Section` (linked to LMS Courses)
- `Specializations`
- `Press`
- `Partners`
- `Footer Links`

Structured Home CMS API:

- `GET /api/cms/home?locale=uk` (or `en`)

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
