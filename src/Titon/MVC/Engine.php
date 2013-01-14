<?php
/**
 * Titon: A PHP 5.4 Modular Framework
 *
 * @copyright	Copyright 2010, Titon
 * @link		http://github.com/titon
 * @license		http://opensource.org/licenses/bsd-license.php (BSD License)
 */

namespace Titon\Mvc;

use Titon\Mvc\Helper;

/**
 * Interface for the engines library.
 */
interface Engine {

	/**
	 * Add a helper to the view rendering engine.
	 *
	 * @access public
	 * @param string $key
	 * @param \Titon\Mvc\Helper $helper
	 * @return \Titon\Mvc\Engine
	 */
	public function addHelper($key, Helper $helper);

	/**
	 * Add a template lookup path.
	 *
	 * @access public
	 * @param string|array $paths
	 * @return \Titon\Mvc\Engine
	 */
	public function addPath($paths);

	/**
	 * Get the file path for a type of template: layout, wrapper, view, partial and custom.
	 *
	 * @access public
	 * @param int $type
	 * @param string $path
	 * @return string
	 */
	public function buildPath($type, $path = null);

	/**
	 * The current output within the rendering process. The output changes depending on the current rendering stage.
	 *
	 * @access public
	 * @return string
	 */
	public function content();

	/**
	 * Return the data based on the given key, or return all data.
	 *
	 * @access public
	 * @param string $key
	 * @return string
	 */
	public function get($key = null);

	/**
	 * Return a single helper by key.
	 *
	 * @access public
	 * @param string $key
	 * @return \Titon\Mvc\Helper
	 */
	public function getHelper($key);

	/**
	 * Return all the helpers.
	 *
	 * @access public
	 * @return \Titon\Mvc\Helper[]
	 */
	public function getHelpers();

	/**
	 * Return all the template lookup paths.
	 *
	 * @access public
	 * @return array
	 */
	public function getPaths();

	/**
	 * Opens and renders a partial view element within the current document.
	 *
	 * @access public
	 * @param string $path
	 * @param array $variables
	 * @return string
	 */
	public function open($path, array $variables = []);

	/**
	 * Primary method to render a single view template.
	 *
	 * @access public
	 * @param string $path
	 * @param array $variables
	 * @return string
	 */
	public function render($path, array $variables = []);

	/**
	 * Begins the staged rendering process.
	 * First stage, the system must render the template based on the module, controller and action path.
	 * Second stage, wrap the first template in any wrappers.
	 * Third stage, wrap the current template output with the layout.
	 * Return the final result.
	 *
	 * @access public
	 * @param boolean $cache
	 * @return string
	 */
	public function run($cache = true);

	/**
	 * Set a variable to the view. The variable name will be inflected if it is invalid.
	 *
	 * @access public
	 * @param string|array $key
	 * @param mixed $value
	 * @return \Titon\Mvc\Engine
	 */
	public function set($key, $value = null);

}
