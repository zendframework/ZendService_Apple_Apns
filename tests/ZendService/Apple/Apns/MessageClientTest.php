<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Service
 */

namespace ZendServiceTest\Apple\Apns;

use ZendServiceTest\Apple\Apns\TestAsset\MessageClient;
use ZendService\Apple\Apns\Message;
use ZendService\Apple\Apns\Response\Message as MessageResponse;

/**
 * @category   ZendService
 * @package    ZendService_Apple
 * @subpackage UnitTests
 * @group      ZendService
 * @group      ZendService_Apple
 * @group      ZendService_Apple_Apns
 */
class MessageClientTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->apns = new MessageClient();
        $this->message = new Message();
    }

    protected function setupValidBase()
    {
        $this->apns->open(MessageClient::SANDBOX_URI, __DIR__ . '/TestAsset/certificate.pem');
        $this->message->setToken('662cfe5a69ddc65cdd39a1b8f8690647778204b064df7b264e8c4c254f94fdd8');
        $this->message->setId(time());
        $this->message->setAlert('bar');
    }

    public function testConnectThrowsExceptionOnInvalidEnvironment()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->apns->open(5, __DIR__ . '/TestAsset/certificate.pem');
    }

    public function testSetCertificateThrowsExceptionOnNonString()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->apns->open(MessageClient::PRODUCTION_URI, array('foo'));
    }

    public function testSetCertificateThrowsExceptionOnMissingFile()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->apns->open(MessageClient::PRODUCTION_URI, 'foo');
    }

    public function testSetCertificatePassphraseThrowsExceptionOnNonString()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->apns->open(MessageClient::PRODUCTION_URI, __DIR__ . '/TestAsset/certificate.pem', array('foo'));
    }

    public function testOpen()
    {
        $ret = $this->apns->open(MessageClient::SANDBOX_URI, __DIR__ . '/TestAsset/certificate.pem');
        $this->assertEquals($this->apns, $ret);
        $this->assertTrue($this->apns->isConnected());
    }

    public function testClose()
    {
        $this->apns->open(MessageClient::SANDBOX_URI, __DIR__ . '/TestAsset/certificate.pem');
        $this->apns->close();
        $this->assertFalse($this->apns->isConnected());
    }

    public function testOpenWhenAlreadyOpenThrowsException()
    {
        $this->setExpectedException('RuntimeException');
        $this->apns->open(MessageClient::SANDBOX_URI, __DIR__ . '/TestAsset/certificate.pem');
        $this->apns->open(MessageClient::SANDBOX_URI, __DIR__ . '/TestAsset/certificate.pem');
    }

    public function testSendReturnsTrueOnSuccess()
    {
        $this->setupValidBase();
        $response = $this->apns->send($this->message);
        $this->assertTrue($response);
    }


    public function testSendResponseOnProcessingError()
    {
        $this->setExpectedException('RuntimeException');
    	$this->setupValidBase();
        $this->apns->setReadResponse(pack('CCN*', 1, 1, 12345));
       	$response = $this->apns->send($this->message);
    }

    public function testSendResponseOnMissingToken()
    {
        $this->setExpectedException('RuntimeException');
    	$this->setupValidBase();
        $this->apns->setReadResponse(pack('CCN*', 2, 2, 12345));
        $response = $this->apns->send($this->message);
    }

    public function testSendResponseOnMissingTopic()
    {
        $this->setExpectedException('RuntimeException');
    	$this->setupValidBase();
        $this->apns->setReadResponse(pack('CCN*', 3, 3, 12345));
        $response = $this->apns->send($this->message);
    }

    public function testSendResponseOnMissingPayload()
    {
        $this->setExpectedException('RuntimeException');
    	$this->setupValidBase();
        $this->apns->setReadResponse(pack('CCN*', 4, 4, 12345));
        $response = $this->apns->send($this->message);
    }

    public function testSendResponseOnInvalidTokenSize()
    {
        $this->setExpectedException('RuntimeException');
    	$this->setupValidBase();
        $this->apns->setReadResponse(pack('CCN*', 5, 5, 12345));
        $response = $this->apns->send($this->message);
    }

    public function testSendResponseOnInvalidTopicSize()
    {
        $this->setExpectedException('RuntimeException');
    	$this->setupValidBase();
        $this->apns->setReadResponse(pack('CCN*', 6, 6, 12345));
        $response = $this->apns->send($this->message);
    }

    public function testSendResponseOnInvalidPayloadSize()
    {
        $this->setExpectedException('RuntimeException');
    	$this->setupValidBase();
        $this->apns->setReadResponse(pack('CCN*', 7, 7, 12345));
        $response = $this->apns->send($this->message);
    }

    public function testSendResponseOnInvalidToken()
    {
        $this->setExpectedException('RuntimeException');
    	$this->setupValidBase();
        $this->apns->setReadResponse(pack('CCN*', 8, 8, 12345));
        $response = $this->apns->send($this->message);
    }

    public function testSendResponseOnUnknownError()
    {
        $this->setExpectedException('RuntimeException');
    	$this->setupValidBase();
        $this->apns->setReadResponse(pack('CCN*', 255, 255, 12345));
        $response = $this->apns->send($this->message);
    }
}
