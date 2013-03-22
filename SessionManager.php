<?php
  require_once("global_settings.php");
  
  require_once("User.php");

  class SessionManager {

    public static function loadUI()
    {
      // forward to the admin interface
      $user = self::getAuthenicatedUser();
      
      if (!$user)
	header("Location: login.php");
      
      
      if ( $user->isAdmin() )
      {
	// forward to admin ui.
	header("Location: admin.php");
      } else {
	// forward to user ui.
	header("Location: index.php");
      }     
    }

    // Save's the user's data in the session
    public static function setAuthenticateUser($user)
    {
      
      
      $_SESSION["authenticated_user"] = serialize($user);
    }

    public static function getAuthenicatedUser()
    {
      if ( isset( $_SESSION["authenticated_user"] ) )
      {
	return unserialize( $_SESSION["authenticated_user"] );
      } else
	return false;
    }
  }
?>
