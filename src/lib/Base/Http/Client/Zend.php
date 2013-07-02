<?php

namespace Base\Http\Client;

use Base\Http\Request\Request;
use Base\Timer\Timer;
use Zend\Http\Client as ZendClient;
use Base\Security\Credentials;

class Zend extends ZendClient implements Client
{
    private $httpBasicAuthenticationCredentials;

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

        if( $request->hasHttpBasicAuthenticationCredentials()) {
            $credentials = $request->getHttpBasicAuthenticationCredentials();
            $this->setAuth($credentials->getUsername(), $credentials->getPassword());
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