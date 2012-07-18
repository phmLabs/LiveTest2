<?php

/* This file is part of the LiveTest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LiveTest\TestCase\General\Http;

use Base\Http\Request\Request;
use Base\Http\Response\Response;
use Base\HeaderAnalyser;

use LiveTest\TestCase\TestCase;
use LiveTest\TestCase\Exception;

/**
 * This test case checks the cache-control field for
 * the existence of given cache directives  
 *
 * @author Timo Juers
 */
class HeaderFieldValueCacheControlExists implements TestCase
{
  const FIELD_NAME = "Cache-control";
  private $directives;

  /**
   * Set the directes to look for
   *
   * @param array $directives cache directives to look for
   */
  public function init( $directives )
  {
    $this->directives    = $directives;
  }

  /**
   * Checks for given directives in the cache control header
   *
   * @param Response $response recieved response
   * @param Request  $request request we sent
   */
  public function test(Response $response, Request $request)
  {
    $headerAnalyser = new HeaderAnalyser($response);
    $missing = array();

    foreach ($this->directives as $directive)
    {
      if(!$headerAnalyser->headerFieldValueExists(self::FIELD_NAME, $directive))
      {
        $missing[] = $directive;
      }
    }
    
    if(!empty($missing))
    {
        throw new Exception("Expected cach directives \"" . implode(', ', $missing) . "\" not found in response header field " . self::FIELD_NAME);
    }
    
  }
}

