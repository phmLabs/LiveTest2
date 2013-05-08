<?php
namespace LiveTest\TestCase\General\Xml\Rss;

use Base\Http\Request\Request;
use Base\Http\Response\Response;
use LiveTest\TestCase\General\Xml\Valid as XmlValid;
use LiveTest\TestCase\TestCase;

class Valid implements TestCase
{

    const XSD_SCHEMA = 'Schema/rss2.0.xsd';

    public function test(Response $response, Request $request)
    {
        $xsdTestCase = new XmlValid();
        $xsdTestCase->init(__DIR__ . DIRECTORY_SEPARATOR . self::XSD_SCHEMA);
        $xsdTestCase->test($response, $request);
    }
}