<?php
/**
 * Titon: A PHP 5.4 Modular Framework
 *
 * @copyright	Copyright 2010, Titon
 * @link		http://github.com/titon
 * @license		http://opensource.org/licenses/bsd-license.php (BSD License)
 */

namespace Titon\Test\Fixture;

use Titon\Mvc\Engine\AbstractEngine;

/**
 * Fixture for Titon\Mvc\Engine.
 */
class EngineFixture extends AbstractEngine {

	public function open($path, array $variables = []) {}
	public function render($path, array $variables = []) {}
	public function run($cache = true) {}

}