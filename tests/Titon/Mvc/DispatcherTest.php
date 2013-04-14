<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc;

use Titon\Http\Request;
use Titon\Http\Response;
use Titon\Test\Fixture\DispatcherFixture;
use Titon\Test\Fixture\ModuleFixture;
use Titon\Test\TestCase;
use \Exception;

/**
 * Test class for Titon\Mvc\Dispatcher.
 *
 * @property \Titon\Mvc\Dispatcher $object
 */
class DispatcherTest extends TestCase {

	/**
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		parent::setUp();

		$module = new ModuleFixture('test-module', TEMP_DIR);
		$module->setController('test-controller', 'Titon\Test\Fixture\ControllerFixture');

		$app = Application::getInstance();
		$app->addModule($module);

		$this->object = new DispatcherFixture();
		$this->object->setApplication($app);
		$this->object->setRequest(new Request());
		$this->object->setResponse(new Response());
	}

	/**
	 * Test that getApplication() returns the app instance.
	 */
	public function testGetApplication() {
		$this->assertInstanceOf('Titon\Mvc\Application', $this->object->getApplication());
	}

	/**
	 * Test that getRequest() returns the HTTP request instance.
	 */
	public function testGetRequest() {
		$this->assertInstanceOf('Titon\Http\Request', $this->object->getRequest());
	}

	/**
	 * Test that getResponse() returns the HTTP response instance.
	 */
	public function testGetResponse() {
		$this->assertInstanceOf('Titon\Http\Response', $this->object->getResponse());
	}

	/**
	 * Test that getModule() returns the module instance based off the URL.
	 */
	public function testGetModule() {
		$this->object->setParams(['module' => 'test-module']);

		$this->assertInstanceOf('Titon\Test\Fixture\ModuleFixture', $this->object->getModule());
	}

}