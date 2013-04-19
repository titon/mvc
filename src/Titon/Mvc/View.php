<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc;

use Titon\Common\Traits\Attachable;
use Titon\Common\Traits\Cacheable;
use Titon\Mvc\Helper;
use Titon\Mvc\Engine;
use Titon\Mvc\Engine\ViewEngine;
use Titon\Mvc\Exception;
use Titon\Utility\Inflector;

/**
 * The View acts as the hub between the templates and the rendering engine.
 * It keeps a mapping of template locations, helpers, data variables and more.
 */
class View {
	use Attachable, Cacheable;

	/**
	 * Constants for all the possible types of templates.
	 */
	const TEMPLATE = 1;
	const LAYOUT = 2;
	const WRAPPER = 3;
	const PARTIAL = 4;
	const CUSTOM = 5;

	/**
	 * Variable data for templates.
	 *
	 * @var array
	 */
	protected $_data = [];

	/**
	 * Template rendering engine.
	 *
	 * @var \Titon\Mvc\Engine
	 */
	protected $_engine;

	/**
	 * List of helpers.
	 *
	 * @var \Titon\Mvc\Helper[]
	 */
	protected $_helpers = [];

	/**
	 * List of template lookup paths.
	 *
	 * @var array
	 */
	protected $_paths = [];

	/**
	 * Add paths through the constructor.
	 *
	 * @param array $paths
	 */
	public function __construct(array $paths = []) {
		if ($paths) {
			$this->addPaths($paths);
		}
	}

	/**
	 * Added a view helper.
	 *
	 * @param string $key
	 * @param \Titon\Mvc\Helper $helper
	 * @return \Titon\Mvc\View
	 */
	public function addHelper($key, Helper $helper) {
		$this->_helpers[$key] = $helper;
		$this->_attached[$key] = $helper;

		return $this;
	}

	/**
	 * Add a template lookup path.
	 *
	 * @param string $path
	 * @return \Titon\Mvc\View
	 */
	public function addPath($path) {
		$this->_paths[] = $path;

		return $this;
	}

	/**
	 * Add multiple template lookup paths.
	 *
	 * @param array $paths
	 * @return \Titon\Mvc\View
	 */
	public function addPaths(array $paths) {
		foreach ($paths as $path) {
			$this->addPath($path);
		}

		return $this;
	}

	/**
	 * Return the rendering engine. Use the default if none was set.
	 *
	 * @return \Titon\Mvc\Engine
	 */
	public function getEngine() {
		if (!$this->_engine) {
			$this->setEngine(new ViewEngine());
		}

		return $this->_engine;
	}

	/**
	 * Return a helper by key.
	 *
	 * @param string $key
	 * @return \Titon\Mvc\Helper
	 * @throws \Titon\Mvc\Exception
	 */
	public function getHelper($key) {
		if (isset($this->_helpers[$key])) {
			return $this->_helpers[$key];
		}

		throw new Exception(sprintf('Helper %s does not exist', $key));
	}

	/**
	 * Return all helpers.
	 *
	 * @return \Titon\Mvc\Helper[]
	 */
	public function getHelpers() {
		return $this->_helpers;
	}

	/**
	 * Return all paths.
	 *
	 * @return array
	 */
	public function getPaths() {
		return $this->_paths;
	}

	/**
	 * Return a variable by key.
	 *
	 * @param string $key
	 * @return mixed
	 * @throws \Titon\Mvc\Exception
	 */
	public function getVariable($key) {
		return isset($this->_data[$key]) ? $this->_data[$key] : null;
	}

	/**
	 * Return all variables.
	 *
	 * @return array
	 */
	public function getVariables() {
		return $this->_data;
	}

	/**
	 * Locate a template within the lookup paths.
	 *
	 * @param array|string $template
	 * @param int $type
	 * @return string
	 * @throws \Titon\Mvc\Exception
	 */
	public function locateTemplate($template, $type = self::TEMPLATE) {
		return $this->cache([__METHOD__, $template, $type], function() use ($template, $type) {
			$paths = $this->getPaths();

			if (!$paths) {
				throw new Exception('No template lookup paths have been defined');
			}

			// Combine path parts
			if (is_array($template)) {
				$ext = isset($template['ext']) ? $template['ext'] : null;
				unset($template['ext']);

				$template = implode('/', $template);

				if ($ext) {
					$template .= '.' . $ext;
				}
			}

			// Determine parent path
			switch ($type) {
				case self::LAYOUT:
					$template = sprintf('/private/layouts/%s', $template);
				break;

				case self::WRAPPER:
					$template = sprintf('/private/wrappers/%s', $template);
				break;

				case self::PARTIAL:
					$template = sprintf('/private/includes/%s', $template);
				break;

				case self::TEMPLATE:
					$template = sprintf('/public/%s', $template);
				break;

				case self::CUSTOM:
				default:
					$template = sprintf('/private/%s', $template);
				break;
			}

			// Add template extension
			$ext = $this->getEngine()->getExtension();
			$extLen = strlen($ext) + 1;

			if (mb_substr($template, -$extLen) === '.' . $ext) {
				$template = mb_substr($template, 0, (mb_strlen($template) - $extLen));
			}

			$template .= '.' . $ext;

			// Locate absolute path
			foreach ($paths as $path) {
				if (file_exists($path . $template)) {
					return $path . $template;
				}
			}

			throw new Exception(sprintf('View template %s does not exist', $template));
		});
	}

	/**
	 * Triggered before a template renders.
	 *
	 * @param \Titon\Mvc\Engine $engine
	 * @param int $type
	 */
	public function preRender(Engine $engine, $type) {
		$this->notifyObjects('preRender', [$engine, $type]);
	}

	/**
	 * Triggered after a template renders.
	 *
	 * @param \Titon\Mvc\Engine $engine
	 * @param int $type
	 */
	public function postRender(Engine $engine, $type) {
		$this->notifyObjects('postRender', [$engine, $type]);
	}

	/**
	 * Render a single template.
	 *
	 * @param string $path
	 * @param array $variables
	 * @return string
	 */
	public function render($path, array $variables = []) {
		$engine = $this->getEngine();

		if ($helpers = $this->getHelpers()) {
			foreach ($helpers as $alias => $helper) {
				$engine->{$alias} = $helper;
			}
		}

		return $engine->render($path, $variables);
	}

	/**
	 * Render all templates in order: template -> wrapper(s) -> layout.
	 *
	 * @param string|array $template
	 * @return string
	 */
	public function run($template) {
		return $this->cache([__METHOD__, $template], function() use ($template) {
			if (is_string($template) || empty($template['template'])) {
				$template = ['template' => $template, 'type' => self::TEMPLATE];
			}

			$engine = $this->getEngine();
			$loop = [$template];

			if ($wrappers = $engine->getWrapper()) {
				foreach ($wrappers as $wrapper) {
					$loop[] = ['template' => $wrapper, 'type' => self::WRAPPER];
				}
			}

			if ($layout = $engine->getLayout()) {
				$loop[] = ['template' => $layout, 'type' => self::LAYOUT];
			}

			foreach ($loop as $options) {
				$template = $options['template'];
				$type = $options['type'];

				$this->preRender($engine, $type);

				$engine->setContent($this->render(
					$this->locateTemplate($template, $type),
					$this->getVariables()
				));

				$this->postRender($engine, $type);
			}

			return $engine->getContent();
		});
	}

	/**
	 * Set a view variable.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return \Titon\Mvc\View
	 */
	public function setVariable($key, $value) {
		$this->_data[Inflector::variable($key)] = $value;

		return $this;
	}

	/**
	 * Set multiple view variables.
	 *
	 * @param array $data
	 * @return \Titon\Mvc\View
	 */
	public function setVariables(array $data) {
		foreach ($data as $key => $value) {
			$this->setVariable($key, $value);
		}

		return $this;
	}

	/**
	 * Set the rendering engine.
	 *
	 * @param \Titon\Mvc\Engine $engine
	 * @return \Titon\Mvc\View
	 */
	public function setEngine(Engine $engine) {
		$engine->setView($this);

		$this->_engine = $engine;

		return $this;
	}

}