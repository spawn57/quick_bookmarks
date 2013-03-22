<?php
  require_once("global_settings.php");

  require_once("Checker.php");

  class RegisterUser 
  {
    var $username;
    var $password;
    var $email;

    public function __construct($username, $password, $email)
    {
      $username = strtolower($username);

      if ( Checker::checkUsername($username) )
	$this->username = $username;
      else
	throw new Exception("username isn't right");

      if ( Checker::checkPassword($password) )
	$this->password = $password;
      else
	throw new Exception("password doesn't make the cut");

      if ( Checker::checkEmail($email) )
	$this->email = $email;
      else
	throw new Exception("email isn't real, man");
    }

    public function getUsername()
    {
      return $this->username;
    }

    public function getPassword()
    {
      return $this->password;
    }

    public function getEmail()
    {
      return $this->email;
    }
    
  }
?>