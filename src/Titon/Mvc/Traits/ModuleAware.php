<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Mvc\Traits;

use Titon\Mvc\Module;

/**
 * Permits a class to interact with a module object.
 *
 * @package Titon\Http\Traits
 */
trait ModuleAware {

    /**
     * Module object.
     *
     * @type \Titon\Mvc\Module
     */
    protected $_module;

    /**
     * Return the module object.
     *
     * @return \Titon\Mvc\Module
     */
    public function getModule() {
        return $this->_module;
    }

    /**
     * Set the module object.
     *
     * @param \Titon\Mvc\Module $module
     * @return $this
     */
    public function setModule(Module $module) {
        $this->_module = $module;

        return $this;
    }

}