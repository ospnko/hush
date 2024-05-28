# Introduction

The package to build dashboards for PHP web applications with zero dependencies.

## Getting started

### Installation

1. `composer require ospnko/hush`
2. Create a symlink to the `asset` directory to be available for the users browsers

### Creating a page

The entry point for all functionality is the `HushBuilder` class. It provides methods to work with almost every package component.

Every page starts with the layout. To configure the layout you need to call the `->layout()` method:

```php
<?php

use Ospnko\Hush\HushBuilder;

$hush = new HushBuilder();

$hush->layout(
    csrfToken: '<app csrf token if exists, otherwise provide an empty string>',
    cssPath: '<http path to the app.css from the package\'s asset/css directory>',
    cssColorsPath: '<http path to the dashboard theme from the package\'s asset/css directory>',
    faviconPath: '<path to desired favicon>',
    faviconType: '<favicon type>',
    fontsPath: '<http path to the asset/fonts directory>',
    jsPath: '<http path to the asset/js directory>',
    locale: '<app\'s for the HTML lang tag>',
    title: '<current page title>',
);
```

To render the page you need to call method `->render()` it will produce HTML, which you need to send to the user.

The given example'll produce an empty page for now.

As a building unit Hush uses **Blocks**. Block groups the components into meaningful units described by its title. Let's add one:

```php
<?php

// ...layout definition here

$hush->block(
    headline: 'Greeting',
    content: fn (HushBuilder $hush) => $hush->text('Greeting text'),
);

$hush->render();
```

Now there's a greeting text on the page. As you can see the block's content also uses the `HushBuilder` class, so it allows to keep adding the components into it.

There're not a lot of restrictions here for a moment, but would certainly be a bad idea to call `->layout()` or `->block()` methods from a `->block`.
