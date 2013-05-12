<?php

namespace LiveTest\Cli;

use Symfony\Component\Console\Output\NullOutput;

class EchoOutput extends NullOutput
{
    /**
     * Writes a message to the output.
     *
     * @param string  $message A message to write to the output
     * @param Boolean $newline Whether to add a newline or not
     */
    protected function doWrite($message, $newline)
    {
        echo strip_tags($message);

        if ($newline) {
            echo "\n";
        }
    }
}