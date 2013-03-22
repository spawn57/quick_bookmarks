<?php
require_once("global_settings.php");
require_once('config.php');

require_once('Bookmark.php');
require_once('User.php');
require_once('RegisterUser.php');
require_once('Exceptions.php');

class BookmarkService 
{

  private $db;
  private $connected;

  public function __construct()
  {    
    $this->db = $this->connect();
  }

  /* User Methods */
  public function addUser(RegisterUser $register_user)
  {    
    // check for unique username 
    $sql = "SELECT COUNT(`id`) " 
	 . "FROM `users` "
	 . "WHERE `username` = ? ";
    
    $statement = $this->db->prepare($sql);
    if ($statement)
    {
      $statement->bind_param('s', $register_user->username);
      $statement->execute();
      $statement->bind_result($q_count);

      if ( ( $statement->fetch() ) && ($q_count == 1) )
	throw new userExistsException($register_user);
    }  
    
    $statement->close();

    // add new user
    $sql = "INSERT INTO `users` (`id`, `username`, `email`, `password`) "
	 . "VALUES (NULL, ?, ?, ?);";
    
    $statement = $this->db->prepare($sql);
    if ($statement)
    {
      $statement->bind_param('sss', 
			     $register_user->getUsername(), 
			     $register_user->getEmail(), 
			     $register_user->getPassword() 
      );
      $statement->execute();
    }

    $inserted_id = $statement->insert_id;
    $user = new User(
		      $inserted_id, 
		      $register_user->getUsername(),
		      $register_user->getEmail()
		   );
    $statement->close();    

    return $user;
  }

  public function getUser($id)
  {    
    $statement = $this->db->prepare("SELECT `id`, `username`, `email`
			      FROM `users`
			      WHERE `id` = ?");

    if ($statement)
    {     
      $statement->bind_param('i', $id);
      $statement->execute();
	    
      $statement->bind_result($q_id, $q_username, $q_email);

      /* there should only be one result */
      if ( $statement->fetch() )  
      {
	$statement->close();      
	$user = new User($q_id, $q_username, $q_email);	
	return $user;
      }
    }    
    
    // user doesn't exist
    $statement->close();
    return false;
  }

  public function getUserByUsername($username)
  {
    $username = strtolower($username);    
    
    $statement = $this->db->prepare("SELECT `id`, `username`, `email`
			      FROM `users`
			      WHERE `username` = ?");

    if ($statement)
    {     
      $statement->bind_param('s', $username);
      $statement->execute();
	    
      $statement->bind_result($q_id, $q_username, $q_email);

      /* there should only be one result */
      if ( $statement->fetch() )  
      {
	$statement->close();      
	$user = new User($q_id, $q_username, $q_email);	
	return $user;
      }
    }    
    
    // user doesn't exist
    $statement->close();
    return false;
  }

  public function isUsernameUnique($username)
  {
    $sql = "SELECT COUNT(`id`) " 
	 . "FROM `users` "
	 . "WHERE `username` = ? ";
    
    $statement = $this->db->prepare($sql);
    if ($statement)
    {
      $statement->bind_param('s', $username);
      $statement->execute();
      $statement->bind_result($q_count);

      if ( $statement->fetch() )
      {
	if ( $q_count == 1 )
	{
	    $result = false;
	} else {
	    $result = true;
	}
      }
      
      $statement->close();
      return $result;
    }
  }

  public function authenticateUser($username, $password)
  {
    // username should be case insensitive, no matter what locale
    $username = mb_strtolower($username);
    
    $sql = "SELECT `id`, `username`, `email`"
         . " FROM `users`"
         . " WHERE `username` = ? AND `password` = ? AND `isDisabled` = 0";

    $statement = $this->db->prepare($sql);
    $statement->bind_param("ss", $username, $password);
    $statement->execute();

    $statement->bind_result($q_id, $q_username, $q_email);
    
    /* there should only be one result */
    if ( $statement->fetch() )
    {
	$statement->close();      
	$user = new User($q_id, $q_username, $q_email);	
	return $user;
    }

    // authentication failure
    $statement->close();
    return false;
    
  }

  public function disableUser(User $user)
  {
    $sql = "UPDATE `users` "
	 . "SET `isDisabled` = 1 "
	 . "WHERE id = ? ";

    $statement = $this->db->prepare($sql);
    $statement->bind_param("i", $user->getId() );
    $statement->execute();
    $statement->close();  
  }

  public function enableUser(User $user)
  {
    $sql = "UPDATE `users` "
	 . "SET `isDisabled` = 0 "
	 . "WHERE `id` = ? ";

    $statement = $this->db->prepare($sql);
    $statement->bind_param("i", $user->getId() );
    $statement->execute();
    $statement->close();  
  }

  public function updateUser(User $user)
  {
    $sql = "UPDATE `users` "
	 . "SET `email` = ? "
	 . "WHERE `id` = ?";

    $statement = $this->db->prepare($sql);
    $statement->bind_param("si", $user->getEmail(), $user->getId() );
    $statement->execute();
    $statement->close();
  }


  public function deleteUser($id)
  {
    // check if Id is a number
    if ( !is_numeric($id) )
      throw new BadIdException($id);
    
    $sql = "DELETE FROM `users` "
	 . "WHERE id = ? ";

    $statement = $this->db->prepare($sql);
    $statement->bind_param("i", $id );
    $statement->execute();
    $statement->close();  
  }

  /* Bookmark Methods */
  public function getBookmark($id)
  {
    $sql = "SELECT bookmarks.id, users.id, users.username, users.email, users.isDisabled, bookmarks.name, bookmarks.url \n"
    . "FROM users\n"
    . "LEFT JOIN bookmarks\n"
    . "ON users.id = bookmarks.userid\n"
    . "WHERE bookmarks.id = ? LIMIT 0, 30 ";

    $statement = $this->db->prepare($sql);
    $statement->bind_param("i", $id);
    $statement->execute();

    $statement->bind_result($q_id, $q_userid, $q_username, $q_email, $q_isDisabled, $q_bookmark_name, $q_url );

    // there should only be 1 result
    if ( $statement->fetch() )
    {
	$statement->close();      
	$user = new User($q_userid, $q_username, $q_email);
	$bookmark = new Bookmark($q_id, $user, $q_bookmark_name, $q_url);
	return $bookmark;
    }

    // nothing found 
    $statement->close();
    return false;
  }

  public function getBookmarks($userid)
  {
    $sql = "SELECT bookmarks.id, users.id, users.username, users.email, users.isDisabled, bookmarks.name, bookmarks.url \n"
         . "FROM users\n"
         . "LEFT JOIN bookmarks\n"
         . "ON users.id = bookmarks.userid\n"
         . "WHERE users.id = ? LIMIT 0, 30 ";

    $statement = $this->db->prepare($sql);
    $statement->bind_param("i", $userid);
    $statement->execute();

    $statement->bind_result($q_id, $q_userid, $q_username, $q_email, $q_isDisabled, $q_bookmark_name, $q_url );

    // there should only be 1 result
    $bookmarks = new ArrayObject();
    while ( $statement->fetch() )
    {	
	$user = new User($q_userid, $q_username, $q_email);
	$bookmark = new Bookmark($q_id, $user, $q_bookmark_name, $q_url);	
	$bookmarks->append($bookmark);
    }

    $statement->close();    
    if ($bookmarks->count() > 0)      
      return $bookmarks;
    // no results found
    return false;
  }

  public function addBookmark($userid, $name, $url)
  {
    $sql = "INSERT INTO `bookmarks` (`id`, `userid`, `name`, `url`) "
	 . "VALUES (NULL, ?, ?, ?);";

    $statement = $this->db->prepare($sql);
    $statement->bind_param("isss", $userid, $name, $url );
    $statement->execute();
    $statement->close(); 
  }


  /* Misc */
  private function connect() 
  {
    $db = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE_NAME);
    if ($db->connect_errno > 0) 
    {
      throw new Exception('Could not connect to database server [' . $db->connect_error . ']');
    } else {
      return $db;
    }
  }  
  
  private function validate_password($password)
  {
    // too short
    if ( strlen($password) < 6 )
    {
      return false;
    }

    return true;
  }
}

?>