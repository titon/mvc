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
	 * Return the view manager.
	 *
	 * @return \Titon\Mvc\View
	 */
	public function getView();

	/**
	 * Return the rendering engine.
	 *
	 * @return \Titon\Mvc\Engine
	 */
	public function getEngine();

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

	/**
	 * Set the view manager.
	 *
	 * @param \Titon\Mvc\View $view
	 * @return \Titon\Mvc\Helper
	 */
	public function setView(View $view);

	/**
	 * Set the rendering engine.
	 *
	 * @param \Titon\Mvc\Engine $engine
	 * @return \Titon\Mvc\Helper
	 */
	public function setEngine(Engine $engine);

}