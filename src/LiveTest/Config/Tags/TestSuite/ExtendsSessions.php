<?php

/*
 * This file is part of the LiveTest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LiveTest\Config\Tags\TestSuite;

use LiveTest\ConfigurationException;

use LiveTest\Config\TestSuite as TestSuiteConfig;
use LiveTest\Connection\Request\Symfony as Request;

/**
 * @author Nils Langner
 */
class ExtendsSessions extends Base
{
  /**
   * @see LiveTest\Config\Tags\TestSuite.Base::doProcess()
   */
  protected function doProcess(TestSuiteConfig $config, $sessioNames)
  {
    $currentSession = $config->getCurrentSession();
    foreach ($sessioNames as $sessionName)
    {
      if ($config->hasSession($sessionName))
      {
        $currentSession->extendSession($config->getSession($sessionName));
      }
      else
      {
        throw new ConfigurationException(
            "Can't extend an undefined session (" . $sessionName . "). Sessions must be defined before they can be used.");
      }
    }
  }
}
