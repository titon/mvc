<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc\Dispatcher;

use Titon\Mvc\Dispatcher\AbstractDispatcher;
use Titon\Http\Exception\HttpException;
use Titon\Mvc\View;
use Titon\Route\Router;

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
			$output = $controller->dispatchAction(
				$this->getParam('action'),
				$this->getParam('args')
			);

			if ($output) {
				return $output;
			}

		// Exit early for HTTP errors
		} catch (HttpException $e) {

			return $controller->getView()
				->setVariables([
					'error' => $e,
					'code' => $e->getCode(),
					'message' => $e->getMessage(),
					'url' => Router::build($this->getParams())
				])
				->run([
					'template' => ['errors', $e->getCode()],
					'type' => View::CUSTOM
				]);
		}

		$controller->postProcess();

		return $controller->renderView();
	}

}