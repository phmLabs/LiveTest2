<?php

/*
 * This file is part of the LiveTest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LiveTest\TestCase\General\Html;

use Base\Www\Html\Document;

use LiveTest\TestCase\Exception;

/**
 * This test case is used to check the existence of are given regular expression.

 * @author Nils Langner
 * @author Nicolas Lang
 */
class RegExMatch extends TestCase
{
  private $regEx;
  private $minOccur;
  private $maxOccur;

  /**
   * This function initializes the regular expression to check against.

   * @param string $regEx
   */
  public function init($regEx,$minOccur = null,$maxOccur = null)
  {
    if (is_null($minOccur) && is_null($maxOccur)) {
      throw new \LiveTest\ConfigurationException('neither minOccur nor maxOccur where set.');
    }
    $this->regEx = $regEx;
    $this->minOccur = $minOccur;
    $this->maxOccur = $maxOccur;
  }
  
  /**
   * This function checks if the regEx is found in the html document. 
   * 
   * @see LiveTest\TestCase\General\Html.TestCase::runTest()
   */
  protected function runTest(Document $htmlDocument)
  {
    $htmlCode = $htmlDocument->getHtml();
    
    if ( ($this->maxOccur=== null || $this->maxOccur=== 0)  && ( $this->minOccur ===null || $this->minOccur <=1)  ) {
      $res = preg_match($this->regEx, $htmlCode);
    } else {
      $res = preg_match_all($this->regEx, $htmlCode, $matches);
    }
    if ($res === false) {
      throw new \LiveTest\ConfigurationException('The RegEx Pattern created a Error.');
    }
    
    if ($this->minOccur !== null && $res < $this->minOccur)
    {
      throw new Exception('The given RegEx "' . $this->regEx . '" did not match at least '.$this->minOccur.' times ( found '.$res.' ).');
    }
    if ($this->maxOccur !== null && $res > $this->maxOccur)
    {
      throw new Exception('The given RegEx "' . $this->regEx . '" matched '.$res.'  times. Allowed: '.$this->maxOccur.' times.');
    }
  }
}