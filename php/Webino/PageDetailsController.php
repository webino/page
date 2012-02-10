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
use Webino_Resource_Dependency_Injector_Interface as Dependency;
use Webino_Resource_Events_Interface              as Events;
use Zend_Controller_Action_Exception              as ActionException;

/**
 * Webino controller for page details
 *
 * example of options:
 *
 * - router.routes.studentDetails.route                                       = "examples/students/:name/"
 * - router.routes.studentDetails.defaults.module                             = webino
 * - router.routes.studentDetails.defaults.controller                         = page-details
 * - router.routes.studentDetails.defaults.action                             = index
 * - router.routes.studentDetails.defaults.property.name                      = node
 * - router.routes.studentDetails.defaults.property.value                     = students
 * - router.routes.studentDetails.defaults.property.base                      = students.:name
 * - router.routes.studentDetails.defaults.events.pageRender.helper.renderer  = viewRenderer
 * - router.routes.studentDetails.defaults.events.pageRender.param.script     = "demo-students/details.html"
 * - router.routes.studentDetails.defaults.events.pageDraw.param.demoStudents = PEAR_PHP_DIR "/Webino/configs/draw/demoStudentDetails.ini"
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
class Webino_PageDetailsController
    extends Zend_Controller_Action
{
    /**
     * Name of page property option key
     */
    const PROPERTY_KEYNAME = 'property';

    /**
     * Name of page property name option key
     */
    const NAME_KEYNAME = 'name';

    /**
     * Name of page property value option key
     */
    const VALUE_KEYNAME = 'value';

    /**
     * Name of events option key
     */
    const EVENTS_KEYNAME = 'events';

    /**
     * Name of property base key
     */
    const BASE_KEYNAME = 'base';

    /**
     * Event name for page detail
     */
    const DETAILS_EVENT = 'pageDetails';

    /**
     * Name of page action helper
     */
    const PAGE_HELPER = 'page';

    /**
     * Name of events action helper
     */
    const EVENTS_HELPER = 'events';

    /**
     * Return properties of page
     *
     * @return array
     */
    private function _pageProperties()
    {
        $property = $this->_getParam(self::PROPERTY_KEYNAME);

        return $this->getHelper(self::PAGE_HELPER)->findPage(
            $property[self::VALUE_KEYNAME], $property[self::NAME_KEYNAME]
        )->setActive(true)->toArray();
    }

    /**
     * Return base properties of page
     *
     * @param array $properties
     *
     * @throws ActionException If property was not found
     * 
     * @return array 
     */
    private function _baseProperties(array $properties)
    {
        $property = $this->_getParam(self::PROPERTY_KEYNAME);

        if (!empty($property[self::BASE_KEYNAME])) {

            foreach (explode('.', $property[self::BASE_KEYNAME]) as $key) {

                if (':' == $key[0]) {
                    $key = $this->_getParam(substr($key, 1));
                }

                if (empty($properties[$key])) {
                    throw new ActionException(
                        sprintf(
                            'Page with URI "%s" was not found.',
                            htmlspecialchars($this->_request->getPathInfo())
                        ), 404
                    );
                }

                $properties = $properties[$key];
            }
        }

        return $properties;
    }

    /**
     * Assign page base properties to view
     */
    public function preDispatch()
    {
        $properties = $this->_baseProperties($this->_pageProperties());

        $this->getHelper(self::EVENTS_HELPER)->fire(
            self::DETAILS_EVENT, array(&$properties, $this)
        );

        $this->view->assign($properties);
    }

    /**
     * Fire route events
     */
    public function indexAction()
    {
        $this->getHelper(self::EVENTS_HELPER)->trigger(
            $this->_getParam(self::EVENTS_KEYNAME, array())
        );
    }
}
