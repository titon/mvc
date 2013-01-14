<?php
/**
 * Titon: A PHP 5.4 Modular Framework
 *
 * @copyright	Copyright 2010, Titon
 * @link		http://github.com/titon
 * @license		http://opensource.org/licenses/bsd-license.php (BSD License)
 */

namespace Titon\Mvc\Controller;

use Titon\Common\Base;
use Titon\Common\Registry;
use Titon\Common\Traits\Attachable;
use Titon\Mvc\Action;
use Titon\Mvc\Controller;
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
	 * @access protected
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
	 * Dispatch the request to the correct controller action. Checks to see if the action exists and is not protected.
	 *
	 * @access public
	 * @param string $action
	 * @param array $args
	 * @return mixed
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

		return call_user_func_array([$this, $action], $args);
	}

	/**
	 * Forward the current request to a new action, instead of doing an additional HTTP request.
	 *
	 * @access public
	 * @param string $action
	 * @param array $args
	 * @return mixed
	 */
	public function forwardAction($action, array $args = []) {
		$this->config->action = $action;

		return $this->dispatchAction($action, $args);
	}

	/**
	 * Attach the request and response objects. Can overwrite or remove for high customization.
	 *
	 * @access public
	 * @return void
	 */
	public function initialize() {
		$this->attachObject('request', function() {
			return Registry::factory('Titon\Http\Request');
		});

		$this->attachObject('response', function() {
			return Registry::factory('Titon\Http\Response');
		});
	}

	/**
	 * Trigger a custom Action class.
	 *
	 * @access public
	 * @param \Titon\Mvc\Action $action
	 * @return mixed
	 */
	public function runAction(Action $action) {
		return $action->setController($this)->run();
	}

	/**
	 * Triggered before the controller processes the requested action.
	 *
	 * @access public
	 * @return void
	 */
	public function preProcess() {
		$this->notifyObjects('preProcess');
	}

	/**
	 * Triggered after the action processes, but before the view renders.
	 *
	 * @access public
	 * @return void
	 */
	public function postProcess() {
		$this->notifyObjects('postProcess');
	}

}
