# The Margin

The Margin is a server-rendered blog application built with Laravel 13, Blade, and Tailwind CSS. Guests can read posts and comments. Registered users can publish posts, join discussions, and manage their own content.

## Features

- Public post index and post detail pages. Posts are listed newest first, ten per page.
- Authenticated users can create, edit, and delete their own posts.
- Comments belong to a post and an author, appear oldest first, and are limited to 500 characters.
- Authenticated users can create, edit, and delete their own comments. Deleting a post removes its comments.
- Breeze registration, login, logout, password reset, and profile management.
- Responsive Blade interface with Tailwind CSS and Alpine.js.

## Requirements

- Docker Engine or Docker Desktop with the Docker Compose v2 plugin.
- PHP 8.5 CLI and Composer 2 for the initial dependency installation that provides the Sail command.
- Git.

Node.js and npm are available inside the Sail container; they are not required on the host.

## Installation

Clone the repository and enter the project directory. Copy the example environment file before installing dependencies:

```sh
cp .env.example .env
```

The example environment selects SQLite, while the Compose stack also provides MySQL 8.4. To use the bundled MySQL service, set these values in `.env` before starting Sail:

```dotenv
APP_PORT=8000
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```

These credentials are for local development. The default `APP_URL` in `.env.example` is `http://localhost:8000`, matching the `APP_PORT` above.

Install PHP dependencies once on the host to make `vendor/bin/sail` available. After this bootstrap step, run application, Composer, Artisan, and Node commands through Sail:

```sh
composer install
vendor/bin/sail up -d
vendor/bin/sail artisan key:generate
vendor/bin/sail artisan migrate
vendor/bin/sail npm install
vendor/bin/sail npm run build
```

Open [http://localhost:8000](http://localhost:8000). The Compose stack also starts Redis and Mailpit for local development. Mail is configured to use Laravel's log driver by default; change `MAIL_MAILER` in `.env` to use a mail service.

## Configuration and database

Environment-specific settings belong in `.env`, which is not committed. The committed `.env.example` defaults to SQLite; when using that default, keep `DB_CONNECTION=sqlite` and ensure `database/database.sqlite` exists before migrating. To create the file from inside the Sail container, run:

```sh
vendor/bin/sail php -r 'touch("database/database.sqlite");'
vendor/bin/sail artisan migrate
```

For MySQL, use the Compose service name `mysql` as `DB_HOST` and set the database variables shown in the installation section. Start the services before migrating:

```sh
vendor/bin/sail up -d
vendor/bin/sail artisan migrate
```

Migrations create the application tables. The database seeder creates sample users, posts, and comments; run it only when sample data is wanted:

```sh
vendor/bin/sail artisan db:seed
```

## Testing

The project uses PHPUnit with separate Unit and Feature suites. See [docs/testing.md](docs/testing.md) for the supported test commands, coverage expectations, and reporting requirements.

## Project documentation

- [AGENTS.md](AGENTS.md) contains repository development instructions.
- [docs/requirements.md](docs/requirements.md) records the application behavior and business rules.
- [docs/style.md](docs/style.md) records the visual design system and interface conventions.
- [docs/testing.md](docs/testing.md) defines testing practices and expected behavior.
