# Introduction

The package to build dashboards for PHP web applications with zero dependencies.

## Getting started

### Installation

1. `composer require ospnko/hush`
2. Create a symlink to the `asset` directory to be available for the browser

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
    locale: '<app\'s locale for the HTML lang tag>',
    title: '<current page title>',
);
```

To render the page you need to call method `->render()` it will produce HTML, which you need to send to the user.

The given example produces an empty page for now.

As a building unit Hush uses **Blocks**. Block groups the components into meaningful units described by its title. Let's add one:

```php
// ...layout definition here

$hush->block(
    headline: 'Greeting',
    content: fn (HushBuilder $hush) => $hush->text('Greeting text'),
);

$hush->render();
```

Now there's a greeting text on the page. As you can see the block's content also uses the `HushBuilder` class, this way it allows to keep adding components into it.

There aren't a lot of restrictions for a moment, but would certainly be a bad idea to call `->layout()` or `->block()` methods from a `->block()`.

## Documentation

### Attributes

A lot of components support the `$attributes` param, it allows to specify custom HTML attributes as associative array.

Example:

```php
attributes: ['class' => 'my-class', 'id' => 'my-id']
```

### Block

Block is the building unit of Hush interface.

Example:

```php
// this one produces block without the headline
$hush->block(
    content: fn (HushBuilder $hush) => $hush->text('Hello there'),
);

// you can add heading inside the content (useful when it gets more functionality then just displaying text)
$hush->block(
    content: fn (HushBuilder $hush) => $hush
        ->heading('Greeting')
        ->text('Hello there'),
);

$hush->block(
    headline: 'Greeting',
    content: fn (HushBuilder $hush) => $hush->text('Hello there'),
    attributes: ['style' => 'padding: 200px;'],
);
```

### Breadcrumbs

Breadcrumbs component provides useful links tree for the user.

Example:

```php
use Ospnko\Hush\Component\Breadcrumb\Breadcrumb;

$hush->block(
    content: fn (HushBuilder $hush) => $hush->breadcrumbs(
        fn (Breadcrumb $breadcrumb) => $breadcrumb
            ->addItem('Home', '/dashboard')
            ->addItem('Products', '/dashboard/products'))
            ->addItem('Edit product'),
    ),
);
```

### Button

It looks like a button, it behaves like a button, but be careful it's not always represented by the `<button>` HTML attribute, sometimes it's the `<a>` (mostly when the `$link` param is provided).

Examples:

```php
$hush->button(
    content: 'Submit',
);

$hush->button(
    link: '/dashboard',
    content: 'Home',
);

$hush->button(
    link: '/dashboard',
    content: fn (HushBuilder $hush) => $hush->text('Home'),
    attributes: ['class' => 'my-button'],
);
```

### Control structures

#### If

Just a builder method for `if` statement.

Example:

```php
// instead of this
$hush->text('Something');

if ($isTrue) {
    $hush->text('Something additional');
}

// you can do this
$hush
    ->text('Something')
    ->if($isTrue, fn (HushBuilder $hush) => $hush->text('Something additional'));
```

#### Loop

Just a builder method for `foreach` statement.

Example:

```php
// instead of this
$hush->text('Row #1');

foreach ([1, 2, 3] as $rowNumber) {
    $hush->text('Row #' . $rowNumber);
}

// you can do this
$hush
    ->text('Row #1')
    ->loop(
        row: [1, 2, 3],
        content: fn (HushBuilder $hush, int $rowNumber) => $hush
            ->text('Row #' . $row),
        attributes: [ // loop wraps its content into <div> which may have attributes
            'class' => 'my-looped-content'
        ],
    );
```

### Flex

It's a wrapper for `<div style="display: flex">`, it allows you to split content into columns using only PHP.

Example:

```php
$hush->flex(
    // applied to the parent <div>
    attributes: ['style' => 'gap: 16px;'],
    // applied to the flex item
    childAttributes: [
        'leftBlock' => ['style' => 'color: #000'],
        'rightBlock' => ['style' => 'color: #fff'],
    ],
    // all below param names are at your discretion
    leftBlock: fn (HushBuilder $hush) => $hush->block(),
    rightBlock: fn (HushBuilder $hush) => $hush->block(),
);
```

### Form

Just a simple `<form>`.

Example:

```php
$hush->form(
    method: 'POST',
    action: '/dashboard/products',
    content: fn (HushBuilder $hush) => $hush->block(),
    csrfField: '<you csrf field if exists>',
    attributes: ['class' => 'my-form'],
);
```

### Heading

Just a simple `<h1>`.

Example:

```php
$hush->heading(
    text: 'Heading',
    attributes: ['class' => 'my-heading'],
);
```

### HTML

Allows you to paste raw html.

Example:

```php
$hush->html(<<<HTML
    <div class="my-div">Text</div>
HTML);
```

### Image

Just a simple `<img>`.

Example:

```php
$hush->image(
    link: '...',
    alt: '...',
    attributes: ['class' => 'my-image'],
);
```

### Inputs

#### Checkbox

A bit modified version of `<input type="checkbox">`.

Example:

```php
$hush->checkbox(
    label: 'My checkbox',
    name: 'my_checkbox',
    isChecked: true,
    value: 'my_value',
    attributes: ['class' => 'my-class'],
);
```

#### File

A bit modified version of `<input type="file">`.

Supports also `multiple` by passing `isMultiple: true`.

Supports async loading - it's when the file is loaded to server straight after selection, on form submission backend receives only a path of it. Input becomes async if `asyncUrl` is provided.

It sends `POST` request to the url from `asyncUrl` with the field `file` in the body which contains binary representation of the file. And it expects to receive JSON with fields `mime` and `path`.

You could also provide an additional HTML to display something before the input itself, it might be useful for inputs which work with certain kind of files. Keep it in mind that for async fields you most probably need to write frontend logic yourself.

Example of sync singular file input:

```php
$hush->inputFile(
    name: 'my_file',
    value: '/my-image.jpg',
    placeholder: 'My placeholder',
    isMultiple: false,
    errors: ['Error #1'],
    attributes: ['class' => 'my-class'],
    mainInputAttributes: [],
);
```

Example of sync multiple file input:

```php
$hush->inputFile(
    name: 'my_file',
    value: ['/my-image.jpg'],
    placeholder: 'My placeholder',
    isMultiple: true,
    errors: ['Error #1'],
    attributes: ['class' => 'my-class'],
    mainInputAttributes: [],
);
```

Example of async multiple file input:

```php
$hush->inputFile(
    name: 'my_file',
    value: ['/my-image.jpg'],
    placeholder: 'My placeholder',
    isMultiple: true,
    asyncUrl: '/api/v1/image',
    upperContent: <<<HTML
        <img src="/my-image.jpg" alt="">
    HTML,
    errors: ['Error #1'],
    attributes: ['class' => 'my-class'],
    mainInputAttributes: [],
);
```

#### File (image)

Special input for the file input which supports only images.

Example:

```php
$hush->inputImage(
    name: 'my_image',
    image: '/my-image.jpg',
    placeholder: 'My placeholder',
    asyncUrl: '',
    errors: ['Error #1'],
);
```

#### File (image multiple)

Same as the previous one but supports multiple images.

Param `deleted` is used to provided paths of images that need to mark for deletion (becomes useful when need to ask user to fix validation errors before processing).

Supports reordering feature. When enabled provides controls to manage position of the uploaded images. Works only with async uploading.

Example:

```php
$hush->inputImageMultiple(
    name: 'my_images',
    images: ['/my-image.jpg'],
    deleted: ['/my-image.jpg'],
    placeholder: 'My placeholder',
    asyncUrl: '',
    isReorderingEnabled: true,
    errors: ['Error #1'],
);
```

#### File (media)

Almost the same as `File (image)`, but allows to upload videos too.

Example:

```php
$hush->inputMedia(
    name: 'my_media',
    media: '/my-video.mp4',
    isVideo: false, // yeah, you should check this manually
    placeholder: 'My placeholder',
    asyncUrl: '',
    errors: [],
);
```

#### Input

Just a simple `<input>`.

Example:

```php
$hush->input(
    type: 'text',
    name: 'my_input',
    placeholder: 'My placeholder',
    value: 'My value',
    isRequired: true,
    attributes: ['class' => 'my-class'],
    errors: ['Error #1', 'Error #2'],
);
```

#### Input (search)

Kind of input which has some custom functionality like the button to submit the form above itself and the button to clear itself.

Example:

```php
$hush->inputSearch(
    name: 'my_search',
    placeholder: 'My search',
    value: '',
    attributes: ['class' => 'my-search'],
);
```

#### Select

Slightly modified version of classic `<select>`.

It support searching, to enable it you should just pass `isSearchable: true` + `searchPlaceholder` if you need.

It also supports the async search - when rows are uploaded from backend dynamically based on search value. It sends `GET` request on the provided URL with params `query` which contains value of the search field and `selected` which supports value of currently selected element. It expects to receive JSON response with array of objects containing fields `text` and `value`.

Example:

```php
$hush->select(
    name: 'my_select',
    options: ['red' => 'Red', 'green' => 'Green', 'blue' => 'Blue'],
    value: 'green',
    errors: [],
    placeholder: 'My placeholder',
    searchPlaceholder: 'Search',
    isRequired: true,
    isSearchable: true,
    asyncSearchUrl: '',
);
```

#### Select (multiple)

Kind of select which allows to select multiple values.

Example:

```php
$hush->selectMultiple(
    name: 'my_select',
    options: ['red' => 'Red', 'green' => 'Green', 'blue' => 'Blue'],
    values: ['red', 'green'],
    errors: [],
    placeholder: 'My select',
    searchPlaceholder: 'Search',
    isRequired: true,
    isSearchable: true,
);
```

#### Textarea

Just a simple `<textarea>`.

Example:

```php
$hush->textarea(
    name: 'my_textarea',
    value: '',
    placeholder: 'My textarea',
    errors: [],
    attributes: [],
);
```

### Errors

Displays small red text. Mostly used to indicate validation errors.

Example:

```php
$hush->errors(['Error #1', 'Error #2']);
```

### Label

Just a simple `<label>`.

Example:

```php
$hush->label(
    content: 'Label #1',
    attributes: ['class' => 'my-label'],
);

$hush->label(
    content: fn (HushBuilder $hush) => $hush->text('Label #2'),
);
```

### Layout

Sets up the application layout.

Example:

```php
$hush->layout(
    csrfToken: '',
    cssPath: '/asset/css/app.css',
    cssColorsPath: '/asset/css/light.css',
    faviconPath: '/favicon.png',
    faviconType: 'image/png',
    fontsPath: '/asset/fonts',
    jsPath: '/asset/js',
    locale: 'en',
    title: 'My awesome dashboard',
    customEndHeadHtml: '<script src="/my.js"></script>',
    customEndBodyHtml: '<script src="/my-another.js"></script>',
);
```

### Link

Just a simple `<a>`.

Example:

```php
$hush->link(
    link: '/dashboard/products',
    content: 'Products',
    attributes: ['class' => 'my-link'],
);

$hush->link(
    link: '/dashboard/products',
    content: fn (HushBuilder $hush) => $hush->heading('Products'),
);
```

### Menu

Creates a horizontal menu. Has 2 styling options:

- **islands** - when buttons exist like separate islands
- **sticky** - when buttons stick to the block below menu

Example:

```php
use Ospnko\Hush\Component\Menu\Menu;

$hush->menu(
    attributes: ['class' => 'sticky'], // makes it sticky, remove to use islands
    content: fn (Menu $menu) => $menu
        ->addItem(
            link: '/dashboard/orders',
            text: 'Orders',
            isActive: false, // marks element as active if true
        )
        ->addItem(
            text: 'Stock',
            isActive: true,
            submenu: fn (Menu $menu) => $menu // it can have only 1 level of submenu for now
                ->addItem(
                    link: '/dashboard/products',
                    text: 'Products',
                    isActive: true,
                )
        )
);
```

### Modal

Inserts the modal on the page which is opened by default.

Example:

```php
$hush->modal(
    title: 'My modal',
    content: fn (HushBuilder $hush) => $hush
        ->text('My modal text'),
    footer: fn (HushBuilder $hush) => $hush
        ->button('Cancel')
        ->button('Submit'),
);
```

### Pagination

Just a simple pagination element.

Example:

```php
$hush->pagination(
    currentPage: 1,
    pages: 10, // total amount of pages
    baseLink: '/dashboard/products', // link to the current page without GET params
    paramName: 'page', // GET param name to send clicked page number
    params: ['search' => 'phone'], // custom GET params which should be sent on backend too
);
```

### Style

Just a simple `style`.

Example:

```php
$hush->style(<<<CSS
    .element {
        color: #000;
    }
CSS);
```

### SVG

There's an enum for SVGs - `Ospnko\Hush\Enum\Svg`, it provides list of default SVGs.

Example:

```php
$hush->html( // you can insert them using ->html
    Svg::Warning->render(),
);
```

### Table

Serves to build tables.

You should specify columns and actions (actions are little buttons in 1 additional table column if specified).

There're couple of hidden JS snippets to work with tables.

- href = `#hush-table-row-add` will add a new row to the table from it's `data-target=<selector>` with content from `data-template=<html>`
- href = `#hush-table-row-drop` will drop a row from the table to which it belongs.

Example:

```php
use Ospnko\Hush\Component\Table\Table;

$hush->table(
    attributes: [ // table uses CSS grid, so you need to manually adjust column sizes
        'style' => 'grid-template-columns: auto auto max-content',
    ],
    rows: [ // data that we need to display
        ['id' => 1, 'name' => 'Item #1'],
        ['id' => 2, 'name' => 'Item #2'],
    ],
    table: fn (Table $table) => $table
        ->addColumn(
            header: '#',
            content: fn (array $row) => $row['id'],
        )
        ->addColumn(
            header: 'Name',
            content: fn (array $row, HushBuilder $hush) => $hush
                ->link(
                    content: $row['name'],
                    link: '/dashboard/products/' . $row['id']
                )
        )
        ->addAction(
            icon: Svg::Edit->render(), // icon expects HTML
            note: 'Edit',
            link: fn (array $row) => '/dashboard/products/' . $row['id'] . '/edit',
        )
        ->addAction(
            icon: Svg::Delete->render(),
            note: 'Delete',
            link: fn (array $row) => '/dashboard/products/' . $row['id'] . '/delete',
            isAsyncModal: true, // means that action expects modal HTML from backend
        )
);
```

### Text

Just a simple `<p>`.

Example:

```php
$hush->text(
    text: 'My text',
    attributes: ['class' => 'my-class'],
);
```

### Video

Just a simple `<video>`.

Example:

```php
$hush->video(
    source: '/my-video.mp4',
    attributes: ['controls' => ''],
);
```
