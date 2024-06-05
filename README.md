## Getting Started

### Instalation

Please check the official laravel installation guide for server requirements before you start. Official Documentation

Alternative installation is possible without local dependencies relying on Docker.

Clone the repository

```bash
git clone git@github.com:gothinkster/laravel-realworld-example-app.git
```

Switch to the repo folder

```bash
cd laravel-realworld-example-app
```

Install all the dependencies using composer

```bash
composer install
```

Copy the example env file and make the required configuration changes in the .env file

```bash
cp .env.example .env
```

Generate a new application key

```bash
php artisan key:generate
```

Run the database migrations (Set the database connection in .env before migrating)

```bash
php artisan migrate
```

Start the local development server

```bash
php artisan serve
```

You can now access the server at http://localhost:8000

### Database seeding

Populate the database with seed data with relationships which includes users, articles, comments, tags, favorites and follows. This can help you to quickly start testing the api or couple a frontend and start using it with ready content.

Run the database seeder and you're done

```bash
php artisan db:seed
```

Note : It's recommended to have a clean database before seeding. You can refresh your migrations at any point to clean the database by running the following command

```bash
php artisan migrate:refresh
```

### Docker (Sail)

To run this web app in docker using sail you need to install Docker Desktop first
https://www.docker.com/products/docker-desktop/

Run this command to start docker container

```bash
./vendor/bin/sail up -d
./vendor/bin/sail php artisan key:generate
./vendor/bin/sail php artisan migrate
./vendor/bin/sail php artisan db:seed
```

The api can be accessed at http://localhost/api/v1.

The admin panel can be access at http://localhost/admin

The PhpMyAdmin can be accessed at http://localhost:8080

### Authontication

To be able to access admin panel you need to use this credentials:

```bash
Email: admin@gmail.com
Password: 123456
```

### Testing

To verify that all work as expected you can run tests

```bash
php artisan test
```

### PhpMyAdmin

To be able to access PhpMyAdmin panel you need to use credentials from .env file. DB_USERNAME and DB_PASSWORD
