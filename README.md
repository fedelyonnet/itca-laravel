# ITCA Laravel Project

## Overview
Proyecto Laravel base con autenticación y backoffice básico.

## Features
- Autenticación con Breeze
- Backoffice protegido en `/admin`
- Vistas públicas estándar de Laravel
- Base de datos MySQL configurada

## Installation

1. Install dependencies:
```bash
composer install
npm install
```

2. Configure environment:
```bash
cp .env.example .env
php artisan key:generate
```

3. Configure database in `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=itca_laravel
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

4. Run migrations:
```bash
php artisan migrate
```

5. Install Breeze:
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build
```

## Usage

- Public site: `/`
- Login: `/login`
- Register: `/register`
- Admin: `/admin` (requires authentication)

## Development

Start the development server:
```bash
php artisan serve
```

Build assets:
```bash
npm run dev
```
