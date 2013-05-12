<?php
namespace Test\Unit\LiveTest\Connection\Session;
use LiveTest\Connection\Session\Session;

class SessionTest extends \PHPUnit_Framework_TestCase
{

    public function testIfIncludePageRequestWorks ()
    {
        $request = $this->getMock('LiveTest\Connection\Request\Request');
        $session = new Session();
        $session->includePageRequest($request);
        $includedSessions = $session->getPageRequests();
        $this->assertEquals(array(
                $request), $includedSessions);
    }

    public function testIfIncludePageRequestsWorks ()
    {
        $requests = array($this->getMock('LiveTest\Connection\Request\Request'));
        $session = new Session();
        $session->includePageRequests($requests);
        $includedSessions = $session->getPageRequests();
        $this->assertEquals($requests, $includedSessions);
    }

    public function testConstructorException ()
    {
        $this->setExpectedException('\Exception');
        new Session('Fabian');
    }

    public function testConstructor ()
    {
        new Session(true);
        $this->assertTrue(true);
    }

    public function testAllowCookies ()
    {
        $cookiedSession = new Session(true);
        $this->assertTrue($cookiedSession->areCookiesAllowed());

        $noCookiedSession = new Session(false);
        $this->assertFalse($noCookiedSession->areCookiesAllowed());

        $sessionDefaultValue = new Session();
        $this->assertFalse($sessionDefaultValue->areCookiesAllowed());
    }
}