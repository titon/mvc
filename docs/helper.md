# Helper #

A helper provides encapsulated functionality to templates. A helper must be added into the view engine.

```php
$view->addHelper('html', new Titon\Mvc\Helper\Html\HtmlHelper());
```

And then accessed in the template.

```php
echo $this->html->anchor('Link');
```