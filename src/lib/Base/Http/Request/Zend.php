<?php
/**
 * @version $Id: Zend.php 1373 2013-05-08 14:33:00Z schoe37 $
 */

namespace Base\Http\Request;

use Zend\Http\Request as ZendRequest;

/**
 * @author Robert Schönthal <schoenthal.robert_FR@guj.de>
 */
class Zend implements Request
{
    public function __construct(ZendRequest $request)
    {
        $this->request = $request;
    }

    public function getUri()
    {
        return $this->request->getUriString();
    }

    public function getMethod()
    {
        return $this->request->getMethod();
    }

    public function getParameters()
    {
        return $this->request->getPost()->toArray();
    }
}