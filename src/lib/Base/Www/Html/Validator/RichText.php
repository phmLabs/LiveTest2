<?php

/*
 * This file is part of the LiveTest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Base\Www\Html\Validator;

use Base\Www\Uri;

use Symfony\Component\HttpFoundation\Request;

use LiveTest\Connection\Request\Symfony;

use Base\Www\Html\Validator;
use Base\Www\Html\Document;
use Base\Http\Client\Client;

/**
 *
 * Class for validating markup using the
 * google RichText validation service.
 *
 * @author Nicolas Lang
 */
class RichText implements Validator
{
  /**
   *
   * @var string validator URI
   */
  private $_validatorUri = 'http://www.google.com/webmasters/tools/richsnippets';
  private $resultWarningXpath = "/html/body//span[@class='warning']";
  private $resultErrorXpath = "/html/body//div[@id='richsnippets-errors']/ul[@class='warning']/li";

  /**
   *
   * @var Base\Http\Client\Client client used for validating
   */
  private $_httpClient = null;
  private $_ignoreWarnings;
  /**
   * @var Symfony
   */
  private $request;
  

  /**
   *
   * Create validator instance
   */
  public function __construct(Client $httpClient,$ignoreWarnings=false)
  {
    // prepare the injected http client
    $this->_httpClient = $httpClient;
    $this->_ignoreWarnings = $ignoreWarnings;
  }

  /**
   *
   * @see Base\Validator.Html::validateHtml()
   *
   * @param string markup to validate
   * @return bool Is valid markup?
   */
  public function validate(Document $htmlDocument)
  {
    $rawDocument = $htmlDocument->getHtml();
    
    $postVars = array('htmlcontent' => $rawDocument);
    $request = Symfony::create(new Uri($this->_validatorUri), \Base\Http\Request\Request::POST, $postVars );

    $response = $this->_httpClient->request($request);

    return $this->_parseReponse($response->getBody());
  }

  /**
   *
   * @param string xml reponse from validator
   * @return bool valid?
   */
  private function _parseReponse($soapReponse)
  {
    // parse reponse
    $doc = new \DOMDocument();
    if (!@$doc->loadHTML($soapReponse))
    {
      throw new Exception('Can\'t create DOMDocument from returned HTML');
    }
    $domXPath = new \DOMXPath($doc);
    $warnings = @$domXPath->query($this->resultWarningXpath);
    $errors = @$domXPath->query($this->resultErrorXpath);
    if ($warnings ===false || $errors ===false)
    {
      throw new Exception('Can\'t query DOMDocument, xpath wrong ?');
    }
    
    if ((0 === $warnings->length || $this->_ignoreWarnings )&& 0 === $errors->length)
    {
      return false; // errors = false
    }
    $result = array('Validation Failed, check '.$this->_validatorUri);
    
    if (0 !== $warnings->length) {
      //@todo:  extract warnings
      array_push($result,$warnings->length.' Warnings ');
    }
    if (0 !== $errors->length) {
      //@todo:  extract errors
      array_push($result,$errors->length.' Errors');
    }
    return $result;
  }
    
}