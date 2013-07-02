<?php

/*
 * This file is part of the LiveTest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LiveTest\Connection\Session;

use Base\Security\Credentials;

use LiveTest\Connection\Request\Request;
use LiveTest\Connection\Session\WarmUp\WarmUp;

/**
 * @author Nils Langner
 */
class Session
{
    private $pageRequests = array();
    private $allowCookies;
    private $httpBasicAuthenticationCredentials = null;

    private $extendedSessions = array();

    private function getHttpBasicAuthenticationCredentials ()
    {
        return $this->httpBasicAuthenticationCredentials;
    }

    private function hasHttpBasicAuthenticationCredentials()
    {
        return !is_null($this->httpBasicAuthenticationCredentials);
    }

	  public function setHttpBasicAuthenticationCredentials (Credentials $httpBasicAuthenticationCredentials)
    {
        $this->httpBasicAuthenticationCredentials = $httpBasicAuthenticationCredentials;
    }

	  public function __construct($allowCookies = false)
    {
        if (!is_bool($allowCookies)) {
            throw new \InvalidArgumentException('The given parameter must be bool.');
        }

        $this->allowCookies = $allowCookies;
    }

    public function extendSession(Session $session)
    {
        $this->extendedSessions[] = $session;
    }

    public function includePageRequest(Request $pageRequest)
    {
        if( $this->hasHttpBasicAuthenticationCredentials() ) {
            $pageRequest->setHttpBasicAuthenticationCredentials($this->getHttpBasicAuthenticationCredentials());
        }
        $this->pageRequests[] = $pageRequest;
    }

    public function includePageRequests(array $pageRequests)
    {
        foreach ($pageRequests as $pageRequest) {
            $this->includePageRequest($pageRequest);
        }
    }

    public function getPageRequests()
    {
        $pageRequests = $this->pageRequests;

        foreach ($this->extendedSessions as $extendedSession) {
            $sessionPageRequests = $extendedSession->getPageRequests();
            $pageRequests = array_merge($pageRequests, $sessionPageRequests);
        }

        return $pageRequests;
    }

    public function areCookiesAllowed()
    {
        return $this->allowCookies;
    }
}