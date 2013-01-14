<?php
/**
 * Titon: A PHP 5.4 Modular Framework
 *
 * @copyright	Copyright 2010, Titon
 * @link		http://github.com/titon
 * @license		http://opensource.org/licenses/bsd-license.php (BSD License)
 */

namespace Titon\Mvc;

use Titon\Mvc\Engine;

/**
 * Interface for the helpers library.
 */
interface Helper {

	/**
	 * Triggered before a template is rendered by the engine.
	 *
	 * @access public
	 * @param \Titon\Mvc\Engine $engine
	 * @return void
	 */
	public function preRender(Engine $engine);

	/**
	 * Triggered after a template is rendered by the engine.
	 *
	 * @access public
	 * @param \Titon\Mvc\Engine $engine
	 * @return void
	 */
	public function postRender(Engine $engine);

}