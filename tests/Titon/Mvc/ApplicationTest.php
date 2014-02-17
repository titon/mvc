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

        $this->object = new Application(new Request(), new Response());
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

        $module = new ModuleStub(TEMP_DIR);

        $this->object->addModule('test-module', $module);

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

        $this->object->setDispatcher(new DispatcherStub());

        $this->assertInstanceOf('Titon\Test\Stub\DispatcherStub', $this->object->getDispatcher());
    }

    /**
     * Test that the router object is created.
     */
    public function testRouter() {
        $this->assertInstanceOf('Titon\Route\Router', $this->object->getRouter());
    }

}