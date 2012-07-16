<?php
namespace LiveTest\TestCase\General\Xml;
use LiveTest\TestCase\Exception;
use Base\Http\Request\Request;
use Base\Http\Response\Response;
use LiveTest\TestCase\TestCase;

class WellFormed implements TestCase
{

    public function test (Response $response, Request $request)
    {
        $xmlContent = $response->getBody();
        libxml_clear_errors();
        $dom = new \DOMDocument();
        @$dom->loadXML($xmlContent);
        $lastError = libxml_get_last_error();
        if ($lastError) {
            throw new Exception("The given xml file is not well formed (last error: " . str_replace("\n", '', $lastError->message) . ").");
        }
    }
}