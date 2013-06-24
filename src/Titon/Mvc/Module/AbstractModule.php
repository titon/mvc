<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc\Module;

use Titon\Common\Base;
use Titon\Event\Scheduler;
use Titon\Mvc\Application;
use Titon\Mvc\Module;
use Titon\Mvc\Exception\MissingControllerException;

/**
 * A Module represents a self contained miniature application. A Module should easily be dropped into
 * or removed from an existing web application.
 *
 * It provides a mapping of all publicly accessible controllers and resources.
 *
 * @package Titon\Mvc\Module
 */
abstract class AbstractModule extends Base implements Module {

	/**
	 * List of controller slugs to namespaces.
	 *
	 * @type array
	 */
	protected $_controllers = [];

	/**
	 * The module slug used in URLs.
	 *
	 * @type string
	 */
	protected $_key;

	/**
	 * The module file system location.
	 *
	 * @type string
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

		parent::__construct();
	}

	/**
	 * {@inheritdoc}
	 *
	 * @uses Titon\Event\Scheduler
	 */
	public function bootstrap(Application $app) {
		Scheduler::dispatch('mvc.module.bootstrap', [$this]);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @throws \Titon\Mvc\Exception\MissingControllerException
	 */
	public function getController($key) {
		if (isset($this->_controllers[$key])) {
			return $this->_controllers[$key];
		}

		throw new MissingControllerException(sprintf('Controller %s does not exist', $key));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getControllers() {
		return $this->_controllers;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getKey() {
		return $this->_key;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPath() {
		return $this->_path;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getResourcePath() {
		return $this->getPath() . '/resources/';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getViewPath() {
		return $this->getPath() . '/views/';
	}

	/**
	 * {@inheritdoc}
	 */
	public function setController($key, $class) {
		$this->_controllers[$key] = $class;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setControllers(array $controllers) {
		foreach ($controllers as $key => $class) {
			$this->setController($key, $class);
		}

		return $this;
	}

}