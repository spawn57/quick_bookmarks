<?php

  require_once("global_settings.php");

  class Checker
  {
    public static function checkEmail($email)
    {
      if (filter_var($email, FILTER_VALIDATE_EMAIL)) 
      {
	return true;
      } else {
	return false;
      }
    }
    
    public static function checkUsername($username)
    {
      if ( strlen($username) < 6 )
	return false;
      
      return true;
    }

    public static function checkPassword($password)
    {
      if ( strlen($password) < 6 )
	return false;
      
      return true;
    }

  }

?>