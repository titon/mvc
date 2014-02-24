<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Mvc\Dispatcher;

use Titon\Common\Base;
use Titon\Common\Registry;
use Titon\Http\Traits\RequestAware;
use Titon\Http\Traits\ResponseAware;
use Titon\Mvc\Contract\UseApplication;
use Titon\Mvc\Contract\UseModule;
use Titon\Mvc\Dispatcher;
use Titon\Mvc\Traits\AppAware;

/**
 * The Dispatcher handles the generation of the response output.
 * It must locate the correct module and controller, and pass the current request, response and parameters.
 *
 * @package Titon\Mvc\Dispatcher
 */
abstract class AbstractDispatcher extends Base implements Dispatcher {
    use AppAware, RequestAware, ResponseAware;

    /**
     * Request parameters.
     *
     * @type array
     */
    protected $_params;

    /**
     * {@inheritdoc}
     *
     * @uses Titon\Common\Registry
     */
    public function getController() {
        $module = $this->getModule();
        $namespace = $module->getController($this->getParam('controller'));

        /** @type \Titon\Controller\Controller $controller */
        $controller = new $namespace($this->getParams());
        $controller->setRequest($this->getRequest());
        $controller->setResponse($this->getResponse());

        if ($controller instanceof UseApplication) {
            $controller->setApplication($this->getApplication());
        }

        if ($controller instanceof UseModule) {
            $controller->setModule($module);
        }

        if (method_exists($controller, 'initialize')) {
            $controller->initialize();
        }

        // Save the instance for later use
        Registry::set($controller, 'titon.controller');

        return $controller;
    }

    /**
     * {@inheritdoc}
     */
    public function getModule() {
        return $this->getApplication()->getModule($this->getParam('module'));
    }

    /**
     * {@inheritdoc}
     */
    public function getParam($key) {
        return isset($this->_params[$key]) ? $this->_params[$key] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getParams() {
        return $this->_params;
    }

    /**
     * {@inheritdoc}
     */
    public function setParams(array $params) {
        $this->_params = $params;

        return $this;
    }

}