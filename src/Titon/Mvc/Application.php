<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc;

use Titon\Common\Registry;
use Titon\Common\Traits\Instanceable;
use Titon\Debug\Debugger;
use Titon\Controller\Controller\ErrorController;
use Titon\Event\Scheduler;
use Titon\View\View;
use Titon\View\Helper\Html\AssetHelper;
use Titon\View\Helper\Html\HtmlHelper;
use Titon\Mvc\Module;
use Titon\Mvc\Dispatcher;
use Titon\Mvc\Dispatcher\FrontDispatcher;
use Titon\Route\Router;

/**
 * The Application object acts as the hub for the entire HTTP dispatch cycle and manages all available modules.
 * When triggered, it will dispatch the request to the correct module, controller and action.
 */
class Application {
	use Instanceable;

	/**
	 * Dispatcher instance.
	 *
	 * @var \Titon\Mvc\Dispatcher
	 */
	protected $_dispatcher;

	/**
	 * List of modules.
	 *
	 * @var \Titon\Mvc\Module[]
	 */
	protected $_modules = [];

	/**
	 * Request instance.
	 *
	 * @var \Titon\Http\Request
	 */
	protected $_request;

	/**
	 * Response instance.
	 *
	 * @var \Titon\Http\Response
	 */
	protected $_response;

	/**
	 * Router instance.
	 *
	 * @var \Titon\Route\Router
	 */
	protected $_router;

	/**
	 * Set the error handler if the debug package exists.
	 */
	public function __construct() {
		$this->_router = Registry::factory('Titon\Route\Router');
		$this->_request = Registry::factory('Titon\Http\Request');
		$this->_response = Registry::factory('Titon\Http\Response');

		if (class_exists('Titon\Debug\Debugger')) {
			Debugger::setHandler([$this, 'handleError']);
		}
	}

	/**
	 * Add a module into the application.
	 *
	 * @param \Titon\Mvc\Module $module
	 * @return \Titon\Mvc\Module
	 */
	public function addModule(Module $module) {
		$module->bootstrap();

		$this->_modules[$module->getKey()] = $module;

		return $module;
	}

	/**
	 * Return the dispatcher instance. Use FrontDispatcher if none is set.
	 *
	 * @return \Titon\Mvc\Dispatcher
	 */
	public function getDispatcher() {
		if (!$this->_dispatcher) {
			$this->setDispatcher(new FrontDispatcher());
		}

		return $this->_dispatcher;
	}

	/**
	 * Return a module by key.
	 *
	 * @param string $key
	 * @return \Titon\Mvc\Module
	 * @throws \Titon\Mvc\Exception
	 */
	public function getModule($key) {
		if (isset($this->_modules[$key])) {
			return $this->_modules[$key];
		}

		throw new Exception(sprintf('Could not locate %s module', $key));
	}

	/**
	 * Return all modules.
	 *
	 * @return \Titon\Mvc\Module[]
	 */
	public function getModules() {
		return $this->_modules;
	}

	/**
	 * Return the router object.
	 *
	 * @return \Titon\Route\Router
	 */
	public function getRouter() {
		return $this->_router;
	}

	/**
	 * Return the request object.
	 *
	 * @return \Titon\Http\Request
	 */
	public function getRequest() {
		return $this->_request;
	}

	/**
	 * Return the response object.
	 *
	 * @return \Titon\Http\Response
	 */
	public function getResponse() {
		return $this->_response;
	}

	/**
	 * Default mechanism for handling uncaught exceptions.
	 * Will fetch the current controller instance or instantiate an ErrorController.
	 * The error view template will be rendered.
	 *
	 * @param \Exception $exception
	 */
	public function handleError(\Exception $exception) {
		if (class_exists('Titon\Debug\Debugger')) {
			Debugger::logException($exception);
		}

		Scheduler::dispatch('mvc.preError', [$exception]);

		try {
			$controller = Registry::get('Titon.controller');

		} catch (\Exception $e) {
			$view = new View();
			$view->addHelper('html', new HtmlHelper());
			$view->addHelper('asset', new AssetHelper());

			$controller = new ErrorController($this->getRouter()->current()->getParams());
			$controller->setView($view);
			$controller->initialize();
		}

		$controller->setRequest($this->getRequest());
		$controller->setResponse($this->getResponse());

		$response = $controller->renderError($exception);

		Scheduler::dispatch('mvc.postError', [$exception]);

		$this->getResponse()->body($response)->respond();
	}

	/**
	 * Run the application by fetching the dispatcher and dispatching the request
	 * to the module and controller that matches the current URL.
	 */
	public function run() {
		Scheduler::dispatch('mvc.preRun');

		$dispatcher = $this->getDispatcher();
		$dispatcher->setApplication($this);
		$dispatcher->setParams($this->getRouter()->current()->getParams());
		$dispatcher->setRequest($this->getRequest());
		$dispatcher->setResponse($this->getResponse());

		$response = $dispatcher->dispatch();

		Scheduler::dispatch('mvc.postRun');

		$this->getResponse()->body($response)->respond();
	}

	/**
	 * Set the dispatcher to use.
	 *
	 * @param \Titon\Mvc\Dispatcher $dispatcher
	 * @return \Titon\Mvc\Dispatcher
	 */
	public function setDispatcher(Dispatcher $dispatcher) {
		$this->_dispatcher = $dispatcher;

		return $dispatcher;
	}

}

// Define as singleton
Application::$singleton = true;