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

namespace ZendService\Apple\Apns\Client;

use ZendService\Apple\Exception;

/**
 * Apple Push Notification Abstract Client
 *
 * @category   ZendService
 * @package    ZendService_Apple
 * @subpackage Apns
 */
abstract class AbstractClient
{
    /**
     * APNS URIs
     * @var array
     */
    protected $uris = array();

    /**
     * Is Connected
     * @var boolean
     */
    protected $isConnected = false;

    /**
     * Stream Socket
     * @var Resource
     */
    protected $socket;

    /**
     * Open Connection
     *
     * @return Client
     */
    public function open($environment, $certificate, $passPhrase = null)
    {
        if ($this->isConnected) {
            throw new Exception\RuntimeException('Connection has already been opened and must be closed');
        }

        if (!array_key_exists($environment, $this->uris)) {
            throw new Exception\InvalidArgumentException('$env is invalid and must be a Client constant.');
        }

        if (!is_string($certificate) || !file_exists($certificate)) {
            throw new Exception\InvalidArgumentException('$certificate must be a file path to the certificate');
        }

        $sslOptions = array(
            'local_cert' => $this->certificate,
        );
        if ($passPhrase) {
            $sslOptions['passphrase'] = $passPhrase;
        }

        $this->socket = stream_socket_client(
            $this->uris[$environment],
            $errno,
            $errstr,
            ini_get('socket_timeout'),
            STREAM_CLIENT_CONNECT,
            stream_context_create(array(
                'ssl' => $ssl,
            ))
        );

        if (!$this->socket) {
            throw new Exception\RuntimeException(sprintf(
                'Unable to connect: %s: %d (%s)',
                $this->uris[$environment],
                $errno,
                $errstr
            ));
        }

        stream_set_blocking($this->socket, 0);
        stream_set_write_buffer($this->socket, 0);
        $this->isConnected = true;
        return $this;
    }

    /**
     * Close Connection
     *
     * @return Client
     */
    public function close()
    {
        if ($this->isConnected) {
            fclose($this->socket);
        }
        $this->isConnected = false;
        return $this;
    }

    /**
     * Is Connected
     *
     * @return boolean
     */
    public function isConnected()
    {
        return $this->isConnected();
    }

    /**
     * Read from the Server
     *
     * @param int $length
     * @return string
     */
    protected function read($length = 1024)
    {
        if (!$this->isConnected()) {
            throw new Exception\RuntimeException('You must open the connection prior to reading data');
        }
        $data = false;
        if (!feof($this->socket)) {
            $data = fread($this->socket, (int) $length);
        }
        return $data;
    }

    /**
     * Write Payload to the Server
     *
     * @param string $payload
     * @return int
     */
    protected function write($payload)
    {
        if (!$this->isConnected()) {
            throw new Exception\RuntimeException('You must open the connection prior to writing data');
        }
        return @fwrite($this->socket, $payload);
    }

    /**
     * Destructor
     *
     * @return void
     */
    public function __destruct()
    {
        $this->close();
    }
}
