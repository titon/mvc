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
 * The BreadcrumbHelper is primarily used for adding and generating breadcrumb lists.
 */
class BreadcrumbHelper extends AbstractHelper {

	/**
	 * A list of all breadcrumbs in the trail, with the title, url and attributes.
	 *
	 * @var array
	 */
	protected $_breadcrumbs = [];

	/**
	 * Add a link to the breadcrumbs.
	 *
	 * @param string $title
	 * @param string|array $url
	 * @param array $attributes
	 * @return \Titon\Mvc\Helper\Html\BreadcrumbHelper
	 */
	public function add($title, $url, array $attributes = []) {
		$this->_breadcrumbs[] = [$title, $url, $attributes];

		return $this;
	}

	/**
	 * Return an array of breadcrumbs formatted as anchor links.
	 *
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
	 */
	public function initialize() {
		$this->attachObject('html', function() {
			return Registry::factory('Titon\Mvc\Helper\Html\HtmlHelper');
		});
	}

}