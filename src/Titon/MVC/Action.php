<?php
/**
 * Titon: A PHP 5.4 Modular Framework
 *
 * @copyright	Copyright 2010, Titon
 * @link		http://github.com/titon
 * @license		http://opensource.org/licenses/bsd-license.php (BSD License)
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
	 * @access public
	 * @return mixed
	 */
	public function run();

	/**
	 * Store the parent Controller.
	 *
	 * @access public
	 * @param \Titon\Mvc\Controller $controller
	 * @return \Titon\Mvc\Action
	 */
	public function setController(Controller $controller);

}