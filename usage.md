# Usage #

To render view templates, you will need to use a template engine.
Be sure to add the template paths for the lookup process.

```php
$engine = new ViewEngine();
$engine->addPath('/views/')->addPath('/fallback/views/');
```

Templates should be organized in the following structure.
The public folder will contain all controller and action templates.
The private folder will contain all layouts, wrappers and miscellaneous templates.

```
views/
	private/
		layouts/
			default.tpl
		wrappers/
			example.tpl
		includes/
			partial.tpl
	public
		controller/
			action.tpl
```

You can also add helpers that can be accessed from within the views.

```php
use Titon\Mvc\Helper\Html\HtmlHelper;
use Titon\Mvc\Helper\Html\FormHelper;

$engine->addHelper('html', new HtmlHelper());
$engine->addHelper('form', new FormHelper());

// Can be accessed in the view as
$this->html->method();
$this->form->method();
```

As well as adding data that can be accessed as variables within the view.

```php
$engine->set('pageTitle', 'Latest Posts');
$engine->set('posts', $posts);

// Can be accessed in the view as
$pageTitle
$posts
```

You can render a partial view (within the includes folder) within another view, by calling open().

```php
$this->open('partial'); // private/includes/partial.tpl
$this->open('nested/folders/too'); // private/includes/nested/folders/too.tpl
```

And finally, you can render the whole view stack. By calling run(), it will render the view template,
then wrap that in a wrapper (if applicable), then wrap that in a layout (if applicable).

```php
$engine->run();
```