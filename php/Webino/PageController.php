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
 * @subpackage Controller
 * @author     Peter Bačinský <peter@bacinsky.sk>
 * @copyright  2012 Peter Bačinský (http://www.bacinsky.sk/)
 * @license    http://www.webino.org/license/ New BSD License
 * @version    GIT: $Id$
 * @link       http://pear.webino.org/page/
 */

use Webino_Page as Page;

/**
 * Navigation controller for pages
 *
 * @category   Webino
 * @package    Page
 * @subpackage Controller
 * @author     Peter Bačinský <peter@bacinsky.sk>
 * @copyright  2012 Peter Bačinský (http://www.bacinsky.sk/)
 * @license    http://www.webino.org/license/ New BSD License
 * @version    Release: @@PACKAGE_VERSION@@
 * @link       http://pear.webino.org/page/
 */
class Webino_PageController
    extends Zend_Controller_Action
{
    /**
     * Name of events option key
     */
    const EVENTS_KEYNAME = 'events';

    /**
     * Event name for page index
     */
    const INDEX_EVENT = 'pageIndex';

    /**
     * Name of events action helper
     */
    const EVENTS_HELPER = 'events';

    /**
     * Current page
     *
     * @var Page
     */
    private $_page;

    /**
     * Activate page, its parents and assign it to view
     */
    public function preDispatch()
    {
        $parentPage = $this->_page->setActive(true)->getParent();

        while ($parentPage) {

            if (!($parentPage instanceof Zend_Navigation_Page)) {
                break;
            }

            $parentPage->setActive(true);
            $parentPage = $parentPage->getParent();
        }

        $properties = $this->_page->toArray();

        $this->getHelper(self::EVENTS_HELPER)->fire(
            self::INDEX_EVENT, array(&$properties, $this)
        );

        $this->view->assign($properties);

    }

    /**
     * Fire page events
     */
    public function indexAction()
    {
        $this->getHelper(self::EVENTS_HELPER)->trigger(
            $this->_page->get(self::EVENTS_KEYNAME, array())
        );
    }

    /**
     * Page injector
     *
     * @param Page $page
     *
     * @return Webino_PageController
     */
    public function setPage(Page $page)
    {
        $this->_page = $page;

        return $this;
    }

    /**
     * Return current page
     *
     * @return Page
     */
    public function getPage()
    {
        return $this->_page;
    }
}
