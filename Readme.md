# CSCB532-Project

## Used resources

- Dependency Injection Container(https://container.thephpleague.com/4.x/)
- Migrations (https://book.cakephp.org/migrations/3/en/index.html)
- Routes (https://route.thephpleague.com/5.x/)

## Architecture

- Presentation(React maybe) Layer
- Application(Service) Layer
- Domain Layer
- Persistence Layer
- Database

## Docker initialization

### Requirements:

- Docker
- PHP 8.1
- Composer

### Install

1. Install MariaDB and phpMyAdmin in Docker
   (https://migueldoctor.medium.com/run-mariadb-phpmyadmin-locally-in-3-steps-using-docker-6b5912ff37c9)
2. In project directory run through the console ```.\build.bat```
3. In project directory run through the console ```.\run.bat```

## App

### Install

1. Create database with phpMyAdmin
2. Copy ```.env.template``` to ```.env```
3. Fill DB_HOST=host.docker.internal
4. Fill DB_NAME with the database name
5. Fill DB_USERNAME and DB_PASSWORD with the MariaDB credentials
6. Run ```composer install``` in the console
