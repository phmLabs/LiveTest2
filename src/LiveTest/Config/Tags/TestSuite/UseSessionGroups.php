<?php
namespace LiveTest\Config\Tags\TestSuite;

/**
 *
 * @author Nils Langner
 */
class UseSessionGroups extends Base
{

    protected function doProcess (\LiveTest\Config\TestSuite $config, $sessionGroupNames)
    {
        $testCaseConfig = $config->getCurrentTestCaseConfig();
        foreach ($sessionGroupNames as $sessionGroupName) {
            $testCaseConfig->addSessionGroupName($sessionGroupName);
        }
    }
}