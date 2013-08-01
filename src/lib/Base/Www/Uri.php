<?php
namespace Base\Www;
use Zend\Validate\Callback;

class Uri
{

    private $uri;

    public function __construct($uriString)
    {
        //$uriString = $this->checkCorrectUrl($uriString);
        // @todo: http://www.example.com fails on validation with ParserTest.php
        // and I
        // cannot find any error in it. So: lets validate it by Symfony's
        // Request-Object.
        /*
         * if (!self::isValid($uriString)) { throw new \Base\Www\Exception('The given string (' . $uriString . ') does
         * not represent a valid uri'); }
         */
        $this->uri = $uriString;
    }

    public function __toString()
    {
        return $this->toString();
    }

    public function toString()
    {
        return $this->uri;
    }


    /**
     * This static function returns true if a given string represents a valid
     * uri, otherwise false.
     *
     * @param string $uriString
     */
    public static function isValid($uriString)
    {
        /**
         *
         * @todo : Check if Zend_Validator_Callback can do the same.
         *       Used from:
         *       http://phpcentral.com/208-url-validation-in-php.html#post576
         */
        $urlregex = "^(((https?|ftp)\:)?\/\/)?";

        // USER AND PASS (optional)
        $urlregex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?";

        // HOSTNAME OR IP
        $urlregex .= "[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)*"; // http://x = allowed
        // (ex.
        // http://localhost,
        // http://routerlogin)
        // $urlregex .=
        // "[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)+";
        // // http://x.x =
        // minimum
        // $urlregex .=
        // "([a-z0-9+\$_-]+\.)*[a-z0-9+\$_-]{2,3}";
        // // http://x.xx(x) =
        // minimum
        // use only one of the
        // above

        // PORT (optional)
        $urlregex .= "(\:[0-9]{2,5})?";
        // PATH (optional)
        $urlregex .= "(\/([a-z0-9+\$_-~]\.?)+)*\/?";
        // GET Query (optional)
        $urlregex .= "(\?[a-z+&\$_.-][a-z0-9;:@\/&%=+\$_.-]*)?";
        // ANCHOR (optional)
        $urlregex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?\$";

        $urlregex = "/" . $urlregex . "/";
        return (bool)preg_match($urlregex, $uriString);
    }

    /**
     * Concatenates the current uri object with a given uri string
     *
     * @param string $uriString must be an absoulte uri e.g. http://mytesturi.com/myrubric/test.html
     *                          or an uri relative to the current domain e.g. /myrubric/test.html
     *                          or an uri relative to the current uri e.g. myrubric/test.html
     */
    public function concatUri($uriString)
    {
        $uri = $this->uri;

        // uri string is absolute
        if ((strpos($uriString, 'http://') !== FALSE) || (strpos($uriString, 'https://') !== FALSE)) {
            $uri = $uriString;
        } elseif (strpos($uriString, '//') === 0) {
            // url string gives no protocol, which means the same protocol as current page should be used
            $uri = $this->getProtocol().":".$uriString;
        } elseif (strpos($uriString, '/') === 0) {
            // uri string is relative to base uri
            $uri = $this->concatUriTerms($this->getDomain(), $uriString);
        } else {
            // uri string is relative to current path
            $uri = $this->concatUriTerms($this->concatUriTerms($this->getDomain(), $this->getPath()), $uriString);
        }

        return new self($uri);
    }

    /**
     * @param unknown_type $uriString
     */
    private function checkCorrectUrl($uriString)
    {
        $uriString = trim($uriString);
        $uriParts = parse_url($uriString);

        if (key_exists('path', $uriParts)) {
            $url = $uriString;
        } else {
            $url = $uriString . '/';
        }

        return $url;
    }

    /**
     * Concatenates two strings (uri terms). The most important job is the handling of
     * multiple slashes you dont need to care for. The first term could have a trailing
     * slash and / or the second term a leading slash. This method cares to use only
     * one slash to concatenate both terms in any case.
     *
     * @param string $firstTerm the first (left) term to concatenate
     * @param string $secondTerm the secondTerm concatenate rightmost to the left term
     *
     */
    private function concatUriTerms($firstTerm, $secondTerm)
    {

        // two slahes (leading and trailing)
        if ((substr($firstTerm, strlen($firstTerm) - 1) == "/") && (strpos($secondTerm, "/") === 0)) {
            $result = $firstTerm . substr($secondTerm, 1);
        } // one slash
        elseif ((substr($firstTerm, strlen($firstTerm) - 1) == "/") || (strpos($secondTerm, "/") === 0)) {
            $result = $firstTerm . $secondTerm;
        } // no slashes at all
        elseif (!empty($secondTerm)) {
            $result = $firstTerm . "/" . $secondTerm;
        } else {
            $result = $firstTerm;
        }

        return $result;

    }

    public function getDomain()
    {
        // @todo look for the third /, not after position 8
        $pos = strpos($this->uri, '/', 8);
        if ($pos !== false) {
            return new self(substr($this->uri, 0, $pos));
        }
        return new self($this->uri);
    }

    /**
     * Extracts the path from the curent uri (without the rightmost term)
     */
    public function getPath()
    {
        $parsedUri = parse_url($this->uri);

        if (array_key_exists('path', $parsedUri)) {
            $path = $parsedUri['path'];
            $rPath = strrev($path);
            $pathLength = strlen($rPath) - strpos($rPath, '/') - 1;
            return substr($path, 0, $pathLength);

        }

        return $this->uri;
    }
    
    /**
     * retrieve appropriate protocol or scheme (e.g. http:)
     * Note: Colon has to be added on receiving end to construct a valid url
     * 
     * @return String $scheme
     */
    public function getProtocol() 
    {
      $parsedUri = parse_url($this->uri);
      $scheme = "";
      if (array_key_exists('scheme', $parsedUri)) {
        $scheme = $parsedUri['scheme'];
      }
      
      return $scheme;
    }
}