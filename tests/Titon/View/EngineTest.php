<?php
/**
 * Titon: A PHP 5.4 Modular Framework
 *
 * @copyright	Copyright 2010, Titon
 * @link		http://github.com/titon
 * @license		http://opensource.org/licenses/bsd-license.php (BSD License)
 */

namespace Titon\View;

use Titon\Test\Fixture\EngineFixture;
use Titon\View\Helper\Html\HtmlHelper;
use \Exception;

/**
 * Test class for Titon\View\Engine.
 */
class EngineTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Test that addHelper() loads helpers, and throws exceptions when not a helper.
	 * Test that getHelper() returns the aliased helper names.
	 */
	public function testAddHelperAndGetHelper() {
		$engine = new EngineFixture();
		$engine->addHelper('html', new HtmlHelper());

		$this->assertInstanceOf('Titon\View\Helper', $engine->html);
		$this->assertInstanceOf('Titon\View\Helper', $engine->getHelper('html'));
	}

	/**
	 * Test that buildPath() generates correct file system paths, or throws exceptions.
	 */
	public function testBuildPath() {
		// views
		$engine = new EngineFixture([
			'template' => [
				'module' => 'pages',
				'controller' => 'index',
				'action' => 'index',
				'ext' => null
			]
		]);
		$engine->addPath(TEMP_DIR);

		$this->assertEquals(TEMP_DIR . '/public/index/index.tpl', $engine->buildPath(EngineFixture::VIEW));

		$engine->config->set('template.action', 'add');
		$this->assertEquals(TEMP_DIR . '/public/index/add.tpl', $engine->buildPath(EngineFixture::VIEW));

		$engine->config->set('template.action', 'view');
		$engine->config->set('template.ext', 'xml');
		$this->assertEquals(TEMP_DIR . '/public/index/view.xml.tpl', $engine->buildPath(EngineFixture::VIEW));

		try {
			$engine->config->set('template.controller', 'invalidFile');
			$engine->buildPath(EngineFixture::VIEW); // file doesn't exist
			$this->assertTrue(false);

		} catch (Exception $e) {
			$this->assertTrue(true);
		}

		// layouts
		$engine = new EngineFixture([
			'template' => [
				'module' => 'pages',
				'controller' => 'index',
				'action' => 'index',
				'ext' => null
			]
		]);
		$engine->addPath(TEMP_DIR);
		$engine->addPath(TEMP_DIR . '/fallback');

		$this->assertEquals(TEMP_DIR . '/private/layouts/default.tpl', $engine->buildPath(EngineFixture::LAYOUT));

		$engine->config->layout = 'fallback';
		$this->assertEquals(TEMP_DIR . '/fallback/private/layouts/fallback.tpl', $engine->buildPath(EngineFixture::LAYOUT));

		try {
			$engine->config->layout = 'invalidFile';
			$engine->buildPath(EngineFixture::LAYOUT); // file doesn't exist
			$this->assertTrue(false);

		} catch (Exception $e) {
			$this->assertTrue(true);
		}

		try {
			$engine->config->layout = null;
			$engine->buildPath(EngineFixture::LAYOUT); // file doesn't exist
			$this->assertTrue(false);

		} catch (Exception $e) {
			$this->assertTrue(true);
		}

		// errors
		$engine = new EngineFixture([
			'template' => [
				'module' => 'pages',
				'controller' => 'index',
				'action' => 'error',
				'ext' => null
			],
			'folder' => 'errors'
		]);
		$engine->addPath(TEMP_DIR);
		$engine->addPath(TEMP_DIR . '/fallback');

		$this->assertEquals(TEMP_DIR . '/private/errors/error.tpl', $engine->buildPath(EngineFixture::CUSTOM, 'errors'));

		$engine->config->set('template.action', 404);
		$this->assertEquals(TEMP_DIR . '/private/errors/404.tpl', $engine->buildPath(EngineFixture::CUSTOM, 'errors'));

		// wrappers
		$engine = new EngineFixture([
			'template' => [
				'module' => 'pages',
				'controller' => 'index',
				'action' => 'index',
				'ext' => null
			],
			'wrapper' => 'wrapper'
		]);
		$engine->addPath(TEMP_DIR);
		$engine->addPath(TEMP_DIR . '/fallback');

		$this->assertEquals(TEMP_DIR . '/private/wrappers/wrapper.tpl', $engine->buildPath(EngineFixture::WRAPPER));

		$engine->config->wrapper = 'fallback';
		$this->assertEquals(TEMP_DIR . '/fallback/private/wrappers/fallback.tpl', $engine->buildPath(EngineFixture::WRAPPER));

		try {
			$engine->config->wrapper = 'invalidFile';
			$engine->buildPath(EngineFixture::WRAPPER); // file doesn't exist
			$this->assertTrue(false);

		} catch (Exception $e) {
			$this->assertTrue(true);
		}

		try {
			$engine->config->wrapper = null;
			$engine->buildPath(EngineFixture::WRAPPER); // file doesn't exist
			$this->assertTrue(false);

		} catch (Exception $e) {
			$this->assertTrue(true);
		}

		// includes
		$engine = new EngineFixture([
			'template' => [
				'module' => 'pages',
				'controller' => 'index',
				'action' => 'index',
				'ext' => null
			],
			'wrapper' => 'wrapper'
		]);
		$engine->addPath(TEMP_DIR);

		$this->assertEquals(TEMP_DIR . '/private/includes/include.tpl', $engine->buildPath(EngineFixture::PARTIAL, 'include'));
		$this->assertEquals(TEMP_DIR . '/private/includes/nested/include.tpl', $engine->buildPath(EngineFixture::PARTIAL, 'nested/include'));
		$this->assertEquals(TEMP_DIR . '/private/includes/nested\include.tpl', $engine->buildPath(EngineFixture::PARTIAL, 'nested\include'));
		$this->assertEquals(TEMP_DIR . '/private/includes/nested/include.tpl', $engine->buildPath(EngineFixture::PARTIAL, 'nested/include.tpl'));

		try {
			$engine->buildPath(EngineFixture::PARTIAL, 'invalidFile');
			$this->assertTrue(false);

		} catch (Exception $e) {
			$this->assertTrue(true);
		}
	}

	/**
	 * Test that get() and set() handle data correctly. Variable names are inflected to be usable in the page.
	 */
	public function testGetAndSet() {
		$data = [
			'integer' => 123,
			'boolean' => true,
			'string' => 'abc',
			'null' => null,
			'array' => [
				'foo' => 'bar',
				123
			],
			'invalid key' => 'value',
			'123 numeric' => 456,
			'invalid #$*)#)_#@ chars' => false
		];

		$engine = new EngineFixture();
		$engine->set($data);

		$this->assertEquals([
			'integer' => 123,
			'boolean' => true,
			'string' => 'abc',
			'null' => null,
			'array' => [
				'foo' => 'bar',
				123
			],
			'invalidkey' => 'value',
			'_123numeric' => 456,
			'invalid_chars' => false
		], $engine->get());

		$engine->set('array', []);
		$this->assertEquals([], $engine->get('array'));

		$engine->set('123456789', true);
		$this->assertEquals(true, $engine->get('_123456789'));

		$this->assertEquals(null, $engine->get('fakeKey'));
	}

	/**
	 * Test that setup() overwrites configuration depending on the type passed.
	 */
	public function testSetup() {
		$engine = new EngineFixture([
			'template' => [
				'module' => 'pages',
				'controller' => 'index',
				'action' => 'index',
				'ext' => null
			]
		]);

		// toggle rendering
		$engine->setup(false);
		$this->assertFalse($engine->config->render);

		$engine->setup(null);
		$this->assertFalse($engine->config->render);

		$engine->setup(true);
		$this->assertTrue($engine->config->render);

		// change action
		$this->assertEquals('index', $engine->config->template['action']);

		$engine->setup('foo');
		$this->assertEquals('foo', $engine->config->template['action']);

		$engine->setup('bar');
		$this->assertEquals('bar', $engine->config->template['action']);

		// change action via array
		$engine->setup([
			'template' => 'index'
		]);
		$this->assertEquals('index', $engine->config->template['action']);

		// change wrapper or layout
		$engine->setup([
			'layout' => 'fallback',
			'wrapper' => 'wrapper'
		]);
		$this->assertEquals('fallback', $engine->config->layout);
		$this->assertEquals('wrapper', $engine->config->wrapper);

		// change template
		$engine->setup([
			'template' => [
				'module' => 'users',
				'controller' => 'dashboard'
			]
		]);
		$this->assertEquals([
			'module' => 'users',
			'controller' => 'dashboard',
			'action' => 'index',
			'ext' => null
		], $engine->config->template);
	}

}