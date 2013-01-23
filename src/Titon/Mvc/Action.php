<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc;

use Titon\Mvc\Controller;

/**
 * Interface for the actions library.
 */
interface Action {

	/**
	 * Method that is executed to trigger the actions logic.
	 *
	 * @return string
	 */
	public function run();

	/**
	 * Store the parent Controller.
	 *
	 * @param \Titon\Mvc\Controller $controller
	 * @return \Titon\Mvc\Action
	 */
	public function setController(Controller $controller);

}