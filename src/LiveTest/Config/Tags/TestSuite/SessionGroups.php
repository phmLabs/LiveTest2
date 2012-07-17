<?php

/*
 * This file is part of the LiveTest package. For the full copyright and license
 * information, please view the LICENSE file that was distributed with this
 * source code.
 */
namespace LiveTest\Config\Tags\TestSuite;

/**
 * @author Nils Langner
 */
use LiveTest\Connection\Session\WarmUp\NullWarmUp;
use LiveTest\Connection\Session\Session;

class SessionGroups extends Base
{

    /**
     *
     * @see LiveTest\Config\Tags\TestSuite.Base::doProcess()
     */
    protected function doProcess (\LiveTest\Config\TestSuite $config, $sessions)
    {
        foreach ($sessions as $sessionGroupName => $sessionNames) {
            $config->addSessionGroup($sessionGroupName, $sessionNames);
        }
    }
}