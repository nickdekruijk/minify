# Minify

A simple package to minify CSS/SCSS and Javascript on the fly without the need of tools like Laravel Mix or Webpack.
It combines all stylesheet files or javascript files into a single, minified file with simple but effective cachebusting with filemtime().

Version 2 is a completely new package ([version 1 is archived here](https://github.com/nickdekruijk/minify1)) using  [scssphp/scssphp](https://github.com/scssphp/scssphp) and [tedivm/jshrink](https://github.com/tedivm/jshrink). Because minify  now replaces natxet/cssmin with scssphp it can now compile SASS/SCSS code too!

## Installation

Begin by installing this package with composer.

`composer require nickdekruijk/minify`

## Upgrading from 1.x
When upgrading change your projects composer.json to require nickdekruijk/minify with at least version "^2.0" and run `composer update`.

If you use .gitignore to ignore the old builds in js/builds and css/builds dont' forget to remove them from your .gitignore file and delete all obsolete build .css and .js files.

You may also need to change the Minify::stylesheet and Minify::javascript calls in your code/views since pathname might change depending on your configuration.

### Laravel installation

Publish the config file if the defaults doesn't suite your needs:

```php artisan vendor:publish --provider="NickDeKruijk\Minify\MinifyServiceProvider"```

### Stylesheet

```php
// app/views/hello.blade.php
<html>
    <head>
        ...
        {!! Minify::stylesheet(['lightbox.css', 'fonts.css', 'styles.css']) !!}
    </head>
    ...
</html>

```

### Javascript

```php
// app/views/hello.blade.php

<html>
    <body>
        ...
        {!! Minify::javascript(['lazyload.min.js', 'scripts.js']) !!}
    </body>
</html>
```

### Config
See the config file at `/config/minify.php`
