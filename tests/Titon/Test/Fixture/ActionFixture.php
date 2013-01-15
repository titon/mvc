<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
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