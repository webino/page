<?php
/**
 * Webino
 *
 * PHP version 5.3
 *
 * LICENSE: This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt. It is also available through the
 * world-wide-web at this URL: http://www.webino.org/license/
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world-wide-web, please send an email to license@webino.org
 * so we can send you a copy immediately.
 *
 * @category   Webino
 * @package    Page
 * @subpackage EventHandler
 * @author     Peter Bačinský <peter@bacinsky.sk>
 * @copyright  2012 Peter Bačinský (http://www.bacinsky.sk/)
 * @license    http://www.webino.org/license/ New BSD License
 * @version    GIT: $Id$
 * @link       http://pear.webino.org/page/
 */

use Zend_Controller_Request_Abstract           as Request;
use Zend_Controller_Action_Helper_ViewRenderer as ViewRenderer;
use Zend_Controller_Action_Helper_Redirector   as Redirector;
use Webino_Resource_Draw_Interface             as DrawResource;

/**
 * Event handler for page
 *
 * @category   Webino
 * @package    Page
 * @subpackage EventHandler
 * @author     Peter Bačinský <peter@bacinsky.sk>
 * @copyright  2012 Peter Bačinský (http://www.bacinsky.sk/)
 * @license    http://www.webino.org/license/ New BSD License
 * @version    Release: @@PACKAGE_VERSION@@
 * @link       http://pear.webino.org/page/
 */
class Webino_EventHandler_Page
{
    /**
     * Draw resource
     *
     * @var DrawResource
     */
    private $_draw;

    /**
     * Inject draw
     *
     * @param DrawResource $draw
     */
    public function setDraw(DrawResource $draw)
    {
        $this->_draw = $draw;

        return $this;
    }

    /**
     * Render script
     *
     * example of options:
     *
     * - helper.renderer = ViewRenderer
     * - param.script    = "examplescript.html"
     *
     * @param ViewRenderer $renderer
     * @param string       $script
     *
     * @return Webino_EventHandler_Page
     */
    public function render(ViewRenderer $renderer, $script)
    {
        $renderer->setNoRender()->renderScript(
            $script
        );

        return $this;
    }

    /**
     * Forward to elswhere
     *
     * example of options:
     *
     * - getter.request     = request
     * - param.controller   = index
     * - param.action       = index
     * - param.params.param = value
     * - param.module       = default
     *
     * @param Request     $request
     * @param string      $controller
     * @param string      $action
     * @param array       $params
     * @param string      $module
     *
     * @return Webino_EventHandler_Page
     */
    public function forward(
        Request $request, $controller = null,
        $action = null, $params = null, $module = null
    )
    {
        if (null !== $params) {
            $request->setParams($params);
        }

        if (null !== $action) {
            $request->setActionName($action);
        }

        if (null !== $controller) {
            $request->setControllerName($controller);

            // Module should only be reset if controller has been specified
            if (null !== $module) {
                $request->setModuleName($module);
            }
        }

        $request->setDispatched(false);

        return $this;
    }

    /**
     * Redirect to location
     *
     * example of options:
     *
     * - helper.redirector = redirector
     * - param.location    = "examples/text-page/"
     *
     * @param Redirector $redirector
     * @param string     $location
     *
     * @return Webino_EventHandler_Page
     */
    public function redirect(Redirector $redirector, $location)
    {
        $redirector->gotoUrl($location);

        return $this;
    }

    /**
     * Add maps into draw resource
     *
     * example of options:
     *
     * - param.textpage = APPLICATION_CONFIGS "/draw/textpage.ini"
     * - param.example  = APPLICATION_CONFIGS "/draw/example.ini"
     *
     * @return Webino_EventHandler_Page
     */
    public function draw()
    {
        $this->_draw->addMaps(
            func_get_args()
        );

        return $this;
    }
}
