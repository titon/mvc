<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Test\Fixture;

use Titon\Mvc\Controller\AbstractController;

/**
 * Fixture for Titon\Mvc\Controller.
 */
class ControllerFixture extends AbstractController {

	public function actionWithArgs($arg1, $arg2 = 0) {
		return $arg1 + $arg2;
	}

	public function actionNoArgs() {
		return 'actionNoArgs';
	}

	public function _actionPseudoPrivate() {
		return 'wontBeCalled';
	}

	protected function actionProtected() {
		return 'wontBeCalled';
	}

	private function actionPrivate() {
		return 'wontBeCalled';
	}

	public function renderView() { }

}