<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc;

/**
 * Interface for the models library.
 */
interface Model {

	/**
	 * Return all fields.
	 *
	 * @return array
	 */
	public function all();

	/**
	 * Add multiple fields.
	 *
	 * @param array $values
	 * @return \Titon\Mvc\Model
	 */
	public function add(array $values);

	/**
	 * Return a field by key.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get($key);

	/**
	 * Check if a field exists.
	 *
	 * @param string $key
	 * @return bool
	 */
	public function has($key);

	/**
	 * Return all the field names.
	 *
	 * @return array
	 */
	public function keys();

	/**
	 * Set a field value.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return \Titon\Mvc\Model
	 */
	public function set($key, $value);

	/**
	 * Remove a field.
	 *
	 * @param string $key
	 * @return \Titon\Mvc\Model
	 */
	public function remove($key);

}