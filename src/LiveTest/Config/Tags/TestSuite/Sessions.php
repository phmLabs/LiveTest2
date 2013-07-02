<?php

/*
 * This file is part of the LiveTest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LiveTest\Config\Tags\TestSuite;

use LiveTest\ConfigurationException;

use Base\Security\Credentials;

use LiveTest\Connection\Session\Session;
use LiveTest\Connection\Session\WarmUp\NullWarmUp;

/**
 * This tag adds the test cases to the configuration. All tags that are not known withing this class are
 * handed to parser.
 *
 * @example
 * Sessions:
 * user_handling:
 * Pages:
 * - /login.php:
 * key1: value1
 * post:
 * key2: value2
 * - /index.php
 *
 *
 * @author Nils Langner
 */
class Sessions extends Base
{
    /**
     * This functions add the base authentication credentials to the session.
     *
     * @param Session $session
     * @param array $sessionCredentials
     * @throws ConfigurationException
     */
    private function setHttpBaseAutehtication(Session $session, $sessionCredentials)
    {
        $username = $sessionCredentials['Username'];
        $password = $sessionCredentials['Password'];
        if( $username == "" || $password == "" ) {
            throw new ConfigurationException("Username and Password must be set when using http base authentication");
        }
        $session->setHttpBasicAuthenticationCredentials(new Credentials($username, $password));
    }

    /**
     * @see LiveTest\Config\Tags\TestSuite.Base::doProcess()
     */
    protected function doProcess(\LiveTest\Config\TestSuite $config, $sessions)
    {
        // @todo $hasDefaultSession = false;
        foreach ($sessions as $sessionName => $sessionParameter) {
            if (array_key_exists('AllowCookies', $sessionParameter)) {
                $allowCookies = $sessionParameter['AllowCookies'];
            } else {
                $allowCookies = false;
            }

            $session = new Session($allowCookies);

            if( array_key_exists('HttpBaseAuthentication', $sessionParameter)) {
                $this->setHttpBaseAutehtication($session, $sessionParameter["HttpBaseAuthentication"]);
            }

            $config->addSession($sessionName, $session);
            $config->setCurrentSession($sessionName);

            unset($sessionParameter['AllowCookies']);
            unset($sessionParameter['HttpBaseAuthentication']);

            $parser = $this->getParser()->parse($sessionParameter, $config);
        }
    }
}