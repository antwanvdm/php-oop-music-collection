<?php namespace System\Utils;

use MusicCollection\Utils\Session;
use PHPUnit\Framework\TestCase;

/**
 * Class SessionTest
 * @package System\Utils
 */
class SessionTest extends TestCase
{
    /**
     * Cleanup the Session storage
     *
     * @return void
     */
    public function tearDown(): void
    {
        Session::i()->deleteAll();
    }

    public function testKeyExists(): void
    {
        $testData = ['newKey' => 'test'];
        $_SESSION = $testData;
        $session = Session::i();

        $this->assertTrue($session->keyExists('newKey'));
    }

    public function testKeyDoesNotExists(): void
    {
        $testData = ['otherKey' => 'test'];
        $_SESSION = $testData;
        $session = Session::i();

        $this->assertFalse($session->keyExists('newKey'));
    }

    public function testKeyExistsAfterSetData(): void
    {
        $testData = ['newKey' => 'test'];
        $_SESSION = $testData;
        $session = Session::i();

        $session->set('anotherKey', 'bla');
        $this->assertTrue($session->keyExists('anotherKey'));
    }

    public function testKeyExistsAfterDeleteData(): void
    {
        $testData = ['newKey' => 'test'];
        $_SESSION = $testData;
        $session = Session::i();

        $session->delete('newKey');
        $this->assertFalse($session->keyExists('newKey'));
    }
}
