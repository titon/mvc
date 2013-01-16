# Action #

An action represents shared logic that can be re-used within controllers.

```php
use Titon\Mvc\Action\AbstractAction;

class FormAction extends AbstractAction {
	public function run() {
		// Do logic
	}
}
```

One then runs the action through the controller action.

```php
use Titon\Mvc\Controller\AbstractController;

class PostsController extends AbstractController {
	public function add() {
		$this->runAction(new FormAction());
	}

	public function edit() {
		$this->runAction(new FormAction());
	}
}
```