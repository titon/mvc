<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc\Module;

use Titon\Mvc\Module;
use Titon\Mvc\Exception;
use Titon\Common\Base;

/**
 * A Module represents a self contained miniature application. A Module should easily be dropped into
 * or removed from an existing web application.
 *
 * It provides a mapping of all publicly accessible controllers and resources.
 */
abstract class AbstractModule extends Base implements Module {

	/**
	 * List of controller slugs to namespaces.
	 *
	 * @var array
	 */
	protected $_controllers = [];

	/**
	 * The module slug used in URLs.
	 *
	 * @var string
	 */
	protected $_key;

	/**
	 * The module file system location.
	 *
	 * @var string
	 */
	protected $_path;

	/**
	 * Store the module key and path.
	 *
	 * @param string $key
	 * @param string $path
	 */
	public function __construct($key, $path) {
		$this->_key = $key;
		$this->_path = $path;
	}

	/**
	 * Bootstrap the module by triggering any routes or configuration.
	 *
	 * @return void
	 */
	public function bootstrap() {
		return;
	}

	/**
	 * Return a controller by key.
	 *
	 * @param string $key
	 * @return string
	 * @throws \Titon\Mvc\Exception
	 */
	public function getController($key) {
		if (isset($this->_controllers[$key])) {
			return $this->_controllers[$key];
		}

		throw new Exception(sprintf('Controller %s does not exist', $key));
	}

	/**
	 * Return all controllers.
	 *
	 * @return array
	 */
	public function getControllers() {
		return $this->_controllers;
	}

	/**
	 * Return the module key.
	 *
	 * @return string
	 */
	public function getKey() {
		return $this->_key;
	}

	/**
	 * Return the modules file system path.
	 *
	 * @return string
	 */
	public function getPath() {
		return $this->_path;
	}

	/**
	 * Return the modules resource location.
	 *
	 * @return string
	 */
	public function getResourcePath() {
		return $this->getPath() . '/resources/';
	}

	/**
	 * Return the modules views location.
	 *
	 * @return string
	 */
	public function getViewPath() {
		return $this->getPath() . '/views/';
	}

	/**
	 * Define a module controller.
	 *
	 * @param string $key
	 * @param string $class
	 * @return \Titon\Mvc\Module
	 */
	public function setController($key, $class) {
		$this->_controllers[$key] = $class;

		return $this;
	}

	/**
	 * Define multiple module controllers.
	 *
	 * @param array $controllers
	 * @return \Titon\Mvc\Module
	 */
	public function setControllers(array $controllers) {
		foreach ($controllers as $key => $class) {
			$this->setController($key, $class);
		}

		return $this;
	}

}