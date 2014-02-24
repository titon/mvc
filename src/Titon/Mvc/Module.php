<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Mvc;

use Titon\Mvc\Contract\UseApplication;

/**
 * Interface for the modules library.
 *
 * @package Titon\Mvc
 */
interface Module extends UseApplication {

    /**
     * Bootstrap the module by triggering any routes or configuration.
     *
     * @param \Titon\Mvc\Application $app
     */
    public function bootstrap(Application $app);

    /**
     * Return a controller by key.
     *
     * @param string $key
     * @return string
     */
    public function getController($key);

    /**
     * Return all controllers.
     *
     * @return array
     */
    public function getControllers();

    /**
     * Return the modules file system path.
     *
     * @return string
     */
    public function getPath();

    /**
     * Return the modules resource location.
     *
     * @return string
     */
    public function getResourcePath();

    /**
     * Return the modules temporary location.
     *
     * @return string
     */
    public function getTempPath();

    /**
     * Return the modules views location.
     *
     * @return string
     */
    public function getViewPath();

    /**
     * Define a module controller.
     *
     * @param string $key
     * @param string $class
     * @return $this
     */
    public function setController($key, $class);

    /**
     * Define multiple module controllers.
     *
     * @param array $controllers
     * @return $this
     */
    public function setControllers(array $controllers);

}