<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Mvc;

use Titon\Controller\Controller\AbstractController;
use Titon\Mvc\Contract\UseApplication;
use Titon\Mvc\Contract\UseModule;
use Titon\Mvc\Traits\AppAware;
use Titon\Mvc\Traits\ModuleAware;

/**
 * Provide new functionality to the controller layer by
 * allowing modules and application objects to be used.
 *
 * @package Titon\Mvc
 */
class Controller extends AbstractController implements UseApplication, UseModule {
    use AppAware, ModuleAware;
}