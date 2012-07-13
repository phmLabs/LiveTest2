<?php

/*
 * This file is part of the LiveTest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LiveTest\Config\Tags\Config;

use LiveTest\Config\ConfigConfig;

/**
 * @author Nils Langner
 */
class RegisteredTags extends Base
{
  /**
   * @todo check if the directory really exists
   * @see LiveTest\Config\Tags\Config.Base::doProcess()
   */
  protected function doProcess(ConfigConfig $config, $tags)
  {
    foreach ($tags as $key => $classname)
    {
      $this->getParser()->registerTag($key, $classname);
    }
  }
}