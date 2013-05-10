<?php

namespace LiveTest\Connection\Request;

use Base\Http\Request\Request as BaseRequest;
use Base\Www\Uri;

interface Request extends BaseRequest
{
    public function getIdentifier();

    public function removeParameter($key);

    public function addParameter($key, $value);
}