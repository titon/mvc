<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc\Helper\Html;

use Titon\Mvc\Helper\Html\BreadcrumbHelper;
use Titon\Test\TestCase;

/**
 * Test class for Titon\Mvc\Helper\Html\BreadcrumbHelper.
 */
class BreadcrumbHelperTest extends TestCase {

	/**
	 * Test with a single crumb.
	 */
	public function testOneCrumb() {
		$helper = new BreadcrumbHelper();
		$helper->add('Title', '/');

		$this->assertEquals([
			'<a href="/">Title</a>' . PHP_EOL
		], $helper->generate());
	}

	/**
	 * Test with multiple crumbs, attributes and dynamic URLs.
	 */
	public function testMultipleCrumbs() {
		$helper = new BreadcrumbHelper();
		$helper
			->add('Title', '/')
			->add('Title 2', '/static/url', ['class' => 'tier2'])
			->add('Title 3', ['action' => 'view', 123], ['class' => 'tier3']);

		$this->assertEquals([
			'<a href="/">Title</a>' . PHP_EOL,
			'<a class="tier2" href="/static/url">Title 2</a>' . PHP_EOL,
			'<a class="tier3" href="/common/index/view/123">Title 3</a>' . PHP_EOL
		], $helper->generate());
	}

}