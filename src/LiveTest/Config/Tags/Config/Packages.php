<?php

/*
 * This file is part of the LiveTest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LiveTest\Config\Tags\Config;
use Base\Config\Yaml;
use LiveTest\Config\ConfigConfig;

/**
 * This tag is used to extend the include path. It is needed because it must be possible to
 * extend LiveTest without touching the core.
 *
 * @example
 *  Packages:
 *   - /tmp
 *   - c:/
 *
 * @author Nils Langner
 */
class Packages extends Base
{
    private $namespaceRoots = array();

    /**
     * @todo check if the directory really exists
     * @see LiveTest\Config\Tags\Config.Base::doProcess()
     */
    protected function doProcess(ConfigConfig $config, $packages)
    {
        foreach ($packages as $key => $package) {
            $packageName = $package . '/package.yml';
            $yaml = new Yaml($packageName);
            $packageArray = $yaml->toArray();
            if (array_key_exists('NamespaceRoot', $packageArray)) {
                $this->namespaceRoots[] = $package . DIRECTORY_SEPARATOR . $packageArray['NamespaceRoot'];
                unset($packageArray['NamespaceRoot']);
            }
            $this->getParser()->parse($packageArray, $config);
        }
        spl_autoload_register(array($this, 'autoload'));
    }

    public function autoload($classname)
    {
        foreach ($this->namespaceRoots as $path) {
            $classPath = $path . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.php';
            if (file_exists($classPath)) {
                include_once $classPath;
            }
        }
    }
}
