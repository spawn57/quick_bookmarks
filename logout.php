<?php
  require_once("global_settings.php");
  
  session_start();
  $sid = session_id();
  if ($sid)
  {
    session_destroy();
  }
?>
<html>
  <head>
    <title>php_bookmarks</title>
  </head>
  <body>
    <h1>You have been logged out</h1>
    <p>come back soon</p>
    <p><a href="login.php">Login again?</a></p>
  </body>
</html>