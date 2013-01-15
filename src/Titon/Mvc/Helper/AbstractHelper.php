<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc\Helper;

use Titon\Common\Base;
use Titon\Common\Traits\Attachable;
use Titon\Route\Router;
use Titon\Utility\Sanitize;
use Titon\Utility\String;
use Titon\Mvc\Engine;
use Titon\Mvc\Helper;
use Titon\Mvc\View;

/**
 * The Helper class acts as the base for all children helpers to extend.
 * Defines methods and properties for HTML tags and attribute generation.
 */
abstract class AbstractHelper extends Base implements Helper {
	use Attachable;

	/**
	 * Mapping of HTML tags.
	 *
	 * @var array
	 */
	protected $_tags = [];

	/**
	 * Engine object.
	 *
	 * @var \Titon\Mvc\Engine
	 */
	protected $_engine;

	/**
	 * View object.
	 *
	 * @var \Titon\Mvc\View
	 */
	protected $_view;

	/**
	 * Parses an array of attributes to the HTML equivalent.
	 *
	 * @param array $attributes
	 * @param array $remove
	 * @return string
	 */
	public function attributes(array $attributes, array $remove = []) {
		$parsed = '';
		$escape = true;

		if (isset($attributes['escape'])) {
			$escape = $attributes['escape'];
			unset($attributes['escape']);
		}

		if ($attributes) {
			ksort($attributes);

			foreach ($attributes as $key => $value) {
				if (in_array($key, $remove)) {
					unset($attributes[$key]);
					continue;
				}

				if ((is_array($escape) && !in_array($key, $escape)) || ($escape === true)) {
					$value = $this->escape($value, true);
				}

				$parsed .= ' ' . mb_strtolower($key) . '="' . $value . '"';
			}
		}

		return $parsed;
	}

	/**
	 * Escape a value.
	 *
	 * @param string $value
	 * @param boolean|null $escape
	 * @return string
	 */
	public function escape($value, $escape = null) {
		if ($escape === null) {
			try {
				$escape = $this->config->get('escape');
			} catch (\Exception $e) {
				$escape = true;
			}
		}

		if ($escape) {
			$value = Sanitize::escape($value);
		}

		return $value;
	}

	/**
	 * Triggered before a template is rendered by the engine.
	 *
	 * @param \Titon\Mvc\View $view
	 * @param \Titon\Mvc\Engine $engine
	 * @param int $type
	 * @return void
	 */
	public function preRender(View $view, Engine $engine, $type) {
		$this->_view = $view;
		$this->_engine = $engine;
	}

	/**
	 * Triggered after a template is rendered by the engine.
	 *
	 * @param \Titon\Mvc\View $view
	 * @param \Titon\Mvc\Engine $engine
	 * @param int $type
	 * @return void
	 */
	public function postRender(View $view, Engine $engine, $type) {
		$this->_view = $view;
		$this->_engine = $engine;
	}

	/**
	 * Generates an HTML tag if it exists.
	 *
	 * @param string $tag
	 * @param array $params
	 * @return string
	 */
	public function tag($tag, array $params = []) {
		return String::insert($this->_tags[$tag], $params, ['escape' => false]) . PHP_EOL;
	}

	/**
	 * Return a router generated URL.
	 *
	 * @param mixed $url
	 * @return string
	 */
	public function url($url = '/') {
		return Router::detect($url);
	}

}