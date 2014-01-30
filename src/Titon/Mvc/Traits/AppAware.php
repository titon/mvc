<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Mvc\Traits;

use Titon\Mvc\Application;
use Titon\Mvc\Exception\NoApplicationException;

/**
 * Permits a class to interact with an application object.
 *
 * @package Titon\Mvc\Traits
 */
trait AppAware {

    /**
     * Application instance.
     *
     * @type \Titon\Mvc\Application
     */
    protected $_app;

    /**
     * {@inheritdoc}
     *
     * @throws \Titon\Mvc\Exception\NoApplicationException
     */
    public function getApplication() {
        if (!$this->_app) {
            throw new NoApplicationException('Application has not been initialized');
        }

        return $this->_app;
    }

    /**
     * Set the application.
     *
     * @param \Titon\Mvc\Application $app
     * @return $this
     */
    public function setApplication(Application $app) {
        $this->_app = $app;

        return $this;
    }

}