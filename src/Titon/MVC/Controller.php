<?php
/**
 * Titon: A PHP 5.4 Modular Framework
 *
 * @copyright	Copyright 2010, Titon
 * @link		http://github.com/titon
 * @license		http://opensource.org/licenses/bsd-license.php (BSD License)
 */

namespace Titon\Mvc;

use Titon\Mvc\Action;

/**
 * Interface for the controllers library.
 */
interface Controller {

	/**
	 * Dispatch the request to the correct controller action. Checks to see if the action exists and is not protected.
	 *
	 * @access public
	 * @param string $action
	 * @param array $args
	 * @return mixed
	 */
	public function dispatchAction($action = null, array $args = []);

	/**
	 * Forward the current request to a new action, instead of doing an additional HTTP request.
	 *
	 * @access public
	 * @param string $action
	 * @param array $args
	 * @return mixed
	 */
	public function forwardAction($action, array $args = []);

	/**
	 * Trigger a custom Action class.
	 *
	 * @access public
	 * @param \Titon\Mvc\Action $action
	 * @return mixed
	 */
	public function runAction(Action $action);

	/**
	 * Triggered before the controller processes the requested action.
	 *
	 * @access public
	 * @return void
	 */
	public function preProcess();

	/**
	 * Triggered after the action processes, but before the view renders.
	 *
	 * @access public
	 * @return void
	 */
	public function postProcess();

}
