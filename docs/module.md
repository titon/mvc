# Module #

A module represents a self-contained modular application. Each module will have its own set of controllers, actions, views, resources, libraries and a webroot (for assets). A module should be re-usable and droppable in any application. An example of a module would be a blog or forum system.

Each module is wrapped within a folder and should contain a class extended from `AbstractModule`. A `bootstrap()` method can be defined to bootstrap the module when it is loaded into an application.

```php
namespace Forum;

use Titon\Common\Config;
use Titon\Mvc\Module\AbstractModule;

class ForumModule extends AbstractModule {
	public function bootstrap() {
		$router = Registry::factory('Titon\Route\Router');
		// Add routes

		Config::set('Forum', array());
		// Add config

		// Do anything else
	}
}
```

A typical module folder structure is as follows. Each library should be placed into a folder that represents its parent namespace or package.

```
Forum/
	Action/
	Controller/
	Engine/
	Helper/
	Model/
		Entity/
	resources/
		configs/
		messages/
	views/
		public/
		private/
	web/
		css/
		img/
		js/
	ForumModule.php
```