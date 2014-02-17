<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Mvc;

use Titon\Http\Request;
use Titon\Http\Response;
use Titon\Test\Stub\DispatcherStub;
use Titon\Test\Stub\ModuleStub;
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

        $this->object = new DispatcherStub();
    }

    /**
     * Test that getApplication() returns the app instance.
     */
    public function testGetSetApplication() {
        $module = new ModuleStub(TEMP_DIR);
        $module->setController('test-controller', 'Titon\Test\Stub\ControllerStub');

        $app = new Application(new Request(), new Response());
        $app->addModule('test-module', $module);

        try {
            $this->object->getApplication();
            $this->assertTrue(false);
        } catch (Exception $e) {
            $this->assertTrue(true);
        }

        $this->object->setApplication($app);

        $this->assertInstanceOf('Titon\Mvc\Application', $this->object->getApplication());
    }

    /**
     * Test that getRequest() returns the HTTP request instance.
     */
    public function testGetSetRequest() {
        $this->assertEquals(null, $this->object->getRequest());

        $this->object->setRequest(new Request());

        $this->assertInstanceOf('Titon\Http\Request', $this->object->getRequest());
    }

    /**
     * Test that getResponse() returns the HTTP response instance.
     */
    public function testGetSetResponse() {
        $this->assertEquals(null, $this->object->getResponse());

        $this->object->setResponse(new Response());

        $this->assertInstanceOf('Titon\Http\Response', $this->object->getResponse());
    }

    /**
     * Test that getModule() returns the module instance based off the URL.
     */
    public function testGetSetModule() {
        // No application
        try {
            $this->object->getModule();
            $this->assertTrue(false);
        } catch (Exception $e) {
            $this->assertTrue(true);
        }

        $module = new ModuleStub(TEMP_DIR);
        $module->setController('test-controller', 'Titon\Test\Stub\ControllerStub');

        $app = new Application(new Request(), new Response());
        $app->addModule('test-module', $module);

        $this->object->setApplication($app);
        $this->object->setParams(['module' => 'test-module']);

        $this->assertInstanceOf('Titon\Test\Stub\ModuleStub', $this->object->getModule());

        // Wrong module
        try {
            $this->object->setParams(['module' => 'foobar-module']);
            $this->object->getModule();
            $this->assertTrue(false);
        } catch (Exception $e) {
            $this->assertTrue(true);
        }
    }

    /**
     * Test that getController() returns a controller instance.
     */
    public function testGetController() {
        $module = new ModuleStub(TEMP_DIR);
        $module->setController('test-controller', 'Titon\Test\Stub\ControllerStub');

        $app = new Application(new Request(), new Response());
        $app->addModule('test-module', $module);

        $this->object->setApplication($app);
        $this->object->setRequest(new Request());
        $this->object->setResponse(new Response());
        $this->object->setParams(['module' => 'test-module', 'controller' => 'test-controller']);

        $this->assertInstanceOf('Titon\Test\Stub\ControllerStub', $this->object->getController());
    }

}