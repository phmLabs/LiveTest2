<?php

/*
 * This file is part of the LiveTest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LiveTest\Config\Tags\TestSuite;
use Base\Config\Yaml;

use LiveTest\Config\TestSuite as TestSuiteConfig;
use LiveTest\Connection\Request\Symfony as Request;

/**
 * This tag includes a list of pages.
 *
 * @example
 *  IncludePages:
 *   - /impressum.html
 *   - http://www.example.com
 *
 * @author Nils Langner
 */
class IncludeSessions extends Base
{
  /**
   * @see LiveTest\Config\Tags\TestSuite.Base::doProcess()
   */
  protected function doProcess(TestSuiteConfig $config, $sessionFiles)
  {
    foreach ($sessionFiles as $sessionFile)
    {
      $yaml = new Yaml($config->getBaseDir() . '/' . $sessionFile);
      $this->getParser()->parse($yaml->toArray(), $config);
    }
  }
}
