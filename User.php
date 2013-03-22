<?php
  require_once("global_settings.php");
  
  require_once("Exceptions.php");

  class User 
  {
    
    private $id;
    private $username;  
    private $email;    
    private $isDisabled;
    
    public function __construct($id, $username, $email)
    {
      $this->id = $id;

      if ( $this->checkUsername($username) )
	$this->username = $username;    
      else    
	throw new Exception('Username should be between between 3 and 16 charaters long');
	
      if ( $this->isValidEmail($email) )
	$this->email = $email;
      else
	throw new BadEmailException($email);
	
      $this->isDisabled = false;
    }
    
    public function getId()
    {
      return $this->id;
    }

    public function getUsername()
    {
      return $this->username;
    }
    
    public function setUsername($username)
    {
      if ( $this->checkUsername($username) )
      {
	$this->username = $username;      
      }
      else
	throw new Exception('Username should be between between 3 and 16 charaters long');
    }
    
    public function getEmail()
    {    
      return $this->email;
    }
    
    public function setEmail($email)
    {
      if ( $this->isValidEmail($email) )
      { 
	$this->email = $email;
      }
      else
	throw new BadEmailException($email);
    }
    
    public function isDisabled()
    {
      return $this->isDisabled;
    }
    
    public function enableAccount() 
    {
      $this->isDisabled = false;
    }
    
    public function disableAccount()
    {
      $this->isDisabled = true;
    }
    
    public function isAdmin() {
      return ( $this->id === 0 );	
    }
    
    public function getArray() {
      $array = array(
		'id' => $this->id,
		'username' => $this->username,
		'email' => $this->email,
		'isDisabled' => $this->isDisabled,
      );
      return $array;
    }

    public function getJSON() {
      return json_encode( $this->getArray() );
    }

    private function checkUsername($username)
    {
      if (  ( strlen($username) >= 3 ) || ( strlen($username) <= 16 )  )
      {
	return true;
      } else {
	return false;
      }
    }
    
    private function isValidEmail($email)
    {
      if (filter_var($email, FILTER_VALIDATE_EMAIL)) 
      {
	return true;
      } else {
	return false;
      }
    }
    
  }
?>