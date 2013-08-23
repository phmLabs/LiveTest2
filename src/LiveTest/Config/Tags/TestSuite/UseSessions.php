<?php

namespace LiveTest\Config\Tags\TestSuite;

/**
 * @author Nils Langner
 */
class UseSessions extends Base
{
    protected function doProcess(\LiveTest\Config\TestSuite $config, $sessionNames)
    {
        $testCaseConfig = $config->getCurrentTestCaseConfig();
        if (is_array($sessionsNames) ) {
            foreach ($sessionNames as $sessionName) {
                $testCaseConfig->addSession($sessionName);
            }
        }
    }
}
