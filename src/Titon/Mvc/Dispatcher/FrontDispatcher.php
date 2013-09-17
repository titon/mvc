<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Mvc\Dispatcher;

use Titon\Mvc\Dispatcher\AbstractDispatcher;
use \Exception;

/**
 * The FrontDispatcher triggers all the necessary methods and callbacks
 * within the controller to generate the response output.
 *
 * @package Titon\Mvc\Dispatcher
 */
class FrontDispatcher extends AbstractDispatcher {

    /**
     * {@inheritdoc}
     */
    public function dispatch() {
        $this->getApplication()->emit('mvc.preDispatch', [$this]);

        $controller = $this->getController();

        try {
            $response = $controller->dispatchAction($this->getParam('action'), $this->getParam('args'));

            if (empty($response)) {
                $response = $controller->renderView();
            }
        } catch (Exception $e) {
            $response = $controller->renderError($e);
        }

        $this->getApplication()->emit('mvc.postDispatch', [$this]);

        return $response;
    }

}