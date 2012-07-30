<?php

/*
 * This file is part of the LiveTest package. For the full copyright and license
 * information, please view the LICENSE file that was distributed with this
 * source code.
 */
namespace LiveTest\TestCase\General\Html;
use Base\Http\Request\Request;
use LiveTest\Connection\Request\Symfony;
use Base\Http\Client\Zend;
use Zend\Http\Client;
use Base\Www\Uri;
use Base\Www\Html\Document;
use LiveTest\TestCase\Exception;
use Base\UriAnalyser;

/**
 * This test case is used to check the availability of included HTTP-Ressources.
 *
 * @author Nils Langner, Timo Juers
 */
class IncludesAvailable extends TestCase
{

    private $urlsToIgnore;
    private $urlsToIgnoreRegEx;

    /**
     * Set urls to ignore
     *
     * @param array $urlsToIgnore
     *            these url will be ignored
     * @param array $urlsToIgnoreRegEx
     *            urls matching these Regex-Patterns will be ignored
     */
    public function init ($urlsToIgnore = array(),$urlsToIgnoreRegEx = array())
    {
        $this->urlsToIgnore = is_array($urlsToIgnore) ? $urlsToIgnore : array();
        $this->urlsToIgnoreRegEx = is_array($urlsToIgnoreRegEx) ? $urlsToIgnoreRegEx : array();
    }

    /**
     * Test execution.
     *
     * @param Document $htmlDocument
     *            the HTML dokument to test
     */
    public function runTest (Document $htmlDocument)
    {
        static $requestedDependecies = array();

        $failureCount = 0;
        $failedUrls = array();

        $files = $htmlDocument->getExternalDependencies();

        $requestUri = new Uri($this->getRequest()->getUri());
        $domain = $requestUri->getDomain();

        foreach ($files as $file) {
            $absoluteFile = $domain->concatUri($file);

            if (in_array($absoluteFile->toString(), $this->urlsToIgnore)) {
                continue;
            }
            
            foreach ($this->urlsToIgnoreRegEx as $pattern){
                if (preg_match ( $pattern , $absoluteFile->toString())) {
                    continue 2;
                }
            }

            if (array_key_exists($absoluteFile->toString(), $requestedDependecies)) {
                $status = $requestedDependecies[$absoluteFile->toString()];
            } else {
                $request = Symfony::create($absoluteFile, Request::GET);

                $client = new Zend();
                $response = $client->request($request);
                $status = $response->getStatus();
                $requestedDependecies[$absoluteFile->toString()] = $response->getStatus();
            }

            if ($status >= 400) {
                $failureCount ++;
                $failedUrls[] = $absoluteFile->toString();
            }
        }
        if ($failureCount > 0) {
            $filesString = $failedUrls[0];
            for ($i = 1; $i < min(5,count($failedUrls)); $i++) {
                $filesString.= ', '.$failedUrls[$i];
            }
            if ( count($failedUrls) > 5 ) {
              $filesString.= ', ...';
            }
            throw new Exception("$failureCount uri availability check(s) failed. " . $filesString);
        }
    }
}

