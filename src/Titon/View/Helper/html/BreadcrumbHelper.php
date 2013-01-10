<?php
/**
 * Titon: A PHP 5.4 Modular Framework
 *
 * @copyright	Copyright 2010, Titon
 * @link		http://github.com/titon
 * @license		http://opensource.org/licenses/bsd-license.php (BSD License)
 */

namespace Titon\View\Helper\Html;

use Titon\Common\Registry;
use Titon\View\Helper\AbstractHelper;

/**
 * The BreadcrumbHelper is primarily used for adding and generating breadcrumb lists.
 */
class BreadcrumbHelper extends AbstractHelper {

	/**
	 * A list of all breadcrumbs in the trail, with the title, url and attributes.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_breadcrumbs = [];

	/**
	 * Add a link to the breadcrumbs.
	 *
	 * @access public
	 * @param string $title
	 * @param string|array $url
	 * @param array $attributes
	 * @return \Titon\View\Helper\Html\BreadcrumbHelper
	 */
	public function add($title, $url, array $attributes = []) {
		$this->_breadcrumbs[] = [$title, $url, $attributes];

		return $this;
	}

	/**
	 * Return an array of breadcrumbs formatted as anchor links.
	 *
	 * @access public
	 * @return array
	 */
	public function generate() {
		$trail = [];

		if ($this->_breadcrumbs) {
			foreach ($this->_breadcrumbs as $crumb) {
				$trail[] = $this->html->anchor($crumb[0], $crumb[1], $crumb[2]);
			}
		}

		return $trail;
	}

	/**
	 * Attach the HtmlHelper.
	 *
	 * @access public
	 * @return void
	 */
	public function initialize() {
		$this->attachObject('html', function() {
			return Registry::factory('Titon\View\Helper\Html\HtmlHelper');
		});
	}

}