<?php

/*
 * This file is part of the LiveTest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LiveTest\Packages\Http\Listeners;

use Base\Http\Client\Client;
use LiveTest\Listener\Base;
use phmLabs\Components\Annovent\Annotation\Event;

/**
 * This listener is used to manipulate the http client configuration.
 *
 * @author Nils Langner
 */
class ClientConfiguration extends Base
{
    private $timeout;
    private $maxRedirects = 68; //strange curl default...

    /**
     * This function sets the timeout and optionally the max redirects count of the http client.
     *
     * @param int $timeout the http client time out in seconds
     * @param int $maxRedirects follow this max redirects. defaults to CURLOPT_MAXREDIRS, use 0 for no redirects.
     */
    public function init($timeout, $maxRedirects = CURLOPT_MAXREDIRS)
    {
        $this->timeout = $timeout;
        $this->maxRedirects = $maxRedirects;
    }

    /**
     * This function sets the timeout and redirect count for the http client.
     *
     * @Event("LiveTest.Runner.InitHttpClient")
     *
     * @param Client $client the HTTP Client
     */
    public function initHttpClient(Client $client)
    {
        $client->setTimeout($this->timeout);
        $client->setMaxRedirect($this->maxRedirects);
    }
}