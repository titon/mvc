<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc\Engine;

use Titon\Mvc\View;
use Titon\Mvc\Engine\ViewEngine;
use Titon\Test\TestCase;
use \Exception;

/**
 * Test class for Titon\Mvc\Engine\ViewEngine.
 *
 * @property \Titon\Mvc\View $object
 */
class ViewEngineTest extends TestCase {

	/**
	 * Instantiate the objects.
	 */
	protected function setUp() {
		parent::setUp();

		$this->object = new View([
			TEMP_DIR,
			TEMP_DIR . '/fallback'
		]);
		$this->object->setEngine(new ViewEngine());
	}

	/**
	 * Test that open() renders partials.
	 */
	public function testOpen() {
		$this->assertEquals('nested/include.tpl', $this->object->getEngine()->open('nested/include'));
		$this->assertEquals('nested/include.tpl', $this->object->getEngine()->open('nested/include.tpl'));
		$this->assertEquals('Titon - partial - variables.tpl', $this->object->getEngine()->open('variables', [
			'name' => 'Titon',
			'type' => 'partial',
			'filename' => 'variables.tpl'
		]));

		try {
			$this->object->getEngine()->open('foobar');
			$this->assertTrue(false);

		} catch (Exception $e) {
			$this->assertTrue(true);
		}
	}

	/**
	 * Test that render() parses and returns a template.
	 */
	public function testRender() {
		$this->assertEquals('add.tpl', $this->object->render($this->object->locateTemplate(['index', 'add'])));
		$this->assertEquals('test-include.tpl nested/include.tpl', $this->object->render($this->object->locateTemplate(['index', 'test-include'])));

		try {
			$this->object->render($this->object->locateTemplate(['index', 'add']));
			$this->assertTrue(false);

		} catch (Exception $e) {
			$this->assertTrue(true);
		}
	}

}