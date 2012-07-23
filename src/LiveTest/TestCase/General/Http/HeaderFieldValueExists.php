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
 * This test case checks if the value of a specified http header field
 * matches a given regular expression
 *
 * @author Timo Juers
 */
class HeaderFieldValueExists implements TestCase
{
  private $fieldName;
  private $values;

  /**
   * Set the directes to look for
   *
   * @param string $fieldName the name of the header field to check values for
   * @param string $values these values have to be set in the given field
   */
  public function init( $fieldName, $values )
  {
    $this->fieldName = $fieldName;
    $this->values    = $values;
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

    foreach ($this->values as $value)
    {
      if(!$headerAnalyser->headerFieldValueExists($this->fieldName, $value))
      {
        $missing[] = $value;
      }
    }

    if(!empty($missing))
    {
      throw new Exception("Expected cach directives \"" . implode(', ', $missing) . "\" not found in response header field " . $this->fieldName);
    }

  }
}

