<?php

namespace Unit\Base\Http\Response;

use Base\Http\Response\Response;

class MockUp implements Response
{
  private $body = '';
  private $duration = 0;
  private $header;
  private $status = 200;

  public function setBody($body)
  {
    $this->body = $body;
  }

  public function setDuration($duration)
  {
    $this->duration = $duration;
  }

  public function setHeader($header)
  {
    $this->header = $header;
  }

  public function setStatus($status)
  {
    $this->status = $status;
  }

  public function getBody()
  {
    return $this->body;
  }

  public function getDuration()
  {
    return $this->duration;
  }

  public function getHeader()
  {
    return $this->header;
  }

  public function getStatus()
  {
    return $this->status;
  }
}