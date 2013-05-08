<?php
namespace LiveTest\TestCase\General\Xml\Sitemap;

use LiveTest\TestCase\General\Xml\Valid as XmlValid;
use Base\Http\Request\Request;
use Base\Http\Response\Response;
use LiveTest\TestCase\TestCase;

class ValidNews implements TestCase
{

    const XSD_SCHEMA = 'Schema/sitemapNews.xsd';

    public function test (Response $response, Request $request)
    {
        $xsdTestCase = new XmlValid();
        $xsdTestCase->init(__DIR__ . DIRECTORY_SEPARATOR . self::XSD_SCHEMA);
        $xsdTestCase->test($response, $request);
    }
}