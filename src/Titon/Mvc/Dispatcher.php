<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Mvc;

use Titon\Http\Request;
use Titon\Http\Response;
use Titon\Mvc\Contract\UseApplication;

/**
 * Interface for the dispatchers library.
 *
 * @package Titon\Mvc
 */
interface Dispatcher extends UseApplication {

    /**
     * Return the controller instance.
     *
     * @return \Titon\Controller\Controller
     */
    public function getController();

    /**
     * Return the module instance.
     *
     * @return \Titon\Mvc\Module
     */
    public function getModule();

    /**
     * Return a parameter by key.
     *
     * @param string $key
     * @return mixed
     */
    public function getParam($key);

    /**
     * Return all parameters.
     *
     * @return array
     */
    public function getParams();

    /**
     * Return the request object.
     *
     * @return \Titon\Http\Request
     */
    public function getRequest();

    /**
     * Return the response object.
     *
     * @return \Titon\Http\Response
     */
    public function getResponse();

    /**
     * Dispatch the current request and generate a response.
     *
     * @return string
     */
    public function dispatch();

    /**
     * Set parameters.
     *
     * @param array $params
     * @return $this
     */
    public function setParams(array $params);

    /**
     * Set the request object.
     *
     * @param \Titon\Http\Request $request
     * @return $this
     */
    public function setRequest(Request $request);

    /**
     * Set the response object.
     *
     * @param \Titon\Http\Response $response
     * @return $this
     */
    public function setResponse(Response $response);

}
