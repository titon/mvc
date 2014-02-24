<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Mvc\Contract;

use Titon\Mvc\Application;

/**
 * An interface that requires the host object to interact with an application.
 *
 * @package Titon\Mvc\Contract
 */
interface UseApplication {

    /**
     * Return the application.
     *
     * @return \Titon\Mvc\Application
     */
    public function getApplication();

    /**
     * Set the application.
     *
     * @param \Titon\Mvc\Application $app
     * @return $this
     */
    public function setApplication(Application $app);

}