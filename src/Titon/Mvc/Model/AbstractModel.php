<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc\Model;

use Titon\Common\Augment\ParamAugment;
use Titon\Mvc\Model;
use Titon\Mvc\Exception;
use \Closure;

/**
 * Interface for the models library.
 */
abstract class AbstractModel implements Model {

	/**
	 * Current data representation of a database record.
	 *
	 * @var \Titon\Common\Augment\ParamAugment
	 */
	protected $_data;

	/**
	 * Mapping of getters for fields.
	 *
	 * @var array
	 */
	protected $_getters = [];

	/**
	 * Mapping of setters for fields.
	 *
	 * @var array
	 */
	protected $_setters = [];

	/**
	 * Store the database record as parameters.
	 *
	 * @param array $data
	 */
	public function __construct(array $data) {
		$this->_data = new ParamAugment();

		foreach ($data as $key => $value) {
			$this->set($key, $value);
		}
	}

	/**
	 * Magic method for Model::get().
	 *
	 * @param string $key
	 * @return mixed
	 * @final
	 */
	final public function __get($key) {
		return $this->get($key);
	}

	/**
	 * Magic method for Model::set().
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	final public function __set($key, $value) {
		$this->set($key, $value);
	}

	/**
	 * Magic method for Model::has().
	 *
	 * @param string $key
	 * @return boolean
	 * @final
	 */
	final public function __isset($key) {
		return $this->has($key);
	}

	/**
	 * Magic method for Model::remove().
	 *
	 * @param $key
	 * @return void
	 * @final
	 */
	final public function __unset($key) {
		$this->remove($key);
	}

	/**
	 * Add support for getField(), setField(), hasField() and removeField().
	 *
	 * @param string $method
	 * @param array $args
	 * @return mixed
	 * @throws \Titon\Mvc\Exception
	 */
	final public function __call($method, array $args = []) {
		$type = substr($method, 0, 3);

		switch ($type) {
			case 'get':
			case 'set':
			case 'has':
				array_unshift($args, lcfirst(substr($method, 3, strlen($method))));

				return call_user_func_array([$this, $type], $args);
			break;
			case 'rem':
				return $this->remove(lcfirst(substr($method, 6, strlen($method))));
			break;
		}

		throw new Exception(sprintf('%s:%s() method does not exist', get_class($this), $method));
	}

	/**
	 * Return all fields.
	 *
	 * @return array
	 */
	public function all() {
		return $this->_data->all();
	}

	/**
	 * Add multiple fields.
	 *
	 * @param array $values
	 * @return \Titon\Mvc\Model
	 */
	public function add(array $values) {
		foreach ($values as $key => $value) {
			$this->set($key, $value);
		}

		return $this;
	}

	/**
	 * Return a field by key.
	 *
	 * @param string $key
	 * @return mixed
	 * @throws \Titon\Mvc\Exception
	 */
	public function get($key) {
		if (!$this->has($key)) {
			throw new Exception(sprintf('%s does not contain a %s field', get_class($this), $key));
		}

		$value = $this->_data->get($key);

		if (isset($this->_getters[$key])) {
			$value = call_user_func([$this, $this->_getters[$key]], $value);
		}

		return $value;
	}

	/**
	 * Check if a field exists.
	 *
	 * @param string $key
	 * @return bool
	 */
	public function has($key) {
		return $this->_data->has($key);
	}

	/**
	 * Return all the field names.
	 *
	 * @return array
	 */
	public function keys() {
		return $this->_data->keys();
	}

	/**
	 * Set a field value.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return \Titon\Mvc\Model
	 */
	public function set($key, $value) {
		if (isset($this->_setters[$key])) {
			$value = call_user_func([$this, $this->_setters[$key]], $value);
		}

		$this->_data->set($key, $value);

		return $this;
	}

	/**
	 * Remove a field.
	 *
	 * @param string $key
	 * @return \Titon\Mvc\Model
	 */
	public function remove($key) {
		$this->_data->remove($key);

		return $this;
	}

}