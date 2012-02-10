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
 * Helper to draw page properties
 *
 * Supports filters and helpers.
 *
 * example of options:
 * 
 * - xpath          = '//*[@data-draw="link-example"]'
 * - helper         = page
 * - property.name  = node
 * - property.value = example
 * - attribs.href   = "{$->uri}"
 *
 * example of options (custom variables):
 *
 * - property.fetch.customVar  = value.in.the.depth
 * - property.fetch.customVar2 = items.example.deepest.value
 * - fetch.customVar3          = name
 * - value                     = "{$customVar} {$customVar3} {$customVar2} {$->uri}"
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
class Webino_DrawHelper_Page
    extends Webino_DrawHelper_Element
{
    /**
     * Name of property key
     */
    const PROPERTY_KEYNAME = 'property';

    /**
     * Variable format
     */
    const PAGEVAR_FORMAT = '{$->%s}';

    /**
     * Get translation from page properties
     *
     * @param array $properties
     *
     * @return array
     */
    protected function _pageTranslation(array $properties)
    {
        foreach ($properties as $index => $value) {

            unset($properties[$index]);

            if (!is_string($value)) {
                continue;
            }

            $properties[sprintf(self::PAGEVAR_FORMAT, $index)] = $value;
        }

        return $properties;
    }

    /**
     * Return page object by property
     *
     * @return Webino_Page_Interface
     */
    protected function _page()
    {
        return $this->view->navigation()->findOneBy(
            $this->_options[self::PROPERTY_KEYNAME][self::NAME_KEYNAME],
            $this->_options[self::PROPERTY_KEYNAME][self::VALUE_KEYNAME]
        );
    }

    /**
     * Draw page properties to node
     *
     * @param DOMNode $node
     */
    public function draw(DOMNode $node)
    {
        $this->_nodeHelper = $this->view->node($node);

        $properties = $this->_page()->toArray();

        $this->_varTranslator = $this->view->varTranslator(
            array_merge(
                $this->_translation($node),
                $this->_pageTranslation($properties)
            )
        )->fetch($this->view->getVars(), $this->_options)
        ->fetch($properties, $this->_options[self::PROPERTY_KEYNAME])
        ->apply($this->_options);

        $this->_value();

        $this->_nodeHelper = $this->view->node($this->_asChild($node));

        $this->_asChildValue()->_attribs()->_appendTo($node);
    }
}
