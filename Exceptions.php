<?php
  require_once("global_settings.php");

  class UserExistsException extends Exception
  {

    public function __construct(RegisterUser $user)
    {
      $message = "User " . $user->getUsername() . " already exists";
      parent::__construct($message, 1);      
    }

  }

  class BadIdException extends Exception
  {
    
    public function __construct($id)
    {
      $message = $id . " isn't a valid id.  Ha Ha you lose!";
      parent::__construct($message, 2);
    }

  }

  class BadEmailException extends Exception
  {

    public function __construct($email)
    {
      $message = $email . " isn't a valid email";
      parent::__construct($email, 3);
    }
  }

  class ActionException extends Exception
  {
    public function __construct($message)
    {
      $message = "REST API ERROR : " . $message;
      parent::__construct($message, 4);
    }
  }
?>