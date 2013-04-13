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
 * Test class for Titon\Mvc\Controller.
 *
 * @property \Titon\Mvc\Controller $object
 */
class ControllerTest extends TestCase {

	/**
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		parent::setUp();

		$this->object = new ControllerFixture([
			'module' => 'module',
			'controller' => 'controller',
			'action' => 'action',
			'args' => [100, 25]
		]);
	}

	/**
	 * Test that dispatching executes the correct action and throws exceptions for invalid or private methods.
	 */
	public function testDispatchAction() {
		try {
			$this->object->dispatchAction(null); // wrong action name
			$this->object->dispatchAction('noAction'); // wrong action name
			$this->object->dispatchAction('_actionPseudoPrivate'); // underscored private action
			$this->object->dispatchAction('actionProtected'); // protected action
			$this->object->dispatchAction('actionPrivate'); // private action
			$this->object->dispatchAction('dispatchAction'); // method from parent
			$this->assertTrue(false);

		} catch (\Exception $e) {
			$this->assertTrue(true);
		}

		$this->assertEquals('actionNoArgs', $this->object->dispatchAction('actionNoArgs'));
		$this->assertEquals('actionNoArgs', $this->object->dispatchAction('actionNoArgs', ['foo', 'bar']));
		$this->assertEquals(125, $this->object->dispatchAction('actionWithArgs'));
		$this->assertEquals(555, $this->object->dispatchAction('actionWithArgs', [505, 50]));
		$this->assertEquals(335, $this->object->dispatchAction('actionWithArgs', [335]));
		$this->assertEquals(0, $this->object->dispatchAction('actionWithArgs', ['foo', 'bar']));
	}

	/**
	 * Test that forwarding the action dispatches correctly.
	 */
	public function testForwardAction() {
		try {
			$this->object->forwardAction(null);
			$this->object->forwardAction('noAction');
			$this->object->forwardAction('_actionPseudoPrivate');
			$this->object->forwardAction('actionProtected');
			$this->object->forwardAction('actionPrivate');
			$this->object->forwardAction('dispatchAction');
			$this->assertTrue(false);

		} catch (\Exception $e) {
			$this->assertTrue(true);
		}

		$this->object->forwardAction('actionNoArgs');
		$this->assertEquals('actionNoArgs', $this->object->config->action);

		$this->object->forwardAction('actionWithArgs');
		$this->assertEquals('actionWithArgs', $this->object->config->action);
	}

	/**
	 * Test that runAction() correctly executes and modifies the passed controller.
	 */
	public function testRunAction() {
		$this->object->config->foo = 'bar';

		$this->assertEquals('bar', $this->object->config->foo);
		$this->assertArrayNotHasKey('test', $this->object->config->all());

		$this->object->runAction(new ActionFixture());

		$this->assertNotEquals('bar', $this->object->config->foo);
		$this->assertEquals('baz', $this->object->config->foo);
		$this->assertArrayHasKey('test', $this->object->config->all());
	}

}
