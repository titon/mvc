<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc\Dispatcher;

use Titon\Mvc\Application;
use Titon\Mvc\Dispatcher;
use Titon\Mvc\Exception;
use Titon\Common\Base;
use Titon\Common\Registry;
use Titon\Http\Request;
use Titon\Http\Response;
use Titon\Route\Router;

/**
 * The Dispatcher handles the generation of the response output.
 * It must locate the correct module and controller, and pass the current request, response and parameters.
 */
abstract class AbstractDispatcher extends Base implements Dispatcher {

	/**
	 * Application instance.
	 *
	 * @var \Titon\Mvc\Application
	 */
	protected $_app;

	/**
	 * Request parameters.
	 *
	 * @var array
	 */
	protected $_params;

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
	 * Get the application.
	 *
	 * @return \Titon\Mvc\Application
	 */
	public function getApplication() {
		return $this->_app;
	}

	/**
	 * Return the controller instance.
	 *
	 * @return \Titon\Mvc\Controller
	 */
	public function getController() {
		$module = $this->getModule();
		$namespace = $module->getController($this->getParam('controller'));

		$controller = new $namespace($this->getParams());
		$controller->setRequest($this->getRequest());
		$controller->setResponse($this->getResponse());
		$controller->setModule($module);

		// Save the instance for later use
		Registry::set($controller, 'Titon.controller');

		return $controller;
	}

	/**
	 * Return the module instance.
	 *
	 * @return \Titon\Mvc\Module
	 */
	public function getModule() {
		return $this->getApplication()->getModule($this->getParam('module'));
	}

	/**
	 * Return a parameter by key.
	 *
	 * @param string $key
	 * @return mixed
	 * @throws \Titon\Mvc\Exception
	 */
	public function getParam($key) {
		if (isset($this->_params[$key])) {
			return $this->_params[$key];
		}

		throw new Exception(sprintf('Param %s does not exist', $key));
	}

	/**
	 * Return all parameters.
	 *
	 * @return array
	 */
	public function getParams() {
		return $this->_params;
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
	 * Set the application.
	 *
	 * @param \Titon\Mvc\Application $app
	 * @return \Titon\Mvc\Dispatcher
	 */
	public function setApplication(Application $app) {
		$this->_app = $app;

		return $this;
	}

	/**
	 * Set parameters.
	 *
	 * @param array $params
	 * @return \Titon\Mvc\Dispatcher
	 */
	public function setParams(array $params) {
		$this->_params = $params;

		return $this;
	}

	/**
	 * Set the request object.
	 *
	 * @param \Titon\Http\Request $request
	 * @return \Titon\Mvc\Dispatcher
	 */
	public function setRequest(Request $request) {
		$this->_request = $request;

		return $this;
	}

	/**
	 * Set the response object.
	 *
	 * @param \Titon\Http\Response $response
	 * @return \Titon\Mvc\Dispatcher
	 */
	public function setResponse(Response $response) {
		$this->_response = $response;

		return $this;
	}

}