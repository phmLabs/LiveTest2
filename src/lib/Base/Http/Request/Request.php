<?php

namespace Base\Http\Request;

interface Request
{
    const POST = 'post';
    const GET = 'get';
    const PUT = 'put';

    public function getUri();

    public function getMethod();

    public function getParameters();
}
