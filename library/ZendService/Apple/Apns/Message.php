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

namespace ZendService\Apple\Apns;

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
class Message
{
    /**
     * Identifier
     * @var string
     */
    protected $id;

    /**
     * APN Token
     * @var string
     */
    protected $token;

    /**
     * Expiration
     * @var int|null
     */
    protected $expire;

    /**
     * Alert Message
     * @var Message\Alert|null
     */
    protected $alert;

    /**
     * Badge
     * @var int|null
     */
    protected $badge;

    /**
     * Sound
     * @var string|null
     */
    protected $sound;

    /**
     * Custom Attributes
     * @var array|null
     */
    protected $custom;

    /**
     * Get Identifier
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Identifier
     *
     * @param string $id
     * @return Message
     */
    public function setId($id)
    {
        if (!is_scalar($id)) {
            throw new Exception\InvalidArgumentException('Identifier must be a scalar value');
        }
        $this->id = $id;
        return $this;
    }

    /**
     * Get Token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set Token
     *
     * @param string $token
     * @return Message
     */
    public function setToken($token)
    {
        if (!is_scalar($token)) {
            throw new Exception\InvalidArgumentException('Token must be a scalar value');
        }
        $this->token = $token;
        return $this;
    }

    /**
     * Get Expiration
     *
     * @return int
     */
    public function getExpire()
    {
        return $this->expire;
    }

    /**
     * Set Expiration
     *
     * @param int|DateTime $expire
     * @return Message
     */
    public function setExpire($expire)
    {
        if ($expire instanceof \DateTime) {
            $expire = $expire->getTimestamp();
        } else if ($expire != (int) $expire) {
            throw new Exception\InvalidArgumentException('Expiration must be a DateTime object or a unix timestamp');
        }
        $this->expire = $expire;
        return $this;
    }

    /**
     * Get Alert
     *
     * @return Message\Alert|null
     */
    public function getAlert()
    {
        return $this->alert;
    }

    /**
     * Set Alert
     *
     * @param string|Message\Alert|null $alert
     * @return Message
     */
    public function setAlert($alert)
    {
        if (!$alert instanceof Message\Alert && !is_null($alert)) {
            $alert = new Message\Alert($alert);
        }
        $this->alert = $alert;
        return $this;
    }

    /**
     * Get Badge
     *
     * @return int|null
     */
    public function getBadge()
    {
        return $this->badge;
    }

    /**
     * Set Badge
     *
     * @param int|null $badge
     * @return Message
     */
    public function setBadge($badge)
    {
        if ($badge !== null && !$badge == (int) $badge) {
            throw new Exception\InvalidArgumentException('Badge must be null or an integer');
        }
        $this->badge = $badge;
    }

    /**
     * Get Sound
     *
     * @return string|null
     */
    public function getSound()
    {
        return $this->sound;
    }

    /**
     * Set Sound
     *
     * @param string|null $sound
     * @return Message
     */
    public function setSound($sound)
    {
        if (!is_scalar($sound)) {
            throw new Exception\InvalidArgumentException('Sound must be a scalar value');
        }
        $this->sound = $sound;
        return $this;
    }

    /**
     * Get Custom Data
     *
     * @return array|null
     */
    public function getCustom()
    {
        return $this->custom;
    }

    /**
     * Set Custom Data
     *
     * @return Message
     */
    public function setCustom(array $custom)
    {
        $this->custom = $custom;
        return $this;
    }
    
    /**
     * To Payload
     * Generate APN json object.
     *
     * @return string
     */
    public function toPayload()
    {
        
    }
}
