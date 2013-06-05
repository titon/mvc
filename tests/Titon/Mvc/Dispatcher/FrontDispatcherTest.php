<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc\Dispatcher;

use Titon\Mvc\Application;
use Titon\Http\Request;
use Titon\Http\Response;
use Titon\Test\Fixture\DispatcherFixture;
use Titon\Test\Fixture\ModuleFixture;
use Titon\Test\TestCase;
use \Exception;

/**
 * Test class for Titon\Mvc\Dispatcher\FrontDispatcher.
 *
 * @property \Titon\Mvc\Dispatcher\FrontDispatcher $object
 */
class FrontDispatcherTest extends TestCase {

	/**
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		parent::setUp();

		$module = new ModuleFixture('test-module', TEMP_DIR);
		$module->setController('test-controller', 'Titon\Test\Fixture\ControllerFixture');

		$app = Application::getInstance();
		$app->addModule($module);

		$this->object = new FrontDispatcher();
		$this->object->setApplication($app);
		$this->object->setRequest(new Request());
		$this->object->setResponse(new Response());
		$this->object->setParams([
			'module' => 'test-module',
			'controller' => 'test-controller',
			'action' => 'actionNoArgs',
			'args' => []
		]);
	}

	/**
	 * Test that dispatch() returns the Controllers action output.
	 */
	public function testDispatch() {
		$this->assertEquals('actionNoArgs', $this->object->dispatch());
	}

}