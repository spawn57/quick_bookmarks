<?php
  //require_once("global_settings.php");
  
  require_once("Bookmark.php");
  require_once("User.php");
  require_once("BookmarkService.php");
  require_once("XML/Serializer.php");

  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  
?>
<html>
  <head>
    <title>trying this out</title>
  </head>
  <body>
    <h1>Trying out User Class</h1>
    <?php
      $user = new User(1, "Sunil","spawn57@yahoo.com");
    ?>
    <p>User : <?php print $user->getUsername() ?></p>
    <p>Email : <?php print $user->getEmail() ?></p>
    <p>Disabled : <?php print $user->IsDisabled() ?></p>
    <?php $user->disableAccount() ?>
    <p>Disabling account : ... </p>
    <p>Disabled : <?php print $user->IsDisabled() ?></p>
    <?php $user->enableAccount() ?>
    <p>Enabling account : ... </p>
    <p>Disabled : <?php print $user->IsDisabled() ?></p>    
    <br />
    <p>Initialize the bookmark service : 
    <?php 
      $bs = new BookmarkService();
      if ($bs)
      {
	print 'OK';
      } else {
        print 'Failed';
      }    
    ?>
    </p>
    <br />    
    <p>Getting a user record : 
      <?php
        $user = $bs->getUser(1);
        if (!$user)
        {
          print 'Error: something went wrong';
        } else {
          print 'OK, User found : ' . $user->getUsername();
        }
      ?>
    </p>
    <p>Authenticating user Sunil :
      <?php
        $user = $bs->authenticateUser("SunIL","password");
	if (!$user)
	{
	   print 'Error: authentication failed';
	} else {
	 print 'OK, User authenticated : ' . $user->getUsername();
	}
      ?>     
    <p>Checking for XML Serializer : 
      <? 
	$user = $bs->getUserByUsername("sunil");	
	$serializer = new XML_Serializer();
	$array = $user->getArray();
	$result = $serializer->serialize($array);
	// check result code and display XML if success	
	if ($result === true)
	{
	  print $serializer->getSerializedData();
	}
      ?>
    </p>
    <p>Generating New User KQQLQkk :
      <?php
	$username = "KQQLQkk";
	if ( $user = $bs->addUser(new RegisterUser($username, "password", "hi@hotmail.com")) )
	  print "OK " . $user->getUsername() . " added";
	else
	  print "Not Ok";
      ?>
    </p>
    <p>Checking Username KQQLQkk is unique : 
      <?php		
	if ( $bs->isUsernameUnique( $username ) )
	  print "OK";
	else
	  print "Taken";
      ?>
    </p>
    <p>Dislabing User KQQLQkk :
      <?php
	$bs->disableUser( $user );
	print "Should be Disabled";
      ?>
    </p>
    <p>Dislabing User KQQLQkk :
      <?php
	$bs->enableUser( $user );
	print "Should be Enabled";
      ?>
    </p>
    <p>Updating User KQQLQkk:
      <?php
	$user->setEmail("spawnie@yahoo.com");	
	$bs->updateUser($user);	
	var_dump($user);
	print "Should have changed";
      ?>
    </p>
    <p>Deleting User KQQLQkk:
      <?php
	$user = $bs->getUserByUsername($username);
	$bs->deleteUser( $user->getId() );	
	print "Should Be Deleted Now";
      ?>
    </p>
    <p>Checking Username KQQLQkk again : 
      <?php		
	if ( $bs->isUsernameUnique( $username ) )
	  print "OK";
	else
	  print "Taken";
	print "<br />";
	if ( !(int)("57"))
	  print "NOT INT";
	else 
	  print "INT";
      ?>
    </p>
    <h1>Test Serialize</h1>
    <p>Serializing the user object:
      <?php
	$data = serialize($user);
	$user = unserialize($data);
	var_dump($user);
      ?>      
    </p>
  </body>
</html>