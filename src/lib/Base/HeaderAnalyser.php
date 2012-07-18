<?php

/* This file is part of the LiveTest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Base;

use Base\Http\Response\Response;


/**
 * Helper class containing operations on response headers
 * @author Timo Juers
 */
class HeaderAnalyser
{ 
  private $response;

  public function __construct( Response $response )
  {
    $this->response = $response;
  }
  
  /**
   * 
   * Returns true if die Field $fieldName exists in the 
   * response header
   * 
   * @param string $fieldName name of the field to look for
   */
  public function headerFieldExists( $fieldName )
  {
    return array_key_exists($fieldName, $this->response->getHeaders());
  }

  /**
   * 
   * Returns true if the given header field contains the
   * defined value 
   *
   * @param string $fieldName
   * @param string $fieldValue
   */
  public function headerFieldValueExists( $fieldName, $fieldValue )
  {
    if($this->headerFieldExists( $fieldName ))
    {
      $headers = $this->response->getHeaders();
      $fieldContent = $headers[$fieldName];

      return (strpos($fieldContent, $fieldValue) !== false);
    }
    else 
    {
      return false;
    }
  }
}