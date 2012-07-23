<?php

/* This file is part of the LiveTest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Base;


class UriAnalyser
{
  public function isUri( $uri )
  {
    $http = '(http(s)?://)?';
    $www = '(www\.)?';
    $domain = '([a-zA-Z]((\.|\-)?[a-zA-Z0-9])*)';
    $tld = '([a-zA-Z]{2,8})';
    $usw = '[a-zA-Z0-9|_|-|+|.|,|/|:|\?|=|%|&|-]*';

    $regEx = '^'.$http.$www.$domain.'\.'.$tld.$usw.'$';

    return (bool)ereg($regEx, $uri);
  }
  
  
  public function getDomain( $uri )
  {
    if ( !$this->isUri( $uri ))
    {
      throw new Exception( 'Failed extracting domain. URI "'.$uri.'" was not valid.' );
    }
    $pos = strpos( $uri, '/', 8 );
    if ( $pos !== false )
    {
      return substr( $uri, 0, $pos );
    }
    return $uri;
  }
  
 public function isAbsolutePath( $path )
  {
    if ( strlen( $path ) == 0 )
    {
      return false;
    }
    return $path[0] == '/';
  }
  
  public function addBeginningSlash( $url )
  {
    if ( strlen( $url ) == 0 )
    {
      return '/';
    }

    if ( $url[0] == '/' )
    {
      return $url;
    }
    return '/'.$url;
  }
  
  public function concatUrl( $firstPart, $secondPart )
  {
    if ( strpos( $secondPart, '://' ) > 0 )
    {
      $url = $secondPart;
    }
    else
    {
      if ( $this->isAbsolutePath( $secondPart) )
      {
        $url = $this->getDomain( $firstPart ).$this->addBeginningSlash( $secondPart );
      }
      else
      {
        $url = $this->getDomain( $firstPart ).$this->getPath( $firstPart ).$this->addBeginningSlash( $secondPart );
      }
    }
    return $url;
  }
  
  public function getCompletedUri( $currentUri, $uriPart )
  {
    return $this->concatUrl( $currentUri, $uriPart );
  }

  public function getAbsUri( $uriPart )
  {
    return $this->concatUrl( $this->request->getAddress( ), $uriPart );
  }
  
  public function isFile($file)
  {
    return !( substr( $file, 0, 7 ) == 'mailto:' );
  }
  
  public function uriAvailable( $uri )
  {
    $ch = curl_init();

    curl_setopt( $ch, CURLOPT_URL, $uri);
    curl_setopt( $ch, CURLOPT_HEADER, 0);
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt( $ch, CURLOPT_ENCODING, "" );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 30 );

    $body = curl_exec($ch);

    if ( $body === false)
    {
      return false;
    }

    $header = curl_getinfo($ch);

    return (array_key_exists("http_code", $header)) && ($header["http_code"] == 200); 

    curl_close($ch);
  }

}

