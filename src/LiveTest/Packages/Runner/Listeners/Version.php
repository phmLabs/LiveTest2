<?php

/*
 * This file is part of the LiveTest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LiveTest\Packages\Runner\Listeners;

use LiveTest\Listener\Base;
use phmLabs\Components\Annovent\Annotation\Event;
use phmLabs\Components\Annovent\Event\Event as BaseEvent;

/**
 * @author Nils Langner
 */
class Version extends Base
{
    /**
     * @var string The filename. Relative to __DIR__.
     */
    private $template = 'Help/template.tpl';

    /**
     * @Event("LiveTest.Runner.Init")
     *
     * @param array $arguments
     */
    public function runnerInit(array $arguments, BaseEvent $event)
    {
        if (array_key_exists('version', $arguments)) {
            $credentials = new Credentials($this->getRunId(), $this->getEventDispatcher());
            $event->setProcessed();
        }
    }
}