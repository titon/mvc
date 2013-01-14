<?php
/**
 * Titon: A PHP 5.4 Modular Framework
 *
 * @copyright	Copyright 2010, Titon
 * @link		http://github.com/titon
 * @license		http://opensource.org/licenses/bsd-license.php (BSD License)
 */

namespace Titon\Mvc\Helper\Xhtml;

use Titon\Common\Registry;
use Titon\Mvc\Helper\Html\AssetHelper as HtmlAssetHelper;

/**
 * The AssetHelper aids in the process of including external stylesheets and scripts.
 */
class AssetHelper extends HtmlAssetHelper {

	/**
	 * Attach the XhtmlHelper.
	 *
	 * @access public
	 * @return void
	 */
	public function initialize() {
		$this->attachObject('html', function() {
			return Registry::factory('Titon\Mvc\Helper\Xhtml\XhtmlHelper');
		});
	}

}