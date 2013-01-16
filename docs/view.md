# View #

The view handles the management of template paths, data variables, helpers and rendering engines.

```php
$view = new Titon\Mvc\View();
$view->addPath('/views/');
```

Variables can be set that can be accessed with templates.

```php
$view->setVariables([
	'posts' => $posts,
	'pageTitle' => 'Latest Posts'
]);
```

And helpers can be defined to add advanced functionality to templates.

```php
$view->addHelper('html', new Titon\Mvc\Helper\Html\HtmlHelper());

// Can be accessed in the template as
$this->html;
```

Before a template can be rendered, a rendering engine is required.

```php
$view->setEngine(new Titon\Mvc\Engine\ViewEngine());
```

And finally, a template can be rendered.

```php
// Render a single template
$view->render('/file/path.tpl');

// Render a template with layout and wrappers
$view->run('file');
```