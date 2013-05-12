<?php

/*
 * This file is part of the LiveTest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LiveTest\Packages\Runner\Listeners;

use LiveTest\Listener\Base;
use LiveTest\TestRun\Properties;
use phmLabs\Components\Annovent\Annotation\Event;

/**
 * This listener echoes the run information before the test start.
 *
 * @author Nils Langner
 */
class InfoHeader extends Base
{
    /**
     * This function echoes the default domian, start time, number of uri and number of tests.
     *
     * @Event("LiveTest.Run.PreRun")
     *
     * @param Properties $properties
     */
    public function preRun(Properties $properties)
    {
        $sessions = $properties->getTestSets();
        $uriCount = 0;
        foreach ($sessions as $testSets) {
            $uriCount += count($testSets);
        }

        $output = $this->getEventDispatcher()->getOutput();

        $output->writeln('  Default Domain  : <comment>' . $properties->getDefaultDomain()->toString().'</comment>');
        $output->writeln('  Start Time      : <comment>' . date('Y-m-d H:i:s').'</comment>');
        $output->writeln('  Number of URIs  : <comment>' . $uriCount .'</comment>');
        $output->writeln('  Number of Tests : <comment>' . $this->getTotalTestCount($properties) .'</comment>');
        $output->writeln('');
    }

    /**
     * This function returns the total number of tests defined in a given properties object.
     *
     * @param Properties $properties
     */
    private function getTotalTestCount(Properties $properties)
    {
        $count = 0;
        foreach ($properties->getTestSets() as $testSets) {
            foreach ($testSets as $testSet) {
                $count += $testSet->getTestCount();
            }
        }
        return $count;
    }
}