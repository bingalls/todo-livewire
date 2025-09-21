<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Todo-Livewire

`To Do` is the classic `Hello World` example for JavaScript frameworks. This
demo is written in Livewire, to be as Laravel / PHP as possible. State is
saved to server, unlike many other ToDo demos.

## Requirements

- Git >= 2
- Node.js >= 22
- PHP >= 8.3
- Composer >= 2.8

## Installation

```bash
cd ~
git clone https://github.com/bingalls/todo-livewire.git
cd todo-livewire
composer install
npm install
npm run build
cp .env .env.example
php artisan key:generate
php artisan migrate --seed
php artisan serve
open http://127.0.0.1:8000/     # MacOS to open in default browser
```

## License & Credits

`Todo-Livewire` is open-sourced software licensed under the
[MIT license](https://opensource.org/licenses/MIT).

It is made possible with the hard work & licenses from the following contributors:

- Adam Wathan
- Freek Van Der Herten & Spatie
- Jeffrey Way
- Mohammed Said
- Nuno Maduro
- Povilas Korop
- Taylor Otwell

## To Do

This demo is not intended for production.

- Todo: Filter by project
- Todo: add tests
