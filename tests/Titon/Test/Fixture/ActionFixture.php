<?php
/**
 * Titon: A PHP 5.4 Modular Framework
 *
 * @copyright	Copyright 2010, Titon
 * @link		http://github.com/titon
 * @license		http://opensource.org/licenses/bsd-license.php (BSD License)
 */

namespace Titon\Test\Fixture;

use Titon\Mvc\Action\AbstractAction;

/**
 * Fixture for Titon\Mvc\Action.
 */
class ActionFixture extends AbstractAction {

	public function run() {
		$this->_controller->config->add([
			'foo' => 'baz',
			'test' => 'value'
		]);
	}

}