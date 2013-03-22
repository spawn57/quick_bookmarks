<?php
  require_once("global_settings.php");

  require_once('BookmarkService.php');
  require_once('XML/Serializer.php');

  $service = new BookmarkService();  

  error_reporting(E_ALL);
  ini_set('display_errors', '1');
?>

<html>
  <head>
    <title>Testing bookmarks</title>
  </head>
  <body>
   <h1>Creating a User</h1>
   <p>creating user Sunil: 
      <?php 
	$user = new User(1, "sunil", "spawn57@yahoo.com"); 
	if ($user)
	  print "OK";
	else
	  die("failed to create user");
      ?>
   </p>
   <h1>Trying out the bookmark class</h1>
    <p>creating new bookmark:
    <?php
      $bookmark = new Bookmark(1, $user, "yahoo", "http://yahoo.com");
      if ($bookmark)
	print "OK";
      else
	die("failed to create bookmark");
    ?>
    <p>Bookmark for <?php print $bookmark->getUser()->getUsername() ?></p>
    <p>Bookmark's url <?php print $bookmark->getUrl() ?></p>
    <br />
    <h1>Getting a bookmark from the database</h1>
    <p>Starting the bookmark service 
    <?php
      $service = new BookmarkService();
      if ($service)
      { 
	print "OK";
      } else {
	print "fail: ";
	die("failed to start bookmark service");
      }
    ?>
    </p>
    <p>Getting bookmark with id 1: 
    <?php 
      $bookmark = $service->getBookmark(1);
      if ($bookmark)
	print $bookmark->url;
      else
	print "FAILED";      
    ?>
    </p>
    <p>Getting bookmarks owned by sunil: getting user sunil :
    <?php
      $user = $service->getUser(1);
      if ($user)
	print "OK: " . $user->getUsername();
      else
	print "FAILED";      
    ?></p>
    <p>Getting bookmarks:</p>    
    <?php
      $bookmarks = $service->getBookmarks($user->getId());
      
      if (!$bookmarks)
      {
	 print "No bookmarks found";
      } else {
	print "<ul>";
	for ($i=0; $i < $bookmarks->count(); $i++)
	{
	  print "<li>" . $bookmarks[$i]->getName() . ": " . $bookmarks[$i]->getUrl() . "</li>";
	}
	print "</ul>";
      }
    ?>    
    <h1>Encoding to JSON</h1>  
    <p>Encoding User: 
      <?php 	
	$test = $user->getJSON(); 
	if ($test)
	  print $test;
	else
	  print "Fucked!";
      ?>
    </p>
    <h1>Encoding to XML</h1>
    <p>Encoding User:
      <?php
	$serializer = new XML_Serializer();
	$result = $serializer->serialize($user->getArray());
	if ($result)
	{
	  print "OK\n";
	  echo '<pre>';
	  echo $serializer->getSerializedData();
	  echo '</pre>';
	} else {
	  print "Failed";
	}
      ?>
    </p>
  </body>
</html>