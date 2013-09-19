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
  
  private $docSource = <<<EODOC
<!DOCTYPE html>
<html>
  <head>    
    <title>Some title</title>    	
    <meta property="og:site_name" content="Doma.in" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="canonical" href="http://some.doma.in" />
    <link rel="alternate" href="http://m.doma.in" media="only screen and (max-width: 640px)" />
    <link rel="image_src" href="http://some.doma.in/images/picture.jpg" /> 
    <link rel="alternate" type="application/rss+xml" title="Doma.in Feed" href="http://some.doma.in/feed.rss" />            
    <link rel="stylesheet" type="text/css" media="all" href="http://some.doma.in/style/resource.css" />
    <link rel="stylesheet" type="text/css" media="all" href="http://some.doma.in/style/another_resource.css" />
    <script type="text/javascript" src="http://some.doma.in/scripts/script.js"></script>
    <script src="http://some.doma.in/scripts/more_script.js"></script>
  </head>
  <body>
    Some content.
  </body>
</html>
EODOC;
  
  protected function setUp()
  {
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
        'http://some.doma.in/style/resource.css',
        'http://some.doma.in/style/another_resource.css',
        'http://some.doma.in/scripts/script.js',
        'http://some.doma.in/scripts/more_script.js'
        );
    
    $this->assertEmpty($this->docEmpty->getExternalDependencies());
    $this->assertSame($expectedValues, $this->doc->getExternalDependencies());
  }
}
 