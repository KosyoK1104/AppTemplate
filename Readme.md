# White Framework
White Framework is a PHP-based starting framework developed to explore and deepen understanding of PHP Standards Recommendations (PSRs) and other foundational concepts in modern PHP development. It is intentionally minimal and designed to adhere to best practices, making it a great educational and practical tool for honing skills in creating scalable and maintainable PHP applications.

## Goals of White Framework

1 Learn and Apply PSRs: The framework serves as a hands-on project to implement various PSRs, including:
- PSR-1: Basic coding standards.
- PSR-4: Autoloading standards for organizing and loading classes.
- PSR-7: HTTP message interfaces.
- PSR-12: Extended coding style guidelines.
- And more, depending on the project’s scope.
2. Experimentation: Provides a safe environment to experiment with advanced PHP concepts like dependency injection, middleware, and design patterns.
3. Foundation for Future Projects: Acts as a reusable starting point for new PHP applications, providing a clean structure and helpful tools.

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

