<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc\Engine;

use Titon\Common\Base;
use Titon\Common\Traits\Attachable;
use Titon\Common\Traits\Cacheable;
use Titon\Utility\Inflector;
use Titon\Utility\Loader;
use Titon\Utility\Hash;
use Titon\Mvc\Engine;
use Titon\Mvc\Helper;
use Titon\Mvc\Exception;

/**
 * The Engine acts as a base for all child Engines to inherit. The view engine acts as the renderer of data
 * (set by the controller) to markup (the view templates), using a templating system.
 * The order of process is as follows:
 *
 *  - The engine inherits the configuration and variables that were set in the Controller
 *  - The engine applies the configuration and loads any defined helpers and classes
 *  - Once loaded, begins the staged rendering process
 *  - Will trigger any callbacks and shutdown
 */
abstract class AbstractEngine extends Base implements Engine {
	use Cacheable, Attachable;

	/**
	 * Constants for all the possible types of templates.
	 */
	const VIEW = 1;
	const LAYOUT = 2;
	const WRAPPER = 3;
	const PARTIAL = 4;
	const CUSTOM = 5;

	/**
	 * Configuration. Can be overwritten in the Controller.
	 *
	 *	type 		- The content type to respond as (defaults to html)
	 *	template 	- An array containing the module, controller, and action
	 *	render 		- Toggle the rendering process
	 *	layout 		- The layout template to use
	 *	wrapper 	- The wrapper template to use
	 *	custom		- Custom folder name for private templates
	 * 	ext			- The view template file extension
	 *
	 * @var array
	 */
	protected $_config = [
		'type' => 'html',
		'template' => [
			'module' => null,
			'controller' => null,
			'action' => null,
			'ext' => null
		],
		'render' => true,
		'layout' => 'default',
		'wrapper' => null,
		'custom' => null,
		'ext' => 'tpl'
	];

	/**
	 * The rendered content used within the wrapper or the layout.
	 *
	 * @var string
	 */
	protected $_content = null;

	/**
	 * Data to pass as variables to each template.
	 *
	 * @var array
	 */
	protected $_data = [];

	/**
	 * List of added helpers.
	 *
	 * @var \Titon\Mvc\Helper[]
	 */
	protected $_helpers = [];

	/**
	 * Template lookup paths.
	 *
	 * @var array
	 */
	protected $_paths = [];

	/**
	 * Has the view been rendered.
	 *
	 * @var boolean
	 */
	protected $_rendered = false;

	/**
	 * Add a helper to the view rendering engine.
	 *
	 * @param string $key
	 * @param \Titon\Mvc\Helper $helper
	 * @return \Titon\Mvc\Engine
	 */
	public function addHelper($key, Helper $helper) {
		$this->_helpers[$key] = $helper;

		// Add to Attachable also
		$this->_attached[$key] = $helper;

		return $this;
	}

	/**
	 * Add a template lookup path.
	 *
	 * @param string|array $paths
	 * @return \Titon\Mvc\Engine
	 */
	public function addPath($paths) {
		foreach ((array) $paths as $path) {
			$this->_paths[] = $path;
		}

		return $this;
	}

	/**
	 * Get the file path for a type of template: layout, wrapper, view, partial.
	 *
	 * @param int $type
	 * @param string $path
	 * @return string
	 * @throws \Titon\Mvc\Exception
	 */
	public function buildPath($type, $path = null) {
		$paths = $this->getPaths();
		$config = $this->config->all();
		$template = $config['template'];
		$view = null;

		if (!$paths) {
			throw new Exception('No template lookup paths have been defined');
		}

		switch ($type) {
			case self::LAYOUT:
				if ($config['layout']) {
					$view = sprintf('/private/layouts/%s', $this->_preparePath($config['layout'], $template['ext']));
				}
			break;

			case self::WRAPPER:
				if ($config['wrapper']) {
					$view = sprintf('/private/wrappers/%s', $this->_preparePath($config['wrapper']));
				}
			break;

			case self::PARTIAL:
				$view = sprintf('/private/includes/%s', $this->_preparePath($path));
			break;

			case self::VIEW:
				$view = sprintf('/public/%s/%s', $template['controller'], $this->_preparePath($template['action'], $template['ext']));
			break;

			case self::CUSTOM:
			default:
				$view = sprintf('/private/%s/%s', $path ?: $config['custom'], $this->_preparePath($template['action']));
			break;
		}

		foreach ($paths as $path) {
			if (file_exists($path . $view)) {
				return $path . $view;
			}
		}

		throw new Exception(sprintf('View template %s does not exist', $view));
	}

	/**
	 * The output of the rendering process. The output changes depending on the current rendering stage.
	 *
	 * @return string
	 */
	public function content() {
		return $this->_content;
	}

	/**
	 * Return the data based on the given key, or return all data.
	 *
	 * @param string $key
	 * @return string
	 */
	public function get($key = null) {
		return Hash::get($this->_data, $key);
	}

	/**
	 * Return a single helper by key.
	 *
	 * @param string $key
	 * @return \Titon\Mvc\Helper
	 */
	public function getHelper($key) {
		return $this->getObject($key);
	}

	/**
	 * Return all the helpers.
	 *
	 * @return \Titon\Mvc\Helper[]
	 */
	public function getHelpers() {
		return $this->_helpers;
	}

	/**
	 * Return all the template lookup paths.
	 *
	 * @return array
	 */
	public function getPaths() {
		return $this->_paths;
	}

	/**
	 * Set a variable to the view. The variable name will be inflected if it is invalid.
	 *
	 * @param string|array $key
	 * @param mixed $value
	 * @return \Titon\Mvc\Engine
	 */
	public function set($key, $value = null) {
		if (is_array($key)) {
			foreach ($key as $k => $v) {
				$this->set($k, $v);
			}
		} else {
			$this->_data[Inflector::variable($key)] = $value;
		}

		return $this;
	}

	/**
	 * Custom method to overwrite and configure the view engine manually.
	 *
	 * @param mixed $options
	 * @return \Titon\Mvc\Engine
	 */
	public function setup($options) {
		if ($options === false || $options === true || $options === null) {
			$this->config->render = (bool) $options;

		} else if (is_string($options)) {
			$this->config->set('template.action', $options);

		} else if (is_array($options)) {
			foreach ($options as $key => $value) {
				if ($key === 'template') {
					if (is_array($value)) {
						$this->config->template = $value + $this->config->template;
					} else {
						$this->config->set('template.action', $value);
					}
				} else {
					$this->config->set($key, $value);
				}
			}
		}

		return $this;
	}

	/**
	 * Prepare a path by converting slashes and removing .tpl.
	 *
	 * @param string $path
	 * @param string $ext
	 * @return string
	 */
	protected function _preparePath($path, $ext = null) {
		return $this->cache([__METHOD__, $path], function() use ($path, $ext) {
			$tplExt = $this->config->ext;
			$extLen = strlen($tplExt) + 1;

			// Remove template extension
			if (mb_substr($path, -$extLen) === '.' . $tplExt) {
				$path = mb_substr($path, 0, (mb_strlen($path) - $extLen));
			}

			// Type extension like html
			if ($ext) {
				$path .= '.' . $ext;
			}

			// Template extension like tpl
			$path .= '.' . $tplExt;

			return $path;
		});
	}

}
