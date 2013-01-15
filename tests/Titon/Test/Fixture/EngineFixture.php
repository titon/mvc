<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Test\Fixture;

use Titon\Mvc\Engine\AbstractEngine;

/**
 * Fixture for Titon\Mvc\Engine.
 */
class EngineFixture extends AbstractEngine {

	public function open($partial, array $variables = []) {}
	public function render($path, array $variables = []) {}
	public function getExtension() {}

}