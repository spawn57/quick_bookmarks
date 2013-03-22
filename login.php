<?php
  require_once("global_settings.php");
  require_once("User.php");
  require_once("SessionManager.php");
  
  session_start();
  $authentication_failed = false;

  if ( isset( $_POST["submitted"] ) && ( $_POST["submitted"] = "true" ) )
  {
    
    $username = $_POST["username"];
    $password = $_POST["password"];
        
    require_once("BookmarkService.php");
    require_once("User.php");
    
    $service = new BookmarkService(); 
    if (!$service)
    {
      die("Uh Oh, something isn't working... forwarding to fail whale");
    }
    
    $authenticated_user = $service->authenticateUser($username, $password);
    
    if ( $authenticated_user ) {
      SessionManager::setAuthenticateUser($authenticated_user);            
      SessionManager::loadUI();
    } else {
      global $authentication_failed;
      $authentication_failed = true;
    }   
  }  
 
?>

<html>
  <head>    
    <title>Login</title>    
    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">       
    <!-- Bootstrap JS -->
    <script src="jquery-1.9.1.min.js"></script>    
    <script src="js/bootstrap.min.js"></script>
    <script>    
      $(document).ready( function(){
      
	// enable bootstrap alerts
	$(".alert").alert();
      });
    </script>
        <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
                border-radius: 5px;
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }

    </style>
  </head>
  <body>          
    <div class="container">
      <div class="form-signin">	
	<form name="login" method="post" action="login.php">
	  <h2 class="form-signin-heading">Please sign in</h2>
	  <input type="hidden" name="submitted" value="true" />
	  <input type="text" class="input-block-level" placeholder="Username" name="username"/><span class=".avialable"></span>
	  <input type="password" class="input-block-level" placeholder="Password" name="password" /></p>
	  <button class="btn btn-large btn-primary" type="submit">Sign in</button>
	</form>
	<?php if ($authentication_failed) : ?>	
	  <div class="alert">
	    <button type="button" class="close" data-dismiss="alert">&times;</button>
	    Invalid Credentials.
	  </div>
	<?php endif; ?>
      </div><!-- .sign-in -->
    </div><!-- #container -->
  </body>
</html>