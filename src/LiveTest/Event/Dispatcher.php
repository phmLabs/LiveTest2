<?php

/*
 * This file is part of the LiveTest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LiveTest\Event;

use LiveTest\Cli\EchoOutput;
use LiveTest\Config\ConfigConfig;
use Symfony\Component\Console\Output\OutputInterface;
use phmLabs\Components\Annovent\Dispatcher as AnnoventDispatcher;
use phmLabs\Components\Annovent\Event\Event;

/**
 * This dispatcher is a standard Annovent dispatcher with the possibility to register
 * listeners using a ConfigConfig object.
 *
 * @author Nils Langner
 */
class Dispatcher extends AnnoventDispatcher
{
    /**
     * @var OutputInterface
     */
    private $output;

    public function __construct(OutputInterface $output = null)
    {
        $this->output = $output;

        parent::__construct();
    }

    /**
     * @return OutputInterface
     */
    public function getOutput()
    {
        if (!$this->output) {
            return new EchoOutput();
        }

        return $this->output;
    }

    /**
     * This function is used to register listeners using a global configuration file
     *
     * @param ConfigConfig $config
     * @param string $runId
     */
    public function registerByConfig(ConfigConfig $config, $runId)
    {
        foreach ($config->getListeners() as $listener) {
            $className = $listener['className'];
            if (!class_exists($className)) {
                throw new \LiveTest\ConfigurationException('Listener not found (' . $className . ').');
            }
            $listenerObject = new $className($runId, $this);
            \LiveTest\Functions::initializeObject($listenerObject, $listener['parameters']);
            $this->connectListener($listenerObject, $listener['priority']);
        }
    }

    public function simpleNotify($name, array $namedParameters = array())
    {
        $event = new Event($name, $namedParameters);
        return $this->notify($event, $namedParameters);
    }
}