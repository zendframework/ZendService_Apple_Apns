<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @category   ZendService
 * @package    ZendService_Apple
 * @subpackage Apns
 */

namespace ZendService\Apple\Apns\Message;

use ZendService\Apple\Exception;

/**
 * Apple Push Notification Client
 * This class allows the ability to send out
 * messages through apple push notification service
 *
 * @category   ZendService
 * @package    ZendService_Apple
 * @subpackage Apns
 */
class Alert
{
    /**
     * Message Body
     * @var string|null
     */
    protected $body;

    /**
     * Action Locale Key
     * @var string|null
     */
    protected $actionLocKey;

    /**
     * Locale Key
     * @var string|null
     */
    protected $locKey;

    /**
     * Locale Arguments
     * @var array|null
     */
    protected $locArgs;

    /**
     * Launch Image
     * @var string|null
     */
    protected $launchImage;

    /**
     * Constructor
     *
     * @param string $body
     * @param string $actionLocKey
     * @param string $locKey
     * @param array $locArgs
     * @param string $launchImage
     * @return Alert
     */
    public function __construct($body = null, $actionLocKey = null, $locKey = null, $locArgs = null, $launchImage = null)
    {
        if ($body) {
            $this->setBody($body);
        }
        if ($actionLocKey) {
            $this->setActionLocKey($actionLocKey);
        }
        if ($locKey) {
            $this->setLocKey($locKey);
        }
        if ($locArgs) {
            $this->setLocArgs();
        }
        if ($launchImage) {
            $this->setLaunchImage($launchImage);
        }
    }

    /**
     * Get Body
     *
     * @return string|null
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set Body
     *
     * @param string|null $body
     * @return Alert
     */
    public function setBody($body)
    {
        if (!is_null($body) || !is_scalar($body)) {
            throw new Exception\InvalidArgumentException('Body must be null OR a scalar value');
        }
        $this->body = $body;
        return $this;
    }

    /**
     * Get Action Locale Key
     *
     * @return string|null
     */
    public function getActionLocKey()
    {
        return $this->actionLocKey;
    }

    /**
     * Set Action Locale Key
     *
     * @param string|null $key
     * @return Alert
     */
    public function setActionLocKey($key)
    {
        if (!is_null($key) || !is_scalar($key)) {
            throw new Exception\InvalidArgumentException('ActionLocKey must be null OR a scalar value');
        }
        $this->actionLocKey = $key;
        return $this;
    }

    /**
     * Get Locale Key
     *
     * @return string|null
     */
    public function getLocKey()
    {
        return $this->locKey;
    }

    /**
     * Set Locale Key
     *
     * @param string|null $key
     * @return Alert
     */
    public function setLocKey($key)
    {
        if (!is_null($key) || !is_scalar($key)) {
            throw new Exception\InvalidArgumentException('LocKey must be null OR a scalar value');
        }
        $this->locKey = $key;
        return $this;
    }

    /**
     * Get Locale Arguments
     *
     * @return array|null
     */
    public function getLocArgs()
    {
        return $this->locArgs;
    }

    /**
     * Set Locale Arguments
     *
     * @param  array|null $args
     * @return Alert
     */
    public function setLocArgs(array $args)
    {
        if (!is_null($args)) {
            foreach ($args as $a) {
                if (!is_scalar($a)) {
                    throw new Exception\InvalidArgumentException('Arguments must only contain scalar values');
                }
            }
        }
        $this->locArgs = $args;
        return $this;
    }

    /**
     * Get Launch Image
     *
     * @return string|null
     */
    public function getLaunchImage()
    {
        return $this->launchImage;
    }

    /**
     * Set Launch Image
     *
     * @param string|null $image
     * @return Alert
     */
    public function setLaunchImage($image)
    {
        if (!is_null($image) || !is_scalar($image)) {
            throw new Exception\InvalidArgumentException('Launch image must be null OR a scalar value');
        }
        $this->launchImage =  $image;
        return $this;
    }

    /**
     * To Payload
     * Formats an APS alert json object.
     *
     * @return string
     */
    public function toPayload()
    {

    }
}
