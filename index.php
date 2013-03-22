<?php  
  require_once("global_settings.php");
  require_once("lock.php");
  
  require_once("User.php");
  require_once("BookmarkService.php");
    
  // keep the session alive 
  session_start();
  
  // get the authenticated user
  $user = unserialize( $_SESSION["authenticated_user"] );
?>
<html>
  <head>
    <title>php_bookmarks</title>
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
  </head>
  <body>
    <h1>Welcome to PHP Bookmarks.</h1>
    <p>Choose what you want to do</p>
    <p><a href=# >Create a bookmark</a></p>
    <h1>View all Bookmarks</h1>
    <div id="list-bookmarks">
      <ul>
      <?php
	$service = new BookmarkService();	
        $bookmarks = $service->getBookmarks( $user->getId() );        
        $iterator = $bookmarks->getIterator();
	while( $iterator->valid() )	
        {	  
	  $bookmark = $iterator->current();
	  print "<li>" . $bookmark->getName() . " " . $bookmark->getUrl() . "</li>";
	  $iterator->next();
        } 
      ?>      
      </ul>
    </div>    
    <p><a href="logout.php">Logout</a></p>
  </body>
</html>