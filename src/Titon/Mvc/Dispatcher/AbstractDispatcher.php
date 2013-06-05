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
 *
 * @package Titon\Mvc\Dispatcher
 */
abstract class AbstractDispatcher extends Base implements Dispatcher {

	/**
	 * Application instance.
	 *
	 * @type \Titon\Mvc\Application
	 */
	protected $_app;

	/**
	 * Request parameters.
	 *
	 * @type array
	 */
	protected $_params;

	/**
	 * Request instance.
	 *
	 * @type \Titon\Http\Request
	 */
	protected $_request;

	/**
	 * Response instance.
	 *
	 * @type \Titon\Http\Response
	 */
	protected $_response;

	/**
	 * {@inheritdoc}
	 */
	public function getApplication() {
		if (!$this->_app) {
			throw new Exception('Application has not been initialized');
		}

		return $this->_app;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @uses Titon\Common\Registry
	 */
	public function getController() {
		$module = $this->getModule();
		$namespace = $module->getController($this->getParam('controller'));

		$controller = new $namespace($this->getParams());
		$controller->setRequest($this->getRequest());
		$controller->setResponse($this->getResponse());
		$controller->setModule($module);
		$controller->initialize();

		// Save the instance for later use
		Registry::set($controller, 'Titon.controller');

		return $controller;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getModule() {
		return $this->getApplication()->getModule($this->getParam('module'));
	}

	/**
	 * {@inheritdoc}
	 *
	 * @throws Titon\Mvc\Exception
	 */
	public function getParam($key) {
		if (isset($this->_params[$key])) {
			return $this->_params[$key];
		}

		throw new Exception(sprintf('Param %s does not exist', $key));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParams() {
		return $this->_params;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRequest() {
		return $this->_request;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getResponse() {
		return $this->_response;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setApplication(Application $app) {
		$this->_app = $app;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setParams(array $params) {
		$this->_params = $params;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setRequest(Request $request) {
		$this->_request = $request;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setResponse(Response $response) {
		$this->_response = $response;

		return $this;
	}

}