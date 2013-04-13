# Controller #

One can use controllers to define logic that handles specific page requests.

```php
use Titon\Mvc\Controller\AbstractController;

class UsersController extends AbstractController {
	public function login() {
		// Do logic
	}
}
```

Once a controller and action have been determined from the current URL (the Route package handles this), the action can be triggered.

```php
$controller = new UsersController();
$controller->dispatchAction($action, $arguments);
```

To improve upon this process flow, callbacks can be triggered.

```php
$controller = new UsersController();
$controller->preProcess();
$controller->dispatchAction($action, $arguments);
$controller->postProcess();
```

If a view and engine have been defined, one can generate an output using templates. The renderView() method will need to be defined.

```php
// In Controller::initialize()
$view = new Titon\Mvc\View();
$view->addPath('/views/');
$view->setEngine(new Titon\Mvc\Engine\ViewEngine());

$this->setView($view);

// Outside of controller
$output = $controller->renderView();
```

Read the view and engine docs for more information.