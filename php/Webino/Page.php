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
 * @author     Peter Bačinský <peter@bacinsky.sk>
 * @copyright  2012 Peter Bačinský (http://www.bacinsky.sk/)
 * @license    http://www.webino.org/license/ New BSD License
 * @version    GIT: $Id$
 * @link       http://pear.webino.org/page/
 */

/**
 * Page class
 *
 * @category   Webino
 * @package    Page
 * @author     Peter Bačinský <peter@bacinsky.sk>
 * @copyright  2012 Peter Bačinský (http://www.bacinsky.sk/)
 * @license    http://www.webino.org/license/ New BSD License
 * @version    Release: @@PACKAGE_VERSION@@
 * @link       http://pear.webino.org/page/
 */
class Webino_Page
    extends    Zend_Navigation_Page_Uri
    implements Webino_Page_Interface
{
    /**
     * Name of the params key
     */
    const PARAMS_KEYNAME = 'params';
    
    /**
     * Return page property, with default option
     *
     * @param string $property
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($property, $default=null)
    {
        $value = parent::get($property);

        if (null === $value) {
            return $default;
        }

        return $value;
    }

    /**
     * Return page uri joined with custom property
     *
     * @param string $property
     * 
     * @return string
     */
    public function getUriWithProperty($property)
    {
        return $this->getUri() . $this->get($property);
    }

    /**
     * Return uri joined with params array
     *
     * @return string
     */
    public function getUriWithParams()
    {
        $params = null;
        if ($this->get(self::PARAMS_KEYNAME)) {
            $params = array();
            foreach ($this->get(self::PARAMS_KEYNAME) as $key=>$value) {
                $params[] = $key . '=' . urlencode($value);
            }
            $params = '?' . join('&amp;', $params);
        }

        return $this->getUri() . $params;
    }
}
