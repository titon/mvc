# Dispatcher #

A dispatcher is used to handle the dispatch cycle. It receives the current route, request, response and attempts to determine the correct module, controller and action to render. The default `FrontDispatcher` is used when no dispatcher is defined.

To define a custom dispatcher, extend the `AbstractDispatcher` and implement the `dispatch()` method.

```php
class CustomDispatcher extends \Titon\Mvc\Dispatcher\AbstractDispatcher {
	public function dispatch() {
		// Handle cycle
	}
}
```

Then tell the application to use it.

```php
$app->setDispatcher(new CustomDispatcher());
```