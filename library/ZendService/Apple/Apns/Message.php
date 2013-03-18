<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Service
 */

namespace ZendService\Apple\Apns;

use ZendService\Apple\Exception;
use Zend\Json\Encoder as JsonEncoder;

/**
 * APNs Message
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
        
        if (!is_string($token)) {
        	throw new Exception\InvalidArgumentException(sprintf(
        			'Device token must be a string, "%s" given.',
        			gettype($token)
        	));
        }
        
        if (preg_match('/[^0-9a-f]/', $token)) {
        	throw new Exception\InvalidArgumentException(sprintf(
        			'Device token must be mask "%s". Token given: "%s"',
        			'/[^0-9a-f]/',
        			$token
        	));
        }
        
        if (strlen($token) != 64) {
        	throw new Exception\InvalidArgumentException(sprintf(
        			'Device token must be a 64 charsets, Token length given: %d.',
        			mb_strlen($token)
        	));
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
        } elseif (!is_numeric($expire) || $expire != (int) $expire) {
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
     * @param array $custom
     * @throws Exception\RuntimeException
     * @return Message
     */
    public function setCustom(array $custom)
    {
    	if (array_key_exists('aps', $custom)) {
    		throw new Exception\RuntimeException('custom data must not contain aps key as it is reserved by apple');
    	}
    	
        $this->custom = $custom;
        return $this;
    }

    /**
     * Get Payload
     * Generate APN array.
     *
     * @return array
     */
    public function getPayload()
    {
        $message = array();
        $message['aps'] = array();
        if ($this->alert && ($alert = $this->alert->getPayload())) {
            $message['aps']['alert'] = $alert;
        }
        if (!is_null($this->badge)) {
            $message['aps']['badge'] = $this->badge;
        }
        if (!is_null($this->sound)) {
            $message['aps']['sound'] = $this->sound;
        }
        if (!empty($this->custom)) {
            $message = array_merge($this->custom, $message);
        }
        return $message;
    }

    /**
     * Get Payload JSON
     *
     * @return string
     */
    public function getPayloadJson()
    {
        $payload = $this->getPayload();
        // don't escape utf8 payloads unless json_encode does not exist.
        if (defined('JSON_UNESCAPED_UNICODE') && function_exists('mb_strlen')) {
            $payload = json_encode($payload, JSON_UNESCAPED_UNICODE);
            $length = mb_strlen($payload, 'UTF-8');
        } else {
            $payload = JsonEncoder::encode($payload);
            $length = strlen($payload);
        }
        return pack('CNNnH*', 1, $this->id, $this->expire, 32, $this->token)
            . pack('n', $length)
            . $payload;
    }
}
