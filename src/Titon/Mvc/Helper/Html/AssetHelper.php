<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc\Helper\Html;

use Titon\Common\Registry;
use Titon\Mvc\Helper\AbstractHelper;

/**
 * The AssetHelper aids in the process of including external stylesheets and scripts.
 */
class AssetHelper extends AbstractHelper {

	/**
	 * Default locations.
	 */
	const HEADER = 'header';
	const FOOTER = 'footer';

	/**
	 * A list of JavaScript files to include in the current page.
	 *
	 * @var array
	 */
	protected $_scripts = [];

	/**
	 * A list of CSS stylesheets to include in the current page.
	 *
	 * @var array
	 */
	protected $_stylesheets = [];

	/**
	 * Add a JavaScript file to the current page request.
	 *
	 * @param string $script
	 * @param string $location
	 * @param int $order
	 * @param int $env
	 * @return \Titon\Mvc\Helper\Html\AssetHelper
	 */
	public function addScript($script, $location = self::FOOTER, $order = null, $env = null) {
		if (mb_substr($script, -3) !== '.js') {
			$script .= '.js';
		}

		if (!isset($this->_scripts[$location])) {
			$this->_scripts[$location] = [];
		}

		if (!is_numeric($order)) {
			$order = count($this->_scripts[$location]);
		}

		while (isset($this->_scripts[$location][$order])) {
			$order++;
		}

		$this->_scripts[$location][$order] = [
			'path' => $script,
			'env' => $env
		];

		return $this;
	}

	/**
	 * Add a CSS stylesheet to the current page request.
	 *
	 * @param string $sheet
	 * @param string $media
	 * @param int $order
	 * @param int $env
	 * @return \Titon\Mvc\Helper\Html\AssetHelper
	 */
	public function addStylesheet($sheet, $media = 'screen', $order = null, $env = null) {
		if (mb_substr($sheet, -4) !== '.css') {
			$sheet .= '.css';
		}

		if (!is_numeric($order)) {
			$order = count($this->_stylesheets);
		}

		while (isset($this->_stylesheets[$order])) {
			$order++;
		}

		$this->_stylesheets[$order] = [
			'path' => $sheet,
			'media' => $media,
			'env' => $env
		];

		return $this;
	}

	/**
	 * Attach the HtmlHelper.
	 *
	 * @return void
	 */
	public function initialize() {
		$this->attachObject('html', function() {
			return Registry::factory('Titon\Mvc\Helper\Html\HtmlHelper');
		});
	}

	/**
	 * Return all the attached scripts. Uses the HTML helper to build the HTML tags.
	 *
	 * @param string $location
	 * @param string $env
	 * @return string
	 */
	public function scripts($location = self::FOOTER, $env = null) {
		$output = null;

		if (!empty($this->_scripts[$location])) {
			$scripts = $this->_scripts[$location];
			ksort($scripts);

			foreach ($scripts as $script) {
				if ($script['env'] === null || $script['env'] === $env) {
					$output .= $this->html->script($script['path']);
				}
			}
		}

		return $output;
	}

	/**
	 * Return all the attached stylesheets. Uses the HTML helper to build the HTML tags.
	 *
	 * @param string $env
	 * @return string
	 */
	public function stylesheets($env = null) {
		$output = null;

		if ($this->_stylesheets) {
			$stylesheets = $this->_stylesheets;
			ksort($stylesheets);

			foreach ($stylesheets as $sheet) {
				if ($sheet['env'] === null || $sheet['env'] === $env) {
					$output .= $this->html->link($sheet['path'], ['media' => $sheet['media']]);
				}
			}
		}

		return $output;
	}

}