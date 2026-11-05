# Nest Point — Real Estate Listing Platform

A PHP + MySQL web app where **sellers list homes**, **buyers search them** and **admins oversee every listing**. Each role has its own sign-up, login and dashboard. Started as a team project for the Web Programming course at Georgia State University.

![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8-4479A1?logo=mysql&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-2496ED?logo=docker&logoColor=white)
![CI](https://github.com/ramya036/nestpoint-real-estate/actions/workflows/ci.yml/badge.svg)

```
            ┌──────────── index.php ────────────┐
            │                                   │
   Buyer: register → login → search listings    │
   Seller: register → login → add / delete listings (with photo)
   Admin:  register → login → see all listings  │
            │                                   │
            └── config.php (env-based DB settings, role checks) ── MySQL
```

## Features

- **Three roles** with separate registration, login and dashboards
- **Sellers** add listings with a name, address, age, bedrooms, bathrooms, garden, parking, price and a photo, and can delete them
- **Buyers** search listings by address, bedrooms, garden, parking or price
- **Admins** see every listing on the platform

## Security

- Passwords stored as **bcrypt hashes** (`password_hash` / `password_verify`)
- **Prepared statements** for every query that takes user input, so SQL injection doesn't work
- **Role checks** on every dashboard and on add/delete, so signed-out visitors and other roles are redirected to login
- **Safe photo uploads**: only real JPG, PNG or WEBP images under 5 MB, saved under a random file name
- **No credentials in code**: database settings come from environment variables (`.env.example`)

## Run it

### With Docker (recommended)

```bash
git clone https://github.com/ramya036/nestpoint-real-estate.git
cd nestpoint-real-estate
docker compose up --build
```

Open http://localhost:8080. The database is created from `schema.sql` automatically.

### Without Docker

1. Install PHP 8 with the `mysqli` and `pdo_mysql` extensions, and MySQL 8.
2. Create a database and load the schema: `mysql -u <user> -p <database> < schema.sql`
3. Set `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER` and `DB_PASS` (see `.env.example`).
4. Start a server in the project folder: `php -S localhost:8080`

## Tests

`tests/smoke.sh` starts the app and checks the main flows end to end with real HTTP requests:

- the home page loads, and every protected page redirects when signed out
- passwords are saved as bcrypt hashes
- SQL-injection and wrong-password logins are rejected, and a correct login works
- buyers can't open seller pages
- sellers can add a listing with a photo, and non-image uploads are refused

```bash
DB_HOST=127.0.0.1 DB_NAME=nestpoint DB_USER=nestpoint DB_PASS=nestpoint bash tests/smoke.sh
```

GitHub Actions runs PHP lint and this test against MySQL on every push.

## Project structure

| File(s) | Purpose |
|---|---|
| `config.php` | Database settings from environment variables, connection helpers, role checks |
| `schema.sql` | All tables: buyers, sellers, admins and listings |
| `index.php`, `global_login.php` | Landing page and role picker |
| `*_registration_form.php`, `*_register_submit.php` | Sign-up for each role |
| `loginb.php`, `logins.php`, `login.php`, `*_login_submit.php` | Login for buyers, sellers and admins |
| `buyer_dashboard.php`, `seller_dashboard.php`, `admin_dashboard.php` | Role dashboards |
| `form.php`, `insert_card.php`, `delete_card.php` | Add and remove listings |
| `tests/smoke.sh` | End-to-end test |

## Team

Built by Ramya Sree Narra with classmates in the GSU Web Programming course.
