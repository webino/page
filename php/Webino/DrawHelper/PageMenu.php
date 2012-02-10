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
 * @subpackage DrawHelper
 * @author     Peter Bačinský <peter@bacinsky.sk>
 * @copyright  2012 Peter Bačinský (http://www.bacinsky.sk/)
 * @license    http://www.webino.org/license/ New BSD License
 * @version    GIT: $Id$
 * @link       http://pear.webino.org/page/
 */

/**
 * Helper to draw page navigation menu
 *
 * Example of options:
 * 
 * - value            = ''
 * - property.menutop = 1
 * - insertBefore     = 1
 *
 * @category   Webino
 * @package    Page
 * @subpackage DrawHelper
 * @author     Peter Bačinský <peter@bacinsky.sk>
 * @copyright  2012 Peter Bačinský (http://www.bacinsky.sk/)
 * @license    http://www.webino.org/license/ New BSD License
 * @version    Release: @@PACKAGE_VERSION@@
 * @link       http://pear.webino.org/page/
 */
class Webino_DrawHelper_PageMenu
    extends Webino_DrawHelper_Abstract
{
    /**
     * Name of clear key
     */
    const CLEAR_KEYNAME = 'clear';

    /**
     * Name of property key
     */
    const PROPERTY_KEYNAME = 'property';

    /**
     * Name of insert before key
     */
    const BEFORE_KEYNAME = 'insertBefore';

    /**
     * Draw navigation to node
     *
     * @param DOMNode $node
     */
    public function draw(DOMNode $node)
    {
        if (!empty($this->_options[self::CLEAR_KEYNAME])) {
            $node->nodeValue = '';
        }
        
        $navigation = $this->view->navigation();

        $pages = $navigation->toArray();

        $navPages = $this->view->navigation()->findAllBy(
            key($this->_options[self::PROPERTY_KEYNAME]),
            current($this->_options[self::PROPERTY_KEYNAME])
        );

        $navigation->setPages($navPages);

        $frag = $this->frag(
            $navigation->__toString(), $node->ownerDocument
        );

        if (isset($this->_options[self::BEFORE_KEYNAME])) {
            $node->parentNode->insertBefore($frag, $node);
        } else {
            $node->appendChild($frag);
        }

        $this->view->navigation()->setPages($pages);
    }
}
