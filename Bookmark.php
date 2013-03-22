<?php
  require_once("global_settings.php");

class Bookmark 
{
  var $id;
  var $user;
  var $name;
  var $url;

  public function __construct($id, $user, $name, $url)
  {
    $this->id   = $id;
    $this->user = $user;
    $this->name = $name;
    $this->url  = $url;  
  }

  public function getId()
  {
    return $this->id;
  }

  public function getName()
  {
    return $this->name;
  }

  public function setName($name)
  {
    $this->name = $name;
  }
  
  public function getUrl()
  {
    return $this->url;
  }

  public function setUrl($url)
  {
    if (filter_var($url, FILTER_VALIDATE_URL) === FALSE)
    {
      throw new Exception('This bookmark\'s url is not valid');
    } else {
       $this->url = $url;
    }
  }

  public function getUser()
  {
    return $this->user;
  }

  public function setUser($user)
  {
    $this->user = $user;
  } 
  
  public function __clone()
  {
    //$this->id = $this->id;
    $this->user = clone $this->user;
    //$this->name = $this->name;
    //$this->url = $this->url;
  }
}

?>