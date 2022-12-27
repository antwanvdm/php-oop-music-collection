<?php namespace System\Utils;

use MusicCollection\Utils\Session;
use PHPUnit\Framework\TestCase;

/**
 * Class SessionTest
 * @package System\Utils
 */
class SessionTest extends TestCase
{
    public function testKeyExists(): void
    {
        $testData = ['newKey' => 'test'];
        $_SESSION = $testData;
        $session = new Session();

        $this->assertTrue($session->keyExists('newKey'));
    }

    public function testKeyDoesNotExists(): void
    {
        $testData = ['otherKey' => 'test'];
        $_SESSION = $testData;
        $session = new Session();

        $this->assertFalse($session->keyExists('newKey'));
    }

    public function testKeyExistsAfterSetData(): void
    {
        $testData = ['newKey' => 'test'];
        $_SESSION = $testData;
        $session = new Session();

        $session->set('anotherKey', 'bla');
        $this->assertTrue($session->keyExists('anotherKey'));
    }

    public function testKeyExistsAfterDeleteData(): void
    {
        $testData = ['newKey' => 'test'];
        $_SESSION = $testData;
        $session = new Session();

        $session->delete('newKey');
        $this->assertFalse($session->keyExists('newKey'));
    }
}
