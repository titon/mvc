<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc\Action;

use Titon\Common\Base;
use Titon\Common\Traits\Attachable;
use Titon\Mvc\Action;
use Titon\Mvc\Controller;

/**
 * The Action is a sub-routine of the Controller parent and is packaged as a stand-alone object instead of a method.
 * An Action object gives you the flexibility of re-using actions and specific logic across multiple
 * Controllers, encapsulating additional methods within the Action process, and defining its own attachments.
 */
abstract class AbstractAction extends Base implements Action {
	use Attachable;

	/**
	 * Controller object.
	 *
	 * @var \Titon\Mvc\Controller
	 */
	protected $_controller;

	/**
	 * Return the controller.
	 *
	 * @return \Titon\Mvc\Controller
	 */
	public function getController() {
		return $this->_controller;
	}

	/**
	 * Set the parent controller.
	 *
	 * @param \Titon\Mvc\Controller $controller
	 * @return \Titon\Mvc\Action
	 */
	public function setController(Controller $controller) {
		$this->_controller = $controller;

		return $this;
	}

}