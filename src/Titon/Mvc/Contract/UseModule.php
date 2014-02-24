<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Mvc\Contract;

use Titon\Mvc\Module;

/**
 * An interface that requires the host object to interact with a module.
 *
 * @package Titon\Mvc\Contract
 */
interface UseModule {

    /**
     * Return the module.
     *
     * @return \Titon\Mvc\Module
     */
    public function getModule();

    /**
     * Set the Module.
     *
     * @param \Titon\Mvc\Module $module
     * @return $this
     */
    public function setModule(Module $module);

}