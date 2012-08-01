<?php

namespace Test\Unit\LiveTest\TestCases\General\Http;

use Base\Http\Header\Header;

use LiveTest\Connection\Request\Symfony;

use LiveTest\TestCase\General\Http\Header\Exists;

use Unit\Base\Http\Response\MockUp;

use Base\Www\Uri;
use Base\Http\Response\Zend;

use LiveTest\TestCase\General\Http\ExpectedStatusCode;

class HeaderExistsTest extends \PHPUnit_Framework_TestCase
{
  public function testNegativeTest()
  {
    $testCase = new Exists();
    $testCase->init('Cache');

    $response = new MockUp();
    $header = new Header(array( 'Cache' => '' ));
    $response->setHeader($header);

    $testCase->test($response, Symfony::create(new Uri('http://www.example.com/')));
  }

  public function testPositiveTest()
  {
    $testCase = new Exists();
    $testCase->init('Cache');

    $response = new MockUp();
    $header = new Header(array( 'No-Cache' => '' ));
    $response->setHeader($header);

    $this->setExpectedException('LiveTest\TestCase\Exception');
    $testCase->test($response, Symfony::create(new Uri('http://www.example.com/')));
  }
}