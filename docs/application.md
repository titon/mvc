# Application #

The Application handles the bulk of application management logic. It loads modules (self-contained modular applications), manages request and response objects, handles routing and dispatches the request.

Begin by starting the application and loading modules.

```php
$app = Titon\Mvc\Application::getInstance();
$app->addModule(new BlogModule('blog', '/modules/blog/'));
$app->addModule(new ForumModule('forum', '/modules/forum/'));
```

The current router, request and response can be accessed through the app. These instances will be passed down to the dispatcher, the module and controller.

```php
$app->getRouter();
$app->getRequest();
$app->getResponse();
```

Dispatch the request after all modules are loaded.

```php
$app->run();
```

The module, controller and action to dispatch to will be determined by the current URL and the parsed router parameters. Any failures along this cycle will throw an exception that will trigger and render error templates.