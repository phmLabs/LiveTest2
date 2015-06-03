<?php

/*
 * This file is part of the LiveTest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LiveTest\TestCase\General\Html\Dom\XPath;

use LiveTest\ConfigurationException;
use LiveTest\InvalidArgumentException;

//use LiveTest\TestCase\General\Html\Dom\XPath\Exception;
use DOMXPath;

/**
 * This test case checks if a given (or more) xpath is existing.
 *
 * @example
 * Xpaths:
 *  TestCase: LiveTest\TestCase\General\Html\Dom\XPath\Exists
 *  Parameter:
 *  xpaths:
 *   - /html
 *   - /html/body
 *
 * Xpath:
 *  TestCase: LiveTest\TestCase\General\Html\Dom\XPath\Exists
 *  Parameter:
 *  xpaths: /html
 *
 * @author Nils Langner
 */
class MinCount extends TestCase
{
    private $xpath;

    private $count;

    /**
     * Sets the xpaths to be checked
     *
     * @see LiveTest\TestCase\General\Html\Dom\XPath.TestCase::init()
     * @throws LiveTest\ConfigurationException
     *
     * @param $xpath
     * @param $xpaths
     */
    public function init($xpath, $count)
    {
        $this->xpath = $xpath;
        $this->count = $count;
    }

    /**
     * This function checks if the given xpaths are existing.
     *
     * @see LiveTest\TestCase\General\Html\Dom\XPath.TestCase::doXPathTest()
     * @throws LiveTest\TestCase\Exception
     *
     * @param DOMXPath $domXPath
     */
    protected function doXPathTest(DOMXPath $domXPath)
    {
        $elements = $domXPath->query($this->xpath);
        if ($elements->length < $this->count) {
            throw new Exception('The given xpath ("' . $this->xpath . '") was not found ' . $this->count . ' times (' . $elements->length . ' elements found).', $this->xpath);
        }
    }
}