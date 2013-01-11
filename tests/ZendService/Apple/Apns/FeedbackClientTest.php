<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link       http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd New BSD License
 * @category   ZendService
 * @package    ZendService_Apple
 * @subpackage UnitTests
 */

namespace ZendServiceTest\Apple\Apns;

use ZendServiceTest\Apple\Apns\TestAsset\FeedbackClient;
use ZendService\Apple\Apns\Response\Feedback as FeedbackResponse;

/**
 * @category   ZendService
 * @package    ZendService_Apple
 * @subpackage UnitTests
 * @group      ZendService
 * @group      ZendService_Apple
 * @group      ZendService_Apple_Apns
 */
class FeedbackClientTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->apns = new FeedbackClient();
    }

    protected function setupValidBase()
    {
        $this->apns->open(FeedbackClient::SANDBOX_URI, __DIR__ . '/TestAsset/certificate.pem');
    }

    public function testFeedback()
    {
        $this->setupValidBase();
        $time = time();
        $token = bin2hex('abc123');
        $length = strlen($token);
        $this->apns->setReadResponse(pack('NnH*', $time, $length, $token));
        $response = $this->apns->feedback();
        $this->assertCount(1, $response);
        $feedback = array_shift($response);
        $this->assertEquals($time, $feedback->getTime());
        $this->assertEquals($token, $feedback->getToken());
    }

}
