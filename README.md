# Lumen API,

Lumen-Api's simple api app service.
This is a combination of Lumen Framwork and Laravel Passport.

##Installation prerequisites

-   PHP 7 + , MYSQL, and Apache.
-   Composer
-   Lumen framrowk
-   Laravel Passport
-   Postman, cUrl, Api Testing

##How to settup the project you clone

-   cd lumen-api
-   composer install
-   coppy (window) cp (macos) .env.example .env
-   php artisan key:generate

##How to run

-   create new a database on locallhost
-   open .env file on the project setup enviroment configuration
    -   DB_DATABASE = database name
    -   DB_USERNAME = database user name
    -   DB_PASSWORD = database password

-Migrate the database - `php artisan migrate` - `php artisan passport:install` - `php artisan db:seed` - `php artisan cache:clear`

##Installed routes - `php artisan route:list`

##Testing Api
    ```
    *create new account*
    /auth/login

    *login to get access token and refresh token*
    /auth/login

    *show all products*
    /api/v1/product - GET

    *add new product*
    /api/v1/product - POST

    *show the product*
    /api/v1/product/{id} - GET

    *update the product*
    /api/v1/product/{id} - POST

    *delete*
    /api/v1/product/{id} - DELETE
    ```

## Official Documentation

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

## Contributing

Thank you for considering contributing to Lumen! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Lumen, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Lumen framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
