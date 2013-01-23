<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc\Controller;

use Titon\Common\Base;
use Titon\Common\Registry;
use Titon\Common\Traits\Attachable;
use Titon\Http\Request;
use Titon\Http\Response;
use Titon\Mvc\Action;
use Titon\Mvc\Controller;
use Titon\Mvc\View;
use Titon\Mvc\Exception;

/**
 * The Controller (MVC) acts as the median between the request and response within the dispatch cycle.
 * It splits up its responsibility into multiple Actions, where each Action deals with specific business logic.
 * The logical data is retrieved from a Model (database or logic entity) or a PHP super global (POST, GET).
 *
 * The Controller receives an instance of the View object allowing the Controller to set data to the view,
 * overwrite the View and Engine configuration, attach helpers, etc.
 *
 * Furthermore, the Controller inherits all functionality from the Attachable class, allowing you to attach
 * external classes to use their functionality and trigger specific callbacks.
 */
abstract class AbstractController extends Base implements Controller {
	use Attachable;

	/**
	 * Configuration.
	 *
	 *	module 			- Current application module
	 *	controller 		- Current controller within the module
	 *	action 			- Current action within the controller
	 *	ext 			- The extension within the address bar, and what content-type to render the page as
	 *	args 			- Action arguments
	 *
	 * @var array
	 */
	protected $_config = [
		'module' => '',
		'controller' => '',
		'action' => '',
		'ext' => '',
		'args' => []
	];

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
	 * View instance.
	 *
	 * @var \Titon\Mvc\View
	 */
	protected $_view;

	/**
	 * Dispatch the request to the correct controller action. Checks to see if the action exists and is not protected.
	 *
	 * @param string $action
	 * @param array $args
	 * @return string
	 * @throws \Titon\Mvc\Exception
	 */
	public function dispatchAction($action = null, array $args = []) {
		if (!$action) {
			$action = $this->config->action;
		}

		if (!$args) {
			$args = $this->config->args;
		}

		// Do not include the base controller methods
		$methods = array_diff(get_class_methods($this), get_class_methods(__CLASS__));

		if (!in_array($action, $methods) || mb_substr($action, 0, 1) === '_') {
			throw new Exception('Your action does not exist, or is not public, or is found within the parent Controller');
		}

		// Trigger action and generate response from view templates
		$response = call_user_func_array([$this, $action], $args);

		if (!$response) {
			$response = $this->renderView();
		}

		return $response;
	}

	/**
	 * Forward the current request to a new action, instead of doing an additional HTTP request.
	 *
	 * @param string $action
	 * @param array $args
	 * @return string
	 */
	public function forwardAction($action, array $args = []) {
		$this->config->action = $action;

		return $this->dispatchAction($action, $args);
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
	 * Return the view object.
	 *
	 * @return \Titon\Mvc\View
	 */
	public function getView() {
		return $this->_view;
	}

	/**
	 * Triggered before the controller processes the requested action.
	 *
	 * @return void
	 */
	public function preProcess() {
		$this->notifyObjects('preProcess');
	}

	/**
	 * Triggered after the action processes, but before the view renders.
	 *
	 * @return void
	 */
	public function postProcess() {
		$this->notifyObjects('postProcess');
	}

	/**
	 * Render the view templates and return the output.
	 *
	 * @return string
	 * @throws \Titon\Mvc\Exception
	 */
	public function renderView() {
		if (!$this->getView()) {
			throw new Exception('View has not been initialized');
		}

		$template = $this->config->all();
		unset($template['args']);

		return $this->getView()->run($template);
	}

	/**
	 * Trigger a custom Action class.
	 *
	 * @param \Titon\Mvc\Action $action
	 * @return string
	 */
	public function runAction(Action $action) {
		return $action->setController($this)->run();
	}

	/**
	 * Set the request object.
	 *
	 * @param \Titon\Http\Request $request
	 * @return \Titon\Mvc\Controller
	 */
	public function setRequest(Request $request) {
		$this->_request = $request;

		return $this;
	}

	/**
	 * Set the response object.
	 *
	 * @param \Titon\Http\Response $response
	 * @return \Titon\Mvc\Controller
	 */
	public function setResponse(Response $response) {
		$this->_response = $response;

		return $this;
	}

	/**
	 * Set the view instance.
	 *
	 * @param \Titon\Mvc\View $view
	 * @return \Titon\Mvc\View
	 */
	public function setView(View $view) {
		$this->_view = $view;

		return $view;
	}

}
