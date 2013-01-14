<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc\Engine;

use Titon\Mvc\Engine\AbstractEngine;

/**
 * Standard engine used for rendering views using pure PHP code.
 */
class ViewEngine extends AbstractEngine {

	/**
	 * Opens and renders a partial view element within the current document.
	 *
	 * @param string $path
	 * @param array $variables
	 * @return string
	 */
	public function open($path, array $variables = []) {
		return $this->render($this->buildPath(self::PARTIAL, $path), $variables + $this->get());
	}

	/**
	 * Primary method to render a single view template.
	 *
	 * @param string $path
	 * @param array $variables
	 * @return string
	 */
	public function render($path, array $variables = []) {
		if ($variables) {
			extract($variables, EXTR_SKIP);
		}

		ob_start();

		include $path;

		return ob_get_clean();
	}

	/**
	 * Begins the staged rendering process. First stage, the system must render the template based on the module,
	 * controller and action path. Second stage, wrap the first template in any wrappers. Third stage,
	 * wrap the current template output with the layout. Return the final result.
	 *
	 * @param boolean $cache
	 * @return string
	 * @throws \Titon\Mvc\Exception
	 */
	public function run($cache = true) {
		$config = $this->config->all();
		$data = $this->get();

		if (!$config['render'] || ($cache && $this->_rendered)) {
			return $this->_content;
		}

		$this->notifyObjects('preRender');

		// Determine whether to render a custom or regular template
		$renderLoop = [];

		if ($config['custom']) {
			$renderLoop[self::CUSTOM] = 'custom';
		} else {
			$renderLoop[self::VIEW] = 'template';
		}

		// Render the layout and wrappers
		$renderLoop[self::WRAPPER] = 'wrapper';
		$renderLoop[self::LAYOUT] = 'layout';

		foreach ($renderLoop as $type => $template) {
			if (empty($config[$template])) {
				continue;
			}

			// Only if the file exists, in case wrapper or layout isn't being used
			if ($path = $this->buildPath($type)) {
				$this->_content = $this->render($path, $data);
			}
		}

		$this->notifyObjects('postRender');

		$this->_rendered = true;

		return $this->_content;
	}

}