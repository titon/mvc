<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc;

/**
 * Interface for the engines library.
 */
interface Engine {

	/**
	 * Return the currently parsed template content.
	 *
	 * @return string
	 */
	public function getContent();

	/**
	 * Return the template file extension.
	 *
	 * @return string
	 */
	public function getExtension();

	/**
	 * Return the current layout.
	 *
	 * @return string
	 */
	public function getLayout();

	/**
	 * Return the list of wrappers.
	 *
	 * @return array
	 */
	public function getWrapper();

	/**
	 * Return the view instance.
	 *
	 * @return \Titon\Mvc\View
	 */
	public function getView();

	/**
	 * Render a partial template at the defined path.
	 * Optionally can pass an array of custom variables.
	 *
	 * @param string $partial
	 * @param array $variables
	 * @return string
	 */
	public function open($partial, array $variables = []);

	/**
	 * Render a template at the defined path.
	 * Optionally can pass an array of custom variables.
	 *
	 * @param string $path
	 * @param array $variables
	 * @return string
	 */
	public function render($path, array $variables = []);

	/**
	 * Set the content.
	 *
	 * @param string $content
	 * @return \Titon\Mvc\Engine
	 */
	public function setContent($content);

	/**
	 * Set the parent view layer.
	 *
	 * @param \Titon\Mvc\View $view
	 * @return \Titon\Mvc\Engine
	 */
	public function setView(View $view);

	/**
	 * Set the layout to use.
	 *
	 * @param string $name
	 * @return \Titon\Mvc\Engine
	 */
	public function useLayout($name);

	/**
	 * Set the wrappers to use.
	 *
	 * @param string|array $name
	 * @return \Titon\Mvc\Engine
	 */
	public function wrapWith($name);

}
