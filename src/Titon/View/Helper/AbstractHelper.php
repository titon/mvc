<?php
/**
 * Titon: A PHP 5.4 Modular Framework
 *
 * @copyright	Copyright 2010, Titon
 * @link		http://github.com/titon
 * @license		http://opensource.org/licenses/bsd-license.php (BSD License)
 */

namespace Titon\View\Helper;

use Titon\Common\Base;
use Titon\Common\Traits\Attachable;
use Titon\Utility\Sanitize;
use Titon\Utility\String;
use Titon\View\Engine;
use Titon\View\Helper;

/**
 * The Helper class acts as the base for all children helpers to extend.
 * Defines methods and properties for HTML tags and attribute generation.
 */
abstract class AbstractHelper extends Base implements Helper {
	use Attachable;

	/**
	 * Mapping of HTML tags.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_tags = [];

	/**
	 * Engine object.
	 *
	 * @access protected
	 * @var \Titon\View\Engine
	 */
	protected $_engine;

	/**
	 * Parses an array of attributes to the HTML equivalent.
	 *
	 * @access public
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
	 * @access public
	 * @param string $value
	 * @param boolean|null $escape
	 * @return string
	 */
	public function escape($value, $escape = null) {
		if ($escape === null) {
			$escape = $this->config->get('escape') ?: true;
		}

		if ($escape) {
			$value = Sanitize::escape($value);
		}

		return $value;
	}

	/**
	 * Triggered before a template is rendered by the engine.
	 *
	 * @access public
	 * @param \Titon\View\Engine $engine
	 * @return void
	 */
	public function preRender(Engine $engine) {
		$this->_engine = $engine;
	}

	/**
	 * Triggered after a template is rendered by the engine.
	 *
	 * @access public
	 * @param \Titon\View\Engine $engine
	 * @return void
	 */
	public function postRender(Engine $engine) {
		$this->_engine = $engine;
	}

	/**
	 * Generates an HTML tag if it exists.
	 *
	 * @access public
	 * @param string $tag
	 * @param array $params
	 * @return string
	 */
	public function tag($tag, array $params = []) {
		return String::insert($this->_tags[$tag], $params, ['escape' => false]) . "\n";
	}

	/**
	 * Return a router generated URL.
	 *
	 * @access public
	 * @param mixed $url
	 * @return string
	 */
	public function url($url = '/') {
		// @TODO
		//return Router::detect($url);
	}

}