<?php

/*
 * This file is part of the LiveTest package. For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace LiveTest\TestCase\General\Html;

use Base\Http\Client\Zend;
use Base\Http\Request\Request;
use Base\Www\Html\Document;
use Base\Www\Uri;
use LiveTest\Connection\Request\Symfony;
use LiveTest\TestCase\Exception;

/**
 * This test case is used to check the availability of included HTTP-Ressources.
 *
 * @author Nils Langner, Timo Juers
 */
class IncludesAvailable extends TestCase
{

    private $urlsToIgnore;

    private $urlsToIgnoreRegEx;

    private $timeoutInSeconds;

    /**
     * Set urls to ignore
     *
     * @param array $urlsToIgnore these url will be ignored
     * @param array $urlsToIgnoreRegEx urls matching these Regex-Patterns will be ignored
     */
    public function init($urlsToIgnore = array(), $urlsToIgnoreRegEx = array(), $timeoutInSeconds = 1)
    {
        $this->urlsToIgnore = is_array($urlsToIgnore) ? $urlsToIgnore : array();
        $this->urlsToIgnoreRegEx = is_array($urlsToIgnoreRegEx) ? $urlsToIgnoreRegEx : array();
        $this->timeoutInSeconds = $timeoutInSeconds;
    }

    /**
     * Returns true if the given url has to be ignored.
     * Ignored urls or regex must be set using the
     * init method.
     *
     * @param string $url
     * @return boolean
     */
    private function isIgnored($url)
    {
        if (in_array($url, $this->urlsToIgnore)) {
            return true;
        }
        foreach ($this->urlsToIgnoreRegEx as $pattern) {
            if (preg_match($pattern, $url)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Test execution.
     *
     * @param Document $htmlDocument the HTML dokument to test
     */
    public function runTest(Document $htmlDocument)
    {
        static $requestedDependencies = array();

        $failedUrls = array();
        $files = $htmlDocument->getExternalDependencies();
        $requestUri = new Uri($this->getRequest()->getUri());

        foreach ($files as $file) {
            try {

                $absoluteFile = $requestUri->concatUri($file);

                if (!$this->isIgnored($absoluteFile->toString())) {
                    if (array_key_exists($absoluteFile->toString(), $requestedDependencies)) {
                        $status = $requestedDependencies[$absoluteFile->toString()];
                    } else {
                        $request = Symfony::create($absoluteFile, Request::GET);

                        $client = new Zend();
                        $client->setTimeout($this->timeoutInSeconds);
                        $response = $client->request($request);
                        $status = $response->getStatus();
                        $requestedDependencies[$absoluteFile->toString()] = $response->getStatus();
                    }

                    if ($status >= 400) {
                        $failedUrls[] = $absoluteFile->toString();
                    }
                }
            } catch (\Zend\Http\Client\Adapter\Exception\RuntimeException $ex) {
                $failedUrls [] = $absoluteFile->toString();
            }
        }
        if (count($failedUrls) > 0) {
            $this->handleFailures($failedUrls);
        }
    }

    /**
     * Failure Handling for LiveTest
     *
     * @param string[] $failedUrls
     * @throws Exception
     */
    private function handleFailures($failedUrls)
    {
        $filesString = $failedUrls[0];
        for ($i = 1; $i < min(5, count($failedUrls)); $i++) {
            $filesString .= ', ' . $failedUrls[$i];
        }
        if (count($failedUrls) > 5) {
            $filesString .= ', ...';
        }
        throw new Exception(count($failedUrls) . " uri availability check(s) failed. " . $filesString);
    }
}