<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc\Engine;

use Titon\Common\Base;
use Titon\Mvc\Engine;
use Titon\Mvc\View;

/**
 * Defines shared functionality for view engines.
 */
abstract class AbstractEngine extends Base implements Engine {

	/**
	 * Current parsed template content.
	 *
	 * @var string
	 */
	protected $_content;

	/**
	 * Name of the layout template to wrap content with.
	 *
	 * @var string
	 */
	protected $_layout = 'default';

	/**
	 * View instance.
	 *
	 * @var \Titon\Mvc\View
	 */
	protected $_view;

	/**
	 * List of wrappers to wrap templates with.
	 *
	 * @var array
	 */
	protected $_wrapper = [];

	/**
	 * Return the currently parsed template content.
	 *
	 * @return string
	 */
	public function content() {
		return $this->_content;
	}

	/**
	 * Return the current layout.
	 *
	 * @return string
	 */
	public function getLayout() {
		return $this->_layout;
	}

	/**
	 * Return the list of wrappers.
	 *
	 * @return array
	 */
	public function getWrapper() {
		return $this->_wrapper;
	}

	/**
	 * Render a partial template at the defined path.
	 * Optionally can pass an array of custom variables.
	 *
	 * @param string $partial
	 * @param array $variables
	 * @return string
	 */
	public function open($partial, array $variables = []) {
		return $this->render(
			$this->_view->locateTemplate($partial, View::PARTIAL),
			$variables + $this->_view->getVariables()
		);
	}

	/**
	 * Set the parent view layer.
	 *
	 * @param \Titon\Mvc\View $view
	 * @return \Titon\Mvc\Engine
	 */
	public function setView(View $view) {
		$this->_view = $view;

		return $this;
	}

	/**
	 * Set the layout to use.
	 *
	 * @param string $name
	 * @return \Titon\Mvc\Engine
	 */
	public function useLayout($name) {
		$this->_layout = (string) $name;

		return $this;
	}

	/**
	 * Set the wrappers to use.
	 *
	 * @param string|array $name
	 * @return \Titon\Mvc\Engine
	 */
	public function wrapWith($name) {
		$this->_wrapper = (array) $name;

		return $this;
	}

}
