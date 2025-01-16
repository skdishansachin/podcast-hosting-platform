# (Work in progress)

# Podcast Hosting Platform API

This is an web application that allows users to host their podcasts. Users can create account, multiple podcasts and episodes.

## Features

- Full authentication system
- Users can schedule the release of their episodes
- Auto generate RSS feed for each podcast
- Application supports subscription to podcasts
- Users can view statistics of their podcasts

## Technologies

- Laravel
- MySQL

## Installation

1. Clone the repository

```bash
git clone https://github.com/skdishansachin/podcast-hosting-platform.git
```

2. Install dependencies

```bash
composer install
```

3. Create a `.env` file

```bash
cp .env.example .env
```

4. Generate application key

```bash
php artisan key:generate
```

5. Create a database and update the `.env` file

6. Run the migrations

```bash
php artisan migrate
```

7. Start the server

```bash
php artisan serve
```
