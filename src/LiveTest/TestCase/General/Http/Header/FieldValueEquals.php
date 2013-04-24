<?php

/*
 * This file is part of the LiveTest package. For the full copyright and license
 * information, please view the LICENSE file that was distributed with this
 * source code.
 */
namespace LiveTest\TestCase\General\Http\Header;
use Base\Http\Request\Request;
use Base\Http\Response\Response;
use LiveTest\TestCase\TestCase;
use LiveTest\TestCase\Exception;

/**
 * This test case checks if the value of a specified http header field
 * matches a given regular expression
 *
 * @author Timo Juers
 * @author Nils Langner
 */
class FieldValueEquals implements TestCase
{

    private $fieldName;

    private $values;

    /**
     * Set the directes to look for
     *
     * @param array $directives
     *            cache directives to look for
     */
    public function init ($fieldName, $values)
    {
        $this->fieldName = $fieldName;
        $this->values = $values;
    }

    /**
     * Checks for given directives in the cache control header
     *
     * @param Response $response
     *            recieved response
     * @param Request $request
     *            request we sent
     */
    public function test (Response $response, Request $request)
    {
        $header = $response->getHeader();
        $missing = array();

        foreach ($this->values as $value) {
            if (! $header->hasField($this->fieldName) && ! $header->getField($this->fieldName) == $value) {
                $missing[] = $value;
            }
        }

        if (! empty($missing)) {
            throw new Exception("Expected header fields not set correct \"" . implode(', ', $missing) . ")");
        }
    }
}