<?php
  require_once("global_settings.php");
  
  require_once("BookmarkService.php");
  //require_once("User.php");
?>

<html>
 <body> 
    <h1>Testing Pass By Value</h1>
    <p>
      <?php
	$bs = new BookmarkService();

	$user1 = $bs->getUserByUsername("sunil");
	$user2 = clone $user1;
	print "user1's email:" . $user1->getEmail() . "<br />";
	print "user2's email:" . $user2->getEmail() . "<br />";
	$user1->SetEmail("haha@hotmail.com");
	print "user1's email:" . $user1->getEmail() . "<br />";
	print "user2's email:" . $user2->getEmail() . "<br />";
      ?>
    </p>
    <h1>Testing Bookmarks cloning</h1>
    <p>
      <?php
	$bm1 = $bs->getBookmark("0000000001");
	$bm2 = $bs->getBookmark("0000000003");
	print "bm1's url:" . $bm1->getUrl() . "<br />";
	print "bm2's url:" . $bm2->getUrl() . "<br />";
	print "bm1's username:" . $bm1->getUser()->getUsername() . "<br />";
	print "bm2's username:" . $bm2->getUser()->getUsername() . "<br 
	/>";
	$bm2 = clone $bm1;
	$user = $bm2->getUser();
	$user->setUsername("arnie");
	$user->setEmail("huzzah@aol.com");
	$bm2->setUser($user);
	$bm2->setUrl("http://youtube.com");
	print "bm1's url:" . $bm1->getUrl() . "<br />";
	print "bm2's url:" . $bm2->getUrl() . "<br />";
	print "bm1's username:" . $bm1->getUser()->getUsername() . "<br />";
	print "bm2's username:" . $bm2->getUser()->getUsername() . "<br 
	/>";	
	print "bm1's email:" . $bm1->getUser()->getEmail() . "<br />";
	print "bm2's email:" . $bm2->getUser()->getEmail() . "<br 
	/>";		
      ?>
    </p>
  </body>
</html>
