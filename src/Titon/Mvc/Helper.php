<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc;

use Titon\Mvc\Engine;
use Titon\Mvc\View;

/**
 * Interface for the helpers library.
 */
interface Helper {

	/**
	 * Triggered before a template is rendered by the engine.
	 *
	 * @param \Titon\Mvc\View $view
	 * @param \Titon\Mvc\Engine $engine
	 * @param int $type
	 * @return void
	 */
	public function preRender(View $view, Engine $engine, $type);

	/**
	 * Triggered after a template is rendered by the engine.
	 *
	 * @param \Titon\Mvc\View $view
	 * @param \Titon\Mvc\Engine $engine
	 * @param int $type
	 * @return void
	 */
	public function postRender(View $view, Engine $engine, $type);

}