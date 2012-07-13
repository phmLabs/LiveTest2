<?php

namespace LiveTest\Connection\Request;

use Base\Www\Uri;
use Base\Http\Request\Request as BaseRequest;

interface Request extends BaseRequest
{
  public function getIdentifier();
  public function removeParameter($key);
  public function addParameter($key, $value);
}