<?php
/**
 * Titon: A PHP 5.4 Modular Framework
 *
 * @copyright	Copyright 2010, Titon
 * @link		http://github.com/titon
 * @license		http://opensource.org/licenses/bsd-license.php (BSD License)
 */

namespace Titon\View\Engine;

use Titon\View\Engine\ViewEngine;
use \Exception;

/**
 * Test class for Titon\View\Engine\ViewEngine.
 */
class ViewEngineTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Test that open() renders includes and it's variables.
	 */
	public function testOpen() {
		$engine = new ViewEngine([
			'template' => [
				'module' => 'pages',
				'controller' => 'index',
				'action' => 'index',
				'ext' => null
			]
		]);
		$engine->addPath(TEMP_DIR);

		$engine->set('name', 'Titon');

		$this->assertEquals('include.tpl', $engine->open('include'));
		$this->assertEquals('include.tpl', $engine->open('include.tpl'));

		$this->assertEquals('nested/include.tpl', $engine->open('nested/include'));
		$this->assertEquals('nested/include.tpl', $engine->open('nested/include.tpl'));

		$data = [
			'filename' => 'variables.tpl',
			'type' => 'include'
		];

		$this->assertEquals('Titon - include - variables.tpl', $engine->open('variables', $data));
		$this->assertEquals('Titon - include - variables.tpl', $engine->open('variables.tpl', $data));
	}

	/**
	 * Test that run() renders the layout, wrapper, view and includes in the correct sequence.
	 */
	public function testRun() {
		$engine = new ViewEngine([
			'template' => [
				'module' => 'pages',
				'controller' => 'index',
				'action' => 'index',
				'ext' => null
			]
		]);
		$engine->addPath(TEMP_DIR);

		$this->assertEquals('<layout>index.tpl</layout>', $engine->run());

		// with wrapper
		$engine = new ViewEngine([
			'template' => [
				'module' => 'pages',
				'controller' => 'index',
				'action' => 'add',
				'ext' => null
			],
			'wrapper' => 'wrapper'
		]);
		$engine->addPath(TEMP_DIR);

		$this->assertEquals('<layout><wrapper>add.tpl</wrapper></layout>', $engine->run());

		// with fallback layout
		$engine = new ViewEngine([
			'template' => [
				'module' => 'pages',
				'controller' => 'index',
				'action' => 'edit',
				'ext' => null
			],
			'layout' => 'fallback'
		]);
		$engine->addPath(TEMP_DIR);
		$engine->addPath(TEMP_DIR . '/fallback');

		$this->assertEquals('<fallbackLayout>edit.tpl</fallbackLayout>', $engine->run());

		// with fallback wrapper
		$engine = new ViewEngine([
			'template' => [
				'module' => 'pages',
				'controller' => 'index',
				'action' => 'view',
				'ext' => null
			],
			'wrapper' => 'fallback'
		]);
		$engine->addPath(TEMP_DIR);
		$engine->addPath(TEMP_DIR . '/fallback');

		$this->assertEquals('<layout><fallbackWrapper>view.tpl</fallbackWrapper></layout>', $engine->run());

		// with include
		$engine = new ViewEngine([
			'template' => [
				'module' => 'pages',
				'controller' => 'index',
				'action' => 'test-include',
				'ext' => null
			]
		]);
		$engine->addPath(TEMP_DIR);

		$this->assertEquals('<layout>test-include.tpl nested/include.tpl</layout>', $engine->run());

		// with ext and no layout
		$engine = new ViewEngine([
			'template' => [
				'module' => 'pages',
				'controller' => 'index',
				'action' => 'view',
				'ext' => 'xml'
			],
			'layout' => null
		]);
		$engine->addPath(TEMP_DIR);

		$this->assertEquals('view.xml.tpl', $engine->run());

		// with ext and blank layout
		$engine = new ViewEngine([
			'template' => [
				'module' => 'pages',
				'controller' => 'index',
				'action' => 'view',
				'ext' => 'xml'
			],
			'layout' => 'blank'
		]);
		$engine->addPath(TEMP_DIR);

		$this->assertEquals('view.xml.tpl', $engine->run());
	}

}