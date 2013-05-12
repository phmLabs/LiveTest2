<?php

namespace LiveTest\Packages\Runner\Listeners;

use LiveTest\ConfigurationException;
use LiveTest\Listener\Base;
use phmLabs\Components\Annovent\Annotation\Event;

class Preconditions extends Base
{
    private $arguments = array();

    /**
     * @Event("LiveTest.Runner.Init")
     * @param array $arguments
     */
    public function runnerInit(array $arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * @Event("LiveTest.Runner.InitTestRun")
     */
    public function checkPreconditions()
    {
    }
}