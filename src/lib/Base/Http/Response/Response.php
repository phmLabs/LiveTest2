<?php

namespace Base\Http\Response;

interface Response
{
    public function getStatus();

    public function getBody();

    /**
     * Returns the duration in milliseconds
     */
    public function getDuration();

    /**
     * @returns \Base\Http\Header\Header
     */
    public function getHeader();
}