<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Mvc;

use Titon\Test\Stub\ModuleStub;
use Titon\Test\TestCase;
use \Exception;

/**
 * Test class for Titon\Mvc\Module.
 *
 * @property \Titon\Mvc\Module $object
 */
class ModuleTest extends TestCase {

    /**
     * This method is called before a test is executed.
     */
    protected function setUp() {
        parent::setUp();

        $this->object = new ModuleStub(TEMP_DIR);
    }

    /**
     * Test that getPath(), getResourcePath() and getViewPath() returns the file system paths.
     */
    public function testGetPaths() {
        $this->assertEquals(TEMP_DIR . DS, $this->object->getPath());
        $this->assertEquals(TEMP_DIR . DS . 'resources' . DS, $this->object->getResourcePath());
        $this->assertEquals(TEMP_DIR . DS . 'views' . DS, $this->object->getViewPath());
        $this->assertEquals(TEMP_DIR . DS . 'temp' . DS, $this->object->getTempPath());
    }

    /**
     * Test that getting and setting controllers work.
     */
    public function testGetSetControllers() {
        $this->assertEquals([], $this->object->getControllers());

        try {
            $this->object->getController('users');
            $this->assertTrue(false);

        } catch (Exception $e) {
            $this->assertTrue(true);
        }

        $this->object->setController('users', 'Titon\Controller\UsersController');
        $this->object->setControllers([
            'forum' => 'Titon\Controller\ForumController',
            'posts' => 'Titon\Controller\PostsController'
        ]);

        $this->assertEquals([
            'users' => 'Titon\Controller\UsersController',
            'forum' => 'Titon\Controller\ForumController',
            'posts' => 'Titon\Controller\PostsController'
        ], $this->object->getControllers());

        $this->assertEquals('Titon\Controller\UsersController', $this->object->getController('users'));
    }

}