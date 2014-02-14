<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Mvc\Dispatcher;

use Titon\Mvc\Application;
use Titon\Http\Request;
use Titon\Http\Response;
use Titon\Test\Stub\ModuleStub;
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

        $module = new ModuleStub('test-module', TEMP_DIR);
        $module->setController('test-controller', 'Titon\Test\Stub\ControllerStub');

        $app = new Application(new Request(), new Response());
        $app->addModule($module);

        $this->object = new FrontDispatcher();
        $this->object->setApplication($app);
        $this->object->setRequest($app->getRequest());
        $this->object->setResponse($app->getResponse());
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