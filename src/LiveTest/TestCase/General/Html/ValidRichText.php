<?php

/*
 * This file is part of the LiveTest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LiveTest\TestCase\General\Html;

use Base\Www\Html\Validator\RichText;
use Base\Www\Html\Document;
use LiveTest\TestCase\Exception;

/**
 * This test case validates the given markup.
 *
 * @author Nicolas Lang
 * @example:
 *  ValidRichText_html:
 *   TestCase: LiveTest\TestCase\General\Html\ValidRichText
 *
 */
class ValidRichText extends TestCase
{
  /**
   *
   * @var Base\Www\Html\Validator The used validator
   */
  private $_validator = null;

  /**
   * Initialize the validation webservice
   *
   * @todo check if Validator can be injected into init
   */
  public function init($ignoreWarnings=false)
  {
    // prepare http client
    $httpClient = new \Base\Http\Client\Zend();

    // create validator and inject http client
    $this->_validator = new RichText($httpClient,$ignoreWarnings);
  }

  /**
   * Validate the markup using the given validator
   *
   * @see LiveTest\TestCase\General\Html.TestCase::runTest()
   */
  protected function runTest(Document $htmlDocument)
  {
    $errors = $this->_validator->validate($htmlDocument);
    if ( $errors !== false)
    {
      $resultString = $errors[0];
      for ($i = 1; $i < min(5, count($errors)); $i ++) {
            $resultString .= ', ' . $errors[$i];
      }
      throw new Exception( $resultString );
    }
  }
}