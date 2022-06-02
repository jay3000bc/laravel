# Laravel CRM

## Requirements 
 + Linux/Unix, WAMP/XAMP or MacOS environment
 + PHP >= 7.3
 + MySQL >= 5.7.8
 + Web server (Apache, Nginx or integrated PHP web server for testing)
 + Composer 2.0+

## Installation

Install PHP dependencies:

```sh
composer install
```

Install NPM dependencies:

```sh
npm ci
```

Build assets:

```sh
npm run dev
```

Setup configuration:

```sh
cp .env.example .env
```

Generate application key:

```sh
php artisan key:generate
```

Run database migrations:

```sh
php artisan migrate
```

Run database seeder:

```sh
php artisan db:seed
```

Run the dev server (the output will give the address):

```sh
php artisan serve
```

You're ready to go! Visit Ping CRM in your browser, and login with:

- **Username:** bikram@gmail.com
- **Password:** secret

## Running tests

To run the Ping CRM tests, run:

```
phpunit
```
