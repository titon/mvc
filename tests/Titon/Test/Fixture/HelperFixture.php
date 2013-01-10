<?php
/**
 * Titon: A PHP 5.4 Modular Framework
 *
 * @copyright	Copyright 2010, Titon
 * @link		http://github.com/titon
 * @license		http://opensource.org/licenses/bsd-license.php (BSD License)
 */

namespace Titon\Test\Fixture;

use Titon\View\Helper\AbstractHelper;

/**
 * Fixture for Titon\View\Helper.
 */
class HelperFixture extends AbstractHelper {

	protected $_tags = [
		'noattr' => '<tag>{body}</tag>',
		'nobody' => '<tag{attr} />',
		'custom' => '<tag {one} {two}>{three}</tag>',
		'default' => '<tag {0}>{1}</tag>{2}'
	];

}