<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc;

use Titon\Mvc\Module;
use Titon\Mvc\Dispatcher;
use Titon\Mvc\Dispatcher\FrontDispatcher;
use Titon\Common\Registry;
use Titon\Common\Traits\Instanceable;
use Titon\Route\Router;

/**
 * The Application object acts as the hub for the entire HTTP dispatch cycle and manages all available modules.
 * When triggered, it will dispatch the request to the correct module, controller and action.
 */
class Application {
	use Instanceable;

	/**
	 * Enforce singleton.
	 *
	 * @var bool
	 */
	public static $singleton = true;

	/**
	 * List of modules.
	 *
	 * @var \Titon\Mvc\Module[]
	 */
	protected $_modules = [];

	/**
	 * Dispatcher instance.
	 *
	 * @var \Titon\Mvc\Dispatcher
	 */
	protected $_dispatcher;

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
	 * @static
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
	 * @static
	 */
	public function getModules() {
		return $this->_modules;
	}

	/**
	 * @todo
	 */
	public function run() {
		$route = Router::current();
		$request = Registry::factory('Titon\Http\Request');
		$response = Registry::factory('Titon\Http\Response');
		$output = null;

		try {
			$dispatcher = $this->getDispatcher();
			$dispatcher->setApplication($this);
			$dispatcher->setParams($route->getParams());
			$dispatcher->setRequest($request);
			$dispatcher->setResponse($response);

			$output = $dispatcher->dispatch();

		} catch (\Exception $e) {
			// HANDLE ERRORS
		}

		$response->body($output)->respond();
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