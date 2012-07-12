<?php
namespace LiveTest\TestCase\General\Xml;

use LiveTest\TestCase\Exception;
use Base\Http\Request\Request;
use Base\Http\Response\Response;
use LiveTest\TestCase\TestCase;

class Valid implements TestCase
{

    private $xsdSchemaFile;

    public function init ($xsdSchema)
    {
        $this->xsdSchemaFile = $xsdSchema;
    }

    public function test (Response $response, Request $request)
    {
        // @todo same code a in the well formed test case
        libxml_clear_errors();
        $dom = new \DOMDocument();
        @$dom->loadXML($response->getBody());
        
        $lastError = libxml_get_last_error();
        if ($lastError) {
            throw new Exception(
                    "The given xml file is not well formed (last error: " .
                             str_replace("\n", '', $lastError->message) . ").");
        }
        $valid = @$dom->schemaValidate($this->xsdSchemaFile);
        if (! $valid) {
            $lastError = libxml_get_last_error();
            $lastErrorMessage = str_replace("\n", '', $lastError->message);
            throw new Exception(
                    "The given xml file did not Validate vs. " .
                             $this->xsdSchemaFile . " (last error: " .
                             $lastErrorMessage . ").");
        }
    }
}