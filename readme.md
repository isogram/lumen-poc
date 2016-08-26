# Lumen PoC
This is PoC of Lumen with Laravel components version 5.2.*  
If you like to find documentation for Lumen or Laravel please go to  [here](https://lumen.laravel.com/docs/5.2).  

## What's Added
- [Dingo API](https://github.com/dingo/api)
- [JWT Auth](https://github.com/tymondesigns/jwt-auth)
- [Redis](https://github.com/illuminate/redis)
- [Mailer Support](https://github.com/illuminate/mail)

## Quick Start
- Clone this repo
- Run `composer install`
- Configure your `.env` file for database usage
- Run `php artisan migrate --seed`

## Read These Files for More Information

```sh
app/helpers.php
app/Auth/ApiGuard.php
app/Api/v1/routes.php
app/Api/v1/AuthController.php
app/Http/Controllers/ApiController.php
app/Http/Middleware/GetUserFromToken.php
app/Http/Middleware/RateLimit.php
app/Http/Middleware/RefreshToken.php
app/Http/Throttle/MyThrottle.php
app/Models/*
app/Serializer/DataSerializer.php
app/Transformer/*
bootstrap/app.php
config/*
database/seeds/PartnerUserTableSeeder.php
public/.htaccess
```
