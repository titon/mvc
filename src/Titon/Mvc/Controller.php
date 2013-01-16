<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
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
	 * @param string $action
	 * @param array $args
	 * @return mixed
	 */
	public function dispatchAction($action = null, array $args = []);

	/**
	 * Forward the current request to a new action, instead of doing an additional HTTP request.
	 *
	 * @param string $action
	 * @param array $args
	 * @return mixed
	 */
	public function forwardAction($action, array $args = []);

	/**
	 * Return the request object.
	 *
	 * @return \Titon\Http\Request
	 */
	public function getRequest();

	/**
	 * Return the response object.
	 *
	 * @return \Titon\Http\Response
	 */
	public function getResponse();

	/**
	 * Return the view object.
	 *
	 * @return \Titon\Mvc\View
	 */
	public function getView();

	/**
	 * Render the view templates and return the output.
	 *
	 * @return string
	 */
	public function renderView();

	/**
	 * Trigger a custom Action class.
	 *
	 * @param \Titon\Mvc\Action $action
	 * @return mixed
	 */
	public function runAction(Action $action);

	/**
	 * Triggered before the controller processes the requested action.
	 *
	 * @return void
	 */
	public function preProcess();

	/**
	 * Triggered after the action processes, but before the view renders.
	 *
	 * @return void
	 */
	public function postProcess();

	/**
	 * Set the view instance.
	 *
	 * @param \Titon\Mvc\View $view
	 * @return \Titon\Mvc\View
	 */
	public function setView(View $view);

}
