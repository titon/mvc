# Engine #

An engine is used during the rendering process of templates. Every instance of the engine represents a single template.

Begin by creating a template. Any child templates will be present in getContent().

```php
<div><?php echo $this->getContent(); ?></div>
```

One can wrap templates using a wrapper. Just call wrapWith() at the top of the template.

```php
$this->wrapWith('sidebar'); // one wrapper
$this->wrapWith(['sidebar', 'body']); // multiple wrappers
```

One can also change the parent layout using useLayout().

```php
$this->useLayout('default');
```

If a helper was defined in the view instance, they can be accessed using their alias.

```php
<div><?php echo $this->html->anchor('This is a link', '/url'); ?></div>
```