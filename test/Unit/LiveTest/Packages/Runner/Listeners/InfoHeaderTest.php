<?php

namespace Unit\LiveTest\Packages\Runner\Listeners;

use LiveTest\Cli\EchoOutput;
use LiveTest\Event\Dispatcher;

use Base\Config\Yaml;
use Base\Www\Uri;

use LiveTest\TestRun\Properties;
use LiveTest\Packages\Runner\Listeners\InfoHeader;
use Symfony\Component\Console\Output\StreamOutput;

class InfoHeaderTest extends \PHPUnit_Framework_TestCase
{
    private $yamlTestSuiteConfig = '/fixtures/InfoHeaderTestSuiteConfig.yml';

    private $listener;

    protected function setUp()
    {
        $this->listener = new InfoHeader('', new Dispatcher(new EchoOutput()));
    }

    public function testPreRun()
    {
        $properties = Properties::createByYamlFile(__DIR__ . $this->yamlTestSuiteConfig, new Uri('http://www.example.com'), new Dispatcher());

        ob_start();
        $this->listener->preRun($properties);
        $output = ob_get_contents();
        ob_clean();

        $this->assertContains('Default Domain  : http://www.example.com', $output);
        $this->assertContains('Number of URIs  : 3', $output);
        $this->assertContains('Number of Tests : 6', $output);
    }
}