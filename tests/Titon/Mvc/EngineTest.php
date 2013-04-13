<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc;

use Titon\Test\TestCase;
use Titon\Test\Fixture\EngineFixture;

/**
 * Test class for Titon\Mvc\Engine.
 *
 * @property \Titon\Mvc\Engine $object
 */
class EngineTest extends TestCase {

	/**
	 * Initialize the engine.
	 */
	protected function setUp() {
		parent::setUp();

		$this->object = new EngineFixture();
	}

	/**
	 * Test that the layout can be set and retrieved.
	 */
	public function testLayout() {
		$this->assertEquals('default', $this->object->getLayout());

		$this->object->useLayout('alternate');
		$this->assertEquals('alternate', $this->object->getLayout());
	}

	/**
	 * Test that the wrapper can be set and retrieved.
	 */
	public function testWrapper() {
		$this->assertEquals([], $this->object->getWrapper());

		$this->object->wrapWith('alternate');
		$this->assertEquals(['alternate'], $this->object->getWrapper());

		$this->object->wrapWith(['alternate', 'double']);
		$this->assertEquals(['alternate', 'double'], $this->object->getWrapper());
	}

	/**
	 * Test that the content can be set and retrieved.
	 */
	public function testContent() {
		$this->assertEquals('', $this->object->getContent());

		$this->object->setContent('content');
		$this->assertEquals('content', $this->object->getContent());
	}

}