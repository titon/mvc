<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
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
	 * @param \Titon\Mvc\Engine $engine
	 * @return void
	 */
	public function preRender(Engine $engine);

	/**
	 * Triggered after a template is rendered by the engine.
	 *
	 * @param \Titon\Mvc\Engine $engine
	 * @return void
	 */
	public function postRender(Engine $engine);

}