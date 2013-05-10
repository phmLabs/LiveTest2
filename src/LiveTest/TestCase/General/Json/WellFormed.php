<?php
namespace LiveTest\TestCase\General\Json;

use Base\Http\Request\Request;
use Base\Http\Response\Response;
use LiveTest\TestCase\Exception;
use LiveTest\TestCase\TestCase;

/**
 * @author Nicolas Lang
 */
class WellFormed implements TestCase
{
    private $json_errors = array(
        JSON_ERROR_NONE => 'No Error',
        JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
        JSON_ERROR_STATE_MISMATCH => 'Underflow or the modes mismatch',
        JSON_ERROR_CTRL_CHAR => 'Unexpected control character found',
        JSON_ERROR_SYNTAX => 'Syntax error, malformed JSON',
        JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded'
    );

    public function test(Response $response, Request $request)
    {
        $result = json_decode($response->getBody());

        if ($result === null) {
            throw new Exception("The given JSON Data is not well formed (last error: " . $this->json_errors[json_last_error()] . ").");
        }
    }
}