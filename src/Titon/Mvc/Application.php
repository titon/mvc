<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Mvc;

use Titon\Common\Registry;
use Titon\Common\Traits\Instanceable;
use Titon\Controller\Controller\ErrorController;
use Titon\Debug\Debugger;
use Titon\Event\Listener;
use Titon\Event\Traits\Emittable;
use Titon\Http\Request;
use Titon\Http\Response;
use Titon\Mvc\Exception\AssetSymlinkException;
use Titon\Mvc\Exception\MissingComponentException;
use Titon\Mvc\Module;
use Titon\Mvc\Dispatcher;
use Titon\Mvc\Dispatcher\FrontDispatcher;
use Titon\Mvc\Exception\MissingModuleException;
use Titon\Route\Router;
use Titon\Utility\Path;
use Titon\View\View;
use Titon\View\Helper\Html\AssetHelper;
use Titon\View\Helper\Html\HtmlHelper;
use \Exception;

/**
 * The Application object acts as the hub for the entire HTTP dispatch cycle and manages all available modules.
 * When triggered, it will dispatch the request to the correct module, controller and action.
 *
 * @package Titon\Mvc
 * @events
 *      mvc.preRun(Application $app)
 *      mvc.postRun(Application $app)
 *      mvc.preError(Application $app, Controller $con, Exception $exc)
 *      mvc.postError(Application $app, Controller $con, Exception $exc, $response)
 *      mvc.preAsset(Application $app, $path, $response)
 *      mvc.postAsset(Application $app, $path, $response)
 *      mvc.onShutdown(Application $app)
 */
class Application {
    use Instanceable, Emittable;

    /**
     * List of manually installed components.
     *
     * @type object[]
     */
    protected $_components = [];

    /**
     * Dispatcher instance.
     *
     * @type \Titon\Mvc\Dispatcher
     */
    protected $_dispatcher;

    /**
     * List of modules.
     *
     * @type \Titon\Mvc\Module[]
     */
    protected $_modules = [];

    /**
     * Request instance.
     *
     * @type \Titon\Http\Request
     */
    protected $_request;

    /**
     * Response instance.
     *
     * @type \Titon\Http\Response
     */
    protected $_response;

    /**
     * Router instance.
     *
     * @type \Titon\Route\Router
     */
    protected $_router;

    /**
     * Path to webroot folder.
     *
     * @type string
     */
    protected $_webroot;

    /**
     * Set the error handler if the debug package exists.
     *
     * @uses Titon\Common\Registry
     * @uses Titon\Debug\Debugger
     */
    public function __construct() {
        $this->_router = Registry::factory('Titon\Route\Router');

        if (class_exists('Titon\Debug\Debugger')) {
            Debugger::setHandler([$this, 'handleError']);
        }
    }

    /**
     * Add a module into the application.
     *
     * @param \Titon\Mvc\Module $module
     * @return \Titon\Mvc\Module
     */
    public function addModule(Module $module) {
        $this->_modules[$module->getKey()] = $module;

        $module->setApplication($this);
        $module->bootstrap($this);

        if ($module instanceof Listener) {
            $this->on('mvc', $module);
        }

        return $module;
    }

    /**
     * Create symbolic links to the webroot folder within each module.
     * This allows for direct static asset handling.
     *
     * @param string $web
     * @throws \Titon\Mvc\Exception\AssetSymlinkException
     */
    public function createLinks($web) {
        $web = Path::ds($web, true);

        foreach ($this->getModules() as $key => $module) {
            $moduleWeb = $module->getPath() . 'web' . Path::SEPARATOR;

            if (!file_exists($moduleWeb)) {
                continue;
            }

            $targetLink = $web . $key . Path::SEPARATOR;

            // Create symlink if folder doesn't exist
            if (!file_exists($targetLink)) {
                symlink($moduleWeb, $targetLink);

            // Throw an error if file exists but is not a symlink
            } else if (is_file($targetLink) && !is_link($targetLink)) {
                throw new AssetSymlinkException(sprintf('Webroot folder %s must not exist so that static assets can be symlinked', $key));
            }
        }
    }

    /**
     * Return a component by key.
     *
     * @param string $key
     * @return object
     * @throws \Titon\Mvc\Exception\MissingComponentException
     */
    public function get($key) {
        if (isset($this->_components[$key])) {
            return $this->_components[$key];
        }

        throw new MissingComponentException(sprintf('Application component %s does not exist', $key));
    }

    /**
     * Return the dispatcher instance. Use FrontDispatcher if none is set.
     *
     * @return \Titon\Mvc\Dispatcher
     */
    public function getDispatcher() {
        if (!$this->_dispatcher) {
            $this->setDispatcher(new FrontDispatcher());
        }

        return $this->_dispatcher;
    }

    /**
     * Return a module by key.
     *
     * @param string $key
     * @return \Titon\Mvc\Module
     * @throws \Titon\Mvc\Exception\MissingModuleException
     */
    public function getModule($key) {
        if (isset($this->_modules[$key])) {
            return $this->_modules[$key];
        }

        throw new MissingModuleException(sprintf('Could not locate %s module', $key));
    }

    /**
     * Return all modules.
     *
     * @return \Titon\Mvc\Module[]
     */
    public function getModules() {
        return $this->_modules;
    }

    /**
     * Return the router object.
     *
     * @return \Titon\Route\Router
     */
    public function getRouter() {
        return $this->_router;
    }

    /**
     * Return the request object.
     *
     * @return \Titon\Http\Request
     */
    public function getRequest() {
        return $this->_request;
    }

    /**
     * Return the response object.
     *
     * @return \Titon\Http\Response
     */
    public function getResponse() {
        return $this->_response;
    }

    /**
     * Return the webroot path.
     *
     * @return string
     */
    public function getWebroot() {
        return $this->_webroot;
    }

    /**
     * Handle a static asset by outputting the file contents and size to the browser.
     * If the asset does not exist, throw a 404 and exit.
     *
     * This method has been deprecated in favor of asset symlinking.
     *
     * @deprecated
     * @param array $params
     */
    public function handleAsset($params) {
        $response = $this->getResponse();
        $path = null;

        try {
            $module = $this->getModule($params['module']);
            $path = implode('/', [$module->getPath(), 'web', $params['asset'], $params['path']]);

            $this->emit('mvc.preAsset', [$this, &$path, $response]);

            if (file_exists($path)) {
                $response
                    ->body(file_get_contents($path))
                    ->cache()
                    ->contentType(pathinfo($path, PATHINFO_EXTENSION))
                    ->contentLength(filesize($path))
                    ->lastModified(filemtime($path));
            } else {
                $response->statusCode(404);
            }
        } catch (Exception $e) {
            $response->statusCode(404);
        }

        $this->emit('mvc.postAsset', [$this, $path, $response]);

        $response->respond();
        exit();
    }

    /**
     * Default mechanism for handling uncaught exceptions.
     * Will fetch the current controller instance or instantiate an ErrorController.
     * The error view template will be rendered.
     *
     * @uses Titon\Debug\Debugger
     *
     * @param \Exception $exception
     */
    public function handleError(Exception $exception) {
        if (class_exists('Titon\Debug\Debugger')) {
            Debugger::logException($exception);
        }

        try {
            $controller = Registry::get('titon.controller');

        } catch (Exception $e) {
            $view = new View();
            $view->addHelper('html', new HtmlHelper());
            $view->addHelper('asset', new AssetHelper());

            $controller = new ErrorController($this->getRouter()->current()->getParams());
            $controller->setView($view);
            $controller->initialize();
        }

        $this->emit('mvc.preError', [$this, $controller, $exception]);

        $controller->setRequest($this->getRequest());
        $controller->setResponse($this->getResponse());

        $response = $controller->renderError($exception);

        $this->emit('mvc.postError', [$this, $controller, $exception, &$response]);

        $this->getResponse()->body($response)->respond();

        $this->emit('mvc.onShutdown', [$this]);
        exit();
    }

    /**
     * Run the application by fetching the dispatcher and dispatching the request
     * to the module and controller that matches the current URL.
     *
     * @param string $webroot
     * @param \Titon\Http\Request $request
     * @param \Titon\Http\Response $response
     */
    public function run($webroot, Request $request, Response $response) {
        $this->_webroot = $webroot;
        $this->_request = $request;
        $this->_response = $response;

        $this->createLinks($webroot);

        $this->emit('mvc.preRun', [$this]);

        $dispatcher = $this->getDispatcher();
        $dispatcher->setApplication($this);
        $dispatcher->setParams($this->getRouter()->current()->getParams());
        $dispatcher->setRequest($this->getRequest());
        $dispatcher->setResponse($this->getResponse());

        $response = $dispatcher->dispatch();

        $this->emit('mvc.postRun', [$this]);

        $this->getResponse()->body($response)->respond();

        $this->emit('mvc.onShutdown', [$this]);
        exit();
    }

    /**
     * Set an object to use throughout the application.
     *
     * @param string $key
     * @param object $object
     * @return \Titon\Mvc\Application
     */
    public function set($key, $object) {
        $this->_components[$key] = $object;

        return $this;
    }

    /**
     * Set the dispatcher to use.
     *
     * @param \Titon\Mvc\Dispatcher $dispatcher
     * @return \Titon\Mvc\Dispatcher
     */
    public function setDispatcher(Dispatcher $dispatcher) {
        $this->_dispatcher = $dispatcher;

        return $dispatcher;
    }

}

// Define as singleton
Application::$singleton = true;