# A Better Embed

[![Packagist Version](https://img.shields.io/packagist/v/abetter/embed.svg)](https://packagist.org/packages/abetter/embed)
[![Latest Stable Version](https://poser.pugx.org/abetter/embed/v/stable.svg)](https://packagist.org/packages/abetter/embed)
[![Total Downloads](https://poser.pugx.org/abetter/embed/downloads.svg)](https://packagist.org/packages/abetter/embed)
[![License](https://poser.pugx.org/abetter/embed/license.svg)](https://packagist.org/packages/abetter/embed)

ABetter Laravel Embedd is a package of directives for faster development of component-based web applications, with focus on scalable static caching.

---

## Requirements

* PHP 7.3+
* Composer 1.6+
* Laravel 6.0+

---

## Installation

Via Composer:

```bash
composer require abetter/embed
```

----

## Directives

#### @style : Embedd sass/css in html source code

    @style('<relative-filename>')
	@style('menu.scss')

Embedded Sass/CSS files will be rendered as external files in development mode to support browsersync live, but will be embedded in html source on Stage/Production for better caching.

#### @script : Embedd js in html source code

    @script('<relative-filename>')
	@script('menu.js')

Embedded JS files will be rendered as external files in development mode to support browsersync live, but will be embedded in html source on Stage/Production for better caching.

#### @svg : Embedd svg in html source code

    @svg('<filename-relative-to-resources>')
	@svg('/images/logo.svg')

---

# Contributors

[Johan Sjöland](https://www.abetterstory.com/]) <johan@sjoland.com>  
Senior Product Developer: ABetter Story Sweden AB.

## License

MIT license. Please see the [license file](LICENSE) for more information.
