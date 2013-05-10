<?php

namespace Base\Config;

use Symfony\Component\Yaml\Yaml as SymfonyYaml;
use Zend\Config\Config as ZendConfig;
use Zend\Config\Exception\InvalidArgumentException;

class Yaml extends ZendConfig implements Config
{
    private $filename;

    /**
     * @throws InvalidArgumentException from sfYaml::load
     * @param string $filename
     * @param boolean $allowModifications
     */
    public function __construct($filename, $allowModifications = false)
    {
        if (!is_array($filename)) {
            $this->filename = $filename;

            $this->checkIfFilenameIsFile();

            $content = SymfonyYaml::parse($this->filename);

            if (is_null($content)) {
                $content = array();
            }
        } else {
            $content = $filename;
        }

        parent::__construct($content, $allowModifications);
    }

    /**
     * This function returns the filename of the information providing file
     *
     * @see Base\Config\Config::getFilename()
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * This function will throws an exception if $this->filename does not point to
     * an existing file.
     *
     * @throws InvalidArgumentException
     */
    private function checkIfFilenameIsFile()
    {
        if (!is_file($this->filename)) {
            throw new InvalidArgumentException('File described by filename: "' . $this->filename . '" is not a file.');
        }
    }
}