<?php
  require_once("global_settings.php");
  
  session_start();
  if ( !isset($_SESSION["authenticated_user"]) )
  {
    // forward them to the main.php
    header("Location: login.php");
    exit();
  }
?>