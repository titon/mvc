<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc;

use Titon\Test\Fixture\ModelFixture;
use \Exception;

/**
 * Test class for Titon\Mvc\Model.
 */
class ModelTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Controller instance.
	 *
	 * @var \Titon\Mvc\Model
	 */
	public $object;

	/**
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->object = new ModelFixture([
			'id' => 1337,
			'status' => 1,
			'username' => 'miles',
			'firstName' => 'Miles',
			'lastName' => 'Johnson',
			'age' => 25,
			'created' => '2012-02-26 16:44:00',
			'modified' => mktime(16, 44, 0, 2, 26, 2012)
		]);
	}

	/**
	 * Test that all() returns all values.
	 */
	public function testAll() {
		$this->assertEquals([
			'id' => 1337,
			'status' => 1,
			'username' => 'miles',
			'firstName' => 'Miles',
			'lastName' => 'Johnson',
			'age' => 25,
			'created' => 1330274640,
			'modified' => 1330274640
		], $this->object->all());
	}

	/**
	 * Test that add() adds multiple values.
	 */
	public function testAdd() {
		$this->assertEquals('miles', $this->object->username);
		$this->assertEquals('2012-02-26 16:44:00', $this->object->created);

		$this->object->add([
			'username' => 'johnson',
			'created' => time()
		]);

		$this->assertEquals('johnson', $this->object->username);
		$this->assertEquals(date('Y-m-d H:i:s'), $this->object->created);
	}

	/**
	 * Test that get() returns a value and triggers getters.
	 */
	public function testGet() {
		$this->assertEquals('miles', $this->object->username);
		$this->assertEquals('2012-02-26 16:44:00', $this->object->get('created'));

		try {
			$this->object->gender;
			$this->assertTrue(false);
		} catch (Exception $e) {
			$this->assertTrue(true, $e->getMessage());
		}
	}

	/**
	 * Test that has() returns true if the field exists.
	 */
	public function testHas() {
		$this->assertTrue($this->object->has('username'));
		$this->assertFalse(isset($this->object->gender));
	}

	/**
	 * Test that keys() returns all keys.
	 */
	public function testKeys() {
		$this->assertEquals([
			'id',
			'status',
			'username',
			'firstName',
			'lastName',
			'age',
			'created',
			'modified'
		], $this->object->keys());
	}

	/**
	 * Test that set() changes values and triggers setters.
	 */
	public function testSet() {
		$this->assertEquals('Miles', $this->object->firstName);
		$this->assertEquals('2012-02-26 16:44:00', $this->object->created);
		$this->assertEquals('2012-02-26 16:44:00', $this->object->get('modified'));

		$this->object->set('firstName', 'Smiles');
		$this->assertEquals('Smiles', $this->object->firstName);

		$this->object->created = time();
		$this->assertEquals(date('Y-m-d H:i:s'), $this->object->created);

		$this->object->set('modified', strtotime('+5 days'));
		$this->assertEquals(date('Y-m-d H:i:s', strtotime('+5 days')), $this->object->get('modified'));
	}

	/**
	 * Test that remove() removes fields.
	 */
	public function testRemove() {
		$this->assertTrue($this->object->has('username'));
		$this->assertTrue(isset($this->object->firstName));

		$this->object->remove('username');
		unset($this->object->firstName);

		$this->assertFalse($this->object->has('username'));
		$this->assertFalse(isset($this->object->firstName));
	}

	/**
	 * Test that magic get/set/has methods work.
	 */
	public function testMagicMethods() {
		$this->assertEquals('Miles', $this->object->getFirstName());
		$this->assertEquals('2012-02-26 16:44:00', $this->object->getCreated());

		$this->assertTrue($this->object->hasUsername());
		$this->assertFalse($this->object->hasGender());

		$this->object->setModified(time());
		$this->assertEquals(date('Y-m-d H:i:s'), $this->object->getModified());

		$this->object->removeLastName();
		$this->assertFalse($this->object->hasLastName());

		try {
			$this->object->missingMethod();
			$this->assertTrue(false);
		} catch (Exception $e) {
			$this->assertTrue(true, $e->getMessage());
		}
	}

}