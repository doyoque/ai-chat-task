# Customer Loyality Campaign

Simple give away voucher code campaign for loyal customer.

## Pre requisite

- Laravel v9.19
- PHP v8.1.13
- Composer v2.4.4
- MySQL v8.0

## Run Local Manually

Copy `.env.example` to `.env`. <br>
Configure `DB_` value as the same as your installed database. Here for the example.
```bash
DB_CONNECTION=mysql
DB_HOST=0.0.0.0
DB_PORT=3306
DB_DATABASE=aichat
DB_USERNAME=root
DB_PASSWORD=secret123
```

Generate artisan key with `php artisan key:generate`.<br>
Run locally with `php artisan serve`. It will open `http://localhost:8000`.

## Run Local With Docker
