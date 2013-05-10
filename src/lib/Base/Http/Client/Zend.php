<?php

namespace Base\Http\Client;

use Base\Http\Request\Request;
use Base\Timer\Timer;
use Zend\Http\Client as ZendClient;

class Zend extends ZendClient implements Client
{
    public function request(Request $request)
    {
        $method = $request->getMethod();

        $parameters = $request->getParameters();

        $this->setUri($request->getUri());

        if (!strcasecmp($method, Request::GET)) {
            $this->setParameterGet($parameters);
        } else if (!strcasecmp($method, Request::POST)) {
            $this->setParameterPost($parameters);
        }

        $timer = new Timer();
        $this->setMethod($method);
        $response = $this->send();
        $duration = $timer->stop();

        return new \Base\Http\Response\Zend($response, $duration);
    }

    public function setTimeout($timeInSeconds)
    {
        $this->config['timeout'] = $timeInSeconds;
    }

    public function setMaxRedirect($maxRedirects)
    {
        $this->config['maxredirects'] = $maxRedirects;
    }
}