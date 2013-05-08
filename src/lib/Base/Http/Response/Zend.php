<?php

namespace Base\Http\Response;

use Base\Http\Header\Header;
use Zend\Http\Response as ZendResponse;

/**
 * @author mikelohmann
 *
 */
class Zend implements Response
{
    private $duration;
    private $zendResponse;

    /**
     * @param ZendResponse $response
     */
    public function __construct(ZendResponse $response, $duration)
    {
        $this->duration = $duration;
        $this->zendResponse = $response;
    }

    public function getStatus()
    {
        return $this->zendResponse->getStatusCode();
    }

    public function getBody()
    {
        return $this->zendResponse->getBody();
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function getHeader()
    {
        return new Header($this->zendResponse->getHeaders()->toArray());
    }
}