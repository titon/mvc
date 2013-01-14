<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
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
	 * @return void
	 */
	public function initialize() {
		$this->attachObject('html', function() {
			return Registry::factory('Titon\Mvc\Helper\Xhtml\XhtmlHelper');
		});
	}

}