<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc\Helper\Xhtml;

use Titon\Common\Registry;
use Titon\Mvc\Helper\Html\BreadcrumbHelper as HtmlBreadcrumbHelper;

/**
 * The BreadcrumbHelper is primarily used for adding and generating breadcrumb lists.
 */
class BreadcrumbHelper extends HtmlBreadcrumbHelper {

	/**
	 * Attach the XhtmlHelper.
	 *
	 * @return void
	 */
	public function initialize() {
		$this->attachObject('html', function() {
			return Registry::factory('Titon\Mvc\Helper\Xhtml\XhtmlHelper');
		});
	}

}