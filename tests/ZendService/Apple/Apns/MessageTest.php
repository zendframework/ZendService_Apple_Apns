<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Service
 */

namespace ZendServiceTest\Apple\Apns\TestAsset;

use ZendService\Apple\Apns\Message;
use ZendService\Apple\Apns\Message\Alert;

/**
 * @category   ZendService
 * @package    ZendService_Apple
 * @subpackage UnitTests
 * @group      ZendService
 * @group      ZendService_Apple
 * @group      ZendService_Apple_Apns
 */
class MessageTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->alert = new Alert();
        $this->message = new Message();
    }

    public function testSetAlertTextReturnsCorrectly()
    {
        $text = 'my alert';
        $ret = $this->message->setAlert($text);
        $this->assertInstanceOf('ZendService\Apple\Apns\Message', $ret);
        $checkText = $this->message->getAlert();
        $this->assertInstanceOf('ZendService\Apple\Apns\Message\Alert', $checkText);
        $this->assertEquals($text, $checkText->getBody());
    }

    public function testSetAlertThrowsExceptionOnTextNonString()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->message->setAlert(array());
    }

    public function testSetAlertThrowsExceptionOnActionLocKeyNonString()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->alert->setActionLocKey(array());
    }

    public function testSetAlertThrowsExceptionOnLocKeyNonString()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->alert->setLocKey(array());
    }

    public function testSetAlertThrowsExceptionOnLaunchImageNonString()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->alert->setLaunchImage(array());
    }

    public function testSetBadgeReturnsCorrectNumber()
    {
        $num = 5;
        $this->message->setBadge($num);
        $this->assertEquals($num, $this->message->getBadge());
    }

    public function testSetBadgeNonNumericThrowsException()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->message->setBadge('string!');
    }

    public function testSetBadgeAllowsNull()
    {
        $this->message->setBadge(null);
        $this->assertNull($this->message->getBadge());
    }

    public function testSetExpireReturnsInteger()
    {
        $expire = 100;
        $this->message->setExpire($expire);
        $this->assertEquals($expire, $this->message->getExpire());
    }

    public function testSetExpireNonNumericThrowsException()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->message->setExpire('sting!');
    }

    public function testSetSoundReturnsString()
    {
        $sound = 'test';
        $this->message->setSound($sound);
        $this->assertEquals($sound, $this->message->getSound());
    }

    public function testSetSoundThrowsExceptionOnNonString()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->message->setSound(array());
    }

    public function testSetCustomData()
    {
        $data = array('key' => 'val', 'key2' => array(1, 2, 3, 4, 5));
        $this->message->setCustom($data);
        $this->assertEquals($data, $this->message->getCustom());
    }

    public function testAlertConstructor()
    {
        $alert = new Alert(
            'Foo wants to play Bar!',
            'PLAY',
            'GAME_PLAY_REQUEST_FORMAT',
            array('Foo', 'Baz'),
            'Default.png'
        );

        $this->assertEquals('Foo wants to play Bar!', $alert->getBody());
        $this->assertEquals('PLAY', $alert->getActionLocKey());
        $this->assertEquals('GAME_PLAY_REQUEST_FORMAT', $alert->getLocKey());
        $this->assertEquals(array('Foo', 'Baz'), $alert->getLocArgs());
        $this->assertEquals('Default.png', $alert->getLaunchImage());
    }

    public function testAlertJsonPayload()
    {
        $alert = new Alert(
            'Foo wants to play Bar!',
            'PLAY',
            'GAME_PLAY_REQUEST_FORMAT',
            array('Foo', 'Baz'),
            'Default.png'
        );
        $payload = $alert->getPayload();

        $this->assertArrayHasKey('body', $payload);
        $this->assertArrayHasKey('action-loc-key', $payload);
        $this->assertArrayHasKey('loc-key', $payload);
        $this->assertArrayHasKey('loc-args', $payload);
        $this->assertArrayHasKey('launch-image', $payload);
    }

    public function testAlertPayloadSendsOnlyBody()
    {
        $alert = new Alert('Foo wants Bar');
        $payload = $alert->getPayload();

        $this->assertEquals('Foo wants Bar', $payload);
    }
}
