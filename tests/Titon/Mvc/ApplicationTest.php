<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc;

use Titon\Test\Fixture\DispatcherFixture;
use Titon\Test\Fixture\ModuleFixture;
use Titon\Test\TestCase;
use \Exception;

/**
 * Test class for Titon\Mvc\Application.
 *
 * @property \Titon\Mvc\Application $object
 */
class ApplicationTest extends TestCase {

	/**
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		parent::setUp();

		$this->object = Application::getInstance();
	}

	/**
	 * Test getting and setting of modules.
	 */
	public function testModules() {
		$this->assertEquals([], $this->object->getModules());

		try {
			$this->object->getModule('test-module');
			$this->assertTrue(false);

		} catch (Exception $e) {
			$this->assertTrue(true);
		}

		$module = new ModuleFixture('test-module', TEMP_DIR);

		$this->object->addModule($module);

		$this->assertEquals($module, $this->object->getModule('test-module'));
		$this->assertEquals([
			'test-module' => $module
		], $this->object->getModules());
	}

	/**
	 * Test getting and setting of the dispatcher.
	 */
	public function testDispatcher() {
		$this->assertInstanceOf('Titon\Mvc\Dispatcher\FrontDispatcher', $this->object->getDispatcher());

		$this->object->setDispatcher(new DispatcherFixture());

		$this->assertInstanceOf('Titon\Test\Fixture\DispatcherFixture', $this->object->getDispatcher());
	}

}