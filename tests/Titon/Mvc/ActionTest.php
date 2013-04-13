<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc;

use Titon\Test\TestCase;
use Titon\Test\Fixture\ActionFixture;
use Titon\Test\Fixture\ControllerFixture;

/**
 * Test class for Titon\Mvc\Action.
 */
class ActionTest extends TestCase {

	/**
	 * Test that run correctly executes and modifies the passed controller.
	 */
	public function testRun() {
		$controller = new ControllerFixture(['foo' => 'bar']);

		$this->assertEquals('bar', $controller->config->foo);
		$this->assertArrayNotHasKey('test', $controller->config->all());

		$action = new ActionFixture();
		$action->setController($controller)->run();

		$this->assertNotEquals('bar', $controller->config->foo);
		$this->assertEquals('baz', $controller->config->foo);
		$this->assertArrayHasKey('test', $controller->config->all());
	}

}