# Minify

A simple package to minify CSS/SCSS and Javascript on the fly without the need of tools like Laravel Mix or Webpack.
It combines all stylesheet files or javascript files into a single, minified file with simpel but effective cachebusting with filemtime()

## Installation

Begin by installing this package through Composer.

```composer require nickdekruijk/minify```

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
