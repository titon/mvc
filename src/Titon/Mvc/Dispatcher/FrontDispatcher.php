<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc\Dispatcher;

use Titon\Mvc\Dispatcher\AbstractDispatcher;
use \Exception;

/**
 * The FrontDispatcher triggers all the necessary methods and callbacks
 * within the controller to generate the response output.
 */
class FrontDispatcher extends AbstractDispatcher {

	/**
	 * Dispatch the current request and generate a response.
	 *
	 * @return string
	 */
	public function dispatch() {
		$controller = $this->getController();
		$controller->preProcess();

		try {
			$controller->dispatchAction($this->getParam('action'), $this->getParam('args'));
		} catch (Exception $e) {
			return $controller->renderError($e);
		}

		$controller->postProcess();

		return $controller->renderView();
	}

}