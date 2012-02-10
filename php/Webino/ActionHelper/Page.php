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
 * @subpackage ActionHelper
 * @author     Peter Bačinský <peter@bacinsky.sk>
 * @copyright  2012 Peter Bačinský (http://www.bacinsky.sk/)
 * @license    http://www.webino.org/license/ New BSD License
 * @version    GIT: $Id$
 * @link       http://pear.webino.org/page/
 */

use Zend_Navigation                  as Navigation;
use Zend_Controller_Action_Exception as ActionException;

/**
 * Action helper for action controller page injection
 *
 * @category   Webino
 * @package    Page
 * @subpackage ActionHelper
 * @author     Peter Bačinský <peter@bacinsky.sk>
 * @copyright  2012 Peter Bačinský (http://www.bacinsky.sk/)
 * @license    http://www.webino.org/license/ New BSD License
 * @version    Release: 0.1.0alpha
 * @link       http://pear.webino.org/page/
 */

class Webino_ActionHelper_Page
    extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * URI for home page
     */
    const HOME_URI = '.';

    /**
     * Name of action controller method to inject page object
     */
    const CONTROLLER_INJECTOR = 'setPage';

    /**
     * URL path info
     *
     * @var String
     */
    private $_pathInfo;

    /**
     * Zend navigation
     *
     * @var Navigation
     */
    private $_navigation;

    /**
     * Find page by request pathInfo
     *
     * Page is injected to action controller if it has setPage method.
     *
     * @return object Page
     */
    public function init()
    {
        $injectPage = method_exists(
            $this->getActionController(), self::CONTROLLER_INJECTOR
        );

        if (!$injectPage) {
            
            return null;
        }

        $this->getActionController()->setPage(
            $this->findPage($this->getPathInfo())
        );
    }

    /**
     * Find page by property
     *
     * @throws ActionException When page was not found.
     *
     * @param string $value
     * @param string $property
     *
     * @return Webino_Page_Interface
     */
    public function findPage($value, $property = 'uri')
    {
        $page = $this->_navigation->findOneBy(
            $property, $value
        );

        if (!$page) {
            throw new ActionException(
                sprintf(
                    'Page with URI "%s" was not found.',
                    htmlspecialchars($this->getPathInfo())
                ), 404
            );
        }

        return $page;
    }

    /**
     * Inject URL path info
     *
     * @param string $pathInfo
     *
     * @return Webino_ActionHelper_Page
     */
    public function setPathInfo($pathInfo = null)
    {
        if (!$pathInfo) {
            $pathInfo = self::HOME_URI;
        }
        $this->_pathInfo = $pathInfo;

        return $this;
    }

    /**
     * Return URL path info
     *
     * @return string
     */
    public function getPathInfo()
    {
        if (!$this->_pathInfo) {
            $this->setPathInfo(
                substr($this->getRequest()->getPathInfo(), 1)
            );
        }

        return $this->_pathInfo;
    }

    /**
     * Inject navigation object
     *
     * @param Navigation $navigation
     *
     * @return Webino_ActionHelper_Page
     */
    public function setNavigation(Navigation $navigation)
    {
        $this->_navigation = $navigation;

        return $this;
    }
}
