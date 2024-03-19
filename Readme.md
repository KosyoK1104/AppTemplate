# App Template

## Used resources

- [Dependency Injection Container](https://container.thephpleague.com/4.x/) 
- [Migrations](https://book.cakephp.org/migrations/3/en/index.html)
- [Routes](https://route.thephpleague.com/5.x/)

## Docker initialization

### Requirements:

- Docker
- PHP 8.1
- Composer

### Install

1. Install MariaDB and phpMyAdmin in Docker
2. Run `composer install`
3. Run `docker-compose up -d`

## App

### Install

1. Create database with phpMyAdmin
2. Copy `.env.template` to `.env`
3. Fill `DB_HOST` with the host of your database
4. Fill `DB_NAME` with the name of the database
5. Fill `DB_USERNAME` and `DB_PASSWORD` with the MariaDB credentials

