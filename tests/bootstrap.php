<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

error_reporting(E_ALL | E_STRICT);

define('TEST_DIR', __DIR__);
define('TEMP_DIR', __DIR__ . '/tmp');
define('VENDOR_DIR', dirname(TEST_DIR) . '/vendor');

if (!file_exists(VENDOR_DIR . '/autoload.php')) {
	exit('Please install composer before running tests!');
}

$loader = require VENDOR_DIR . '/autoload.php';
$loader->add('Titon\\Mvc', TEST_DIR);
$loader->add('Titon\\Test', TEST_DIR);

// Defaults
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['PHP_SELF'] = '/index.php';
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['SERVER_ADDR'] = '';

\Titon\Route\Router::initialize();