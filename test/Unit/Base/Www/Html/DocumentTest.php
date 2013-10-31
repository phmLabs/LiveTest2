<?php
/**
 * Created by PhpStorm.
 * User: Christian Flack
 * Date: 18.09.13
 * Time: 19:28
 */

namespace Test\Unit\baselibrary\Www\Html;
use Base\Www\Html\Document as Document;

class DocumentTest extends \PHPUnit_Framework_TestCase 
{
  protected $doc;
  protected $docEmpty;
  
  private $docSource;
  
  protected function setUp()
  {
    $fixtureDir = __DIR__ . '/fixtures';

    if (file_exists($fixtureDir . '/document.html')) {
      $this->docSource = file_get_contents(
          $fixtureDir . '/document.html'
      );
    }
    $this->docEmpty = new Document('');

    $this->doc = new Document( $this->docSource );
  }
  
  public function testHasHead()
  {
    $docHead = $this->doc->hasHead();
    $this->assertTrue($docHead); 
    
    $docEmptyHead = $this->docEmpty->hasHead();
    $this->assertFalse($docEmptyHead);
  }
  
  /**
   * @expectedException Exception
   * @expectedExceptionMessage No head tag existing.
   */
  public function testGetHeadException()
  {
    $docEmptyHead = $this->docEmpty->getHead();    
  }
  
  public function testGetHead()
  {
    $head = $this->doc->getHead();
    $this->assertTrue(!empty($head));
    $this->assertObjectHasAttribute('content', $head);
    $this->assertObjectHasAttribute('title', $head);
    $this->assertSame('Some title', $head->getTitle());    
  }
  
  public function testGetHtml()
  {
    $this->assertSame($this->docSource, $this->doc->getHtml());
    $this->assertEmpty($this->docEmpty->getHtml());
  }
  
  public function testGetExternalDependencies()
  {
    $expectedValues = array(
        'http://some.doma.in/style/resource.css?cacheBuster=9',
        'http://some.doma.in/style/another_resource.css',
        'http://some.doma.in/scripts/script.js?cache=Xkllo908',
        'http://some.doma.in/scripts/more_script.js'
        );
    
    $this->assertEmpty($this->docEmpty->getExternalDependencies());
    $this->assertSame($expectedValues, $this->doc->getExternalDependencies());
  }
}
 