<?php
namespace LiveTest\Config;

use Base\Debug\DebugHelper;

class TestCaseConfig
{

    private $className;

    private $parameters;

    private $failOnError;

    private $sessionGroupNames = array();

    private $sessionNames = array();

    public function __construct ($className, array $parameters, $failOnError = false)
    {
        $this->className = $className;
        $this->parameters = $parameters;
        $this->failOnError = $failOnError;
    }

    public function getClassName ()
    {
        return $this->className;
    }

    public function getParameters ()
    {
        return $this->parameters;
    }

    public function addSession ($session)
    {
        $this->sessionNames[] = $session;
    }

    public function addSessionGroupName($sessionGroupName)
    {
        $this->sessionGroupNames[] = $sessionGroupName;
    }

    public function getSessionGroupNames()
    {
        return $this->sessionGroupNames;
    }

    public function getSessionNames ()
    {
        return $this->sessionNames;
    }

    public function isDefaultSessionUsed ()
    {
    }

    public function isFailOnError()
    {
        return $this->failOnError;
    }
}