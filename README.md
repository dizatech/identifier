# Laravel Identifier Package :: simple authentication
[![Latest Version on Packagist](https://img.shields.io/packagist/v/dizatech/identifier.svg?style=flat-square)](https://packagist.org/packages/dizatech/identifier)
[![GitHub issues](https://img.shields.io/github/issues/dizatech/identifier?style=flat-square)](https://github.com/dizatech/identifier/issues)
[![GitHub stars](https://img.shields.io/github/stars/dizatech/identifier?style=flat-square)](https://github.com/dizatech/identifier/stargazers)
[![GitHub forks](https://img.shields.io/github/forks/dizatech/identifier?style=flat-square)](https://github.com/dizatech/identifier/network)
[![Total Downloads](https://img.shields.io/packagist/dt/dizatech/identifier.svg?style=flat-square)](https://packagist.org/packages/dizatech/identifier)
[![GitHub license](https://img.shields.io/github/license/dizatech/identifier?style=flat-square)](https://github.com/dizatech/identifier/blob/master/LICENSE)

Laravel Identifier Package :: simple authentication (login, register and forgot-password).

## How to install and config [dizatech/identifier](https://github.com/dizatech/identifier) package?

#### <g-emoji class="g-emoji" alias="arrow_down" fallback-src="https://github.githubassets.com/images/icons/emoji/unicode/2b07.png">⬇️</g-emoji> Installation

```bash
composer require dizatech/identifier
```

#### Publish Config file

```bash
php artisan vendor:publish --tag=dizatech_identifier
```

- Update (Be careful! Overwrites existing settings)

```bash
php artisan vendor:publish --tag=dizatech_identifier --force
```

#### Migrate tables, to add identifier tables to database

```bash
php artisan migrate
```

#### <g-emoji class="g-emoji" alias="book" fallback-src="https://github.githubassets.com/images/icons/emoji/unicode/1f4d6.png">📖</g-emoji> How to change auth options

- Set the configs in /config/dizatech_identifier.php

## Usage

- Create resources/sass/auth.scss file and add the following code :

```scss
// Fonts
@import './fonts/awesome/awesome-font.css';
@import './fonts/iransans/iransans-font.css';

@import "./vendor/dizatech-identifier/dizatech_identifier";
```

* Please note that fonts directories is up to your project structure. change them with your own directories.

- Create resources/js/auth.js file and add the following code :

```js
require('./bootstrap');

require("./vendor/dizatech-identifier/dizatech_identifier");
```

- Add created files directly in your webpack.mix.js

```bash
.js('resources/js/auth.js', 'public/js')
    .sass('resources/sass/auth.scss', 'public/css')
```

- run npm :

```bash
npm run dev
```

- Use this route to redirect your users to login and registration page

```php
route('identifier.login');
```

- Change `app/Http/Middleware/Authenticate.php` like this :

```php
protected function redirectTo($request)
{
    if (! $request->expectsJson()) {
        return route('identifier.login');
    }
}
```

- Clear caches

```bash
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear
```

- Done !

###Requirements :

- PHP v7.0 or above
- Laravel v7.0 or above
- dizatech/notifier package [packagist link](https://packagist.org/packages/dizatech/notifier)
- va/cutlet-helper package [packagist link](https://packagist.org/packages/va/cutlet-helper)