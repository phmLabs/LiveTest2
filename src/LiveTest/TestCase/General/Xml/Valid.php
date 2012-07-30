<?php
namespace LiveTest\TestCase\General\Xml;
use LiveTest\TestCase\Exception;
use Base\Http\Request\Request;
use Base\Http\Response\Response;
use LiveTest\TestCase\TestCase;

class Valid implements TestCase
{
    private $xsdSchemaFile;

    /*how could this be done better? switch needed between absolute path, included path and remote path relative to testsuite*/
    public function init($xsdSchema)
    {
      $this->xsdSchemaFile = __DIR__ .$xsdSchema;
    }
    
    public function test (Response $response, Request $request)
    {
        libxml_clear_errors();
        $dom = new \DOMDocument();
        @$dom->loadXML($response->getBody());
        
        $lastError = libxml_get_last_error();
        if ($lastError) {
            throw new Exception("The given xml file is not well formed (last error: " . str_replace("\n", '', $lastError->message) . ").");
        }
        $valid =  @$dom->schemaValidate($this->xsdSchemaFile);
        if (!$valid) {
            $lastError = libxml_get_last_error();
            $lastErrorMessage = str_replace("\n", '', $lastError->message);
            throw new Exception("The given xml file did not Validate vs. " .$this->xsdSchemaFile." (last error: ".$lastErrorMessage. ").");
        }
        
    }
}