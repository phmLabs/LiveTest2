<?php

/*
 * This file is part of the LiveTest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LiveTest\Connection\Session;

use Base\Http\Client\Client;

use LiveTest\Connection\Session\WarmUp\WarmUp;

use LiveTest\Connection\Request\Request;

/**
 * @author Nils Langner
 */
class Session
{
  private $pageRequests = array();
  private $allowCookies;

  private $extendedSessions = array();

  public function __construct($allowCookies = false)
  {
    if (!is_bool($allowCookies))
    {
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
    $this->pageRequests[] = $pageRequest;
  }

  public function includePageRequests(array $pageRequests)
  {
    foreach ($pageRequests as $pageRequest)
    {
      $this->includePageRequest($pageRequest);
    }
  }

  public function getPageRequests()
  {
    $pageRequests = $this->pageRequests;

    foreach ($this->extendedSessions as $extendedSession)
    {
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
