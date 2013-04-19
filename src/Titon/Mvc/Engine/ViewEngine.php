<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc\Engine;

use Titon\Mvc\View;
use Titon\Mvc\Engine\AbstractEngine;

/**
 * Standard engine used for rendering views using pure PHP code.
 */
class ViewEngine extends AbstractEngine {

	/**
	 * Render a template at the defined path.
	 * Optionally can pass an array of custom variables.
	 *
	 * @param string $path
	 * @param array $variables
	 * @return string
	 */
	public function render($path, array $variables = []) {
		if ($variables) {
			extract($variables, EXTR_OVERWRITE);
		}

		ob_start();

		include $path;

		return ob_get_clean();
	}

}