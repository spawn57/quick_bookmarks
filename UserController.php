<?php
  require_once("global_settings.php");
  
  require_once("RegisterUser.php");
  require_once("User.php");
  require_once("Bookmark.php");
  require_once("BookmarkService.php");
  require_once("Exceptions.php");

  require_once("RestUtils.php");
  require_once("RestRequest.php");

  require_once("XML/Serializer.php");

  $data = RestUtils::processRequest();
  $controller = new UserController();

  switch($data->getMethod())
  {
    case 'get':
      $controller->getMethod($data);
      break;
    case 'post':
      $controller->postMethod($data);
      break;
    case 'delete':
      $controller->deleteMethod($data);
      break;
    case 'put':
      $controller->putMethod($data);
      break;
  }  

  class UserController 
  {
    var $service; 

    public function __construct()
    {
      $this->service = new BookmarkService();
    }

    public function getMethod($data) 
    {
      try {
	$request = $data->getRequestVars();

	// find the action 
	if ( !isset($request["action"]) )
	  throw new ActionException("No action specified");

	switch ($request["action"])
	{
	  case "add-user" : 
	    $this->addUser($data);
	    break;
	  case "is-username-unique" :
	    $this->IsUsernameUnique($data);
	    break;
	}
      }
      catch (ActionException $e)
      {
	RestUtils::sendResponse(417, "you gone and done it now boy", "application/html");
      }
      catch (Exception $e)
      {
	RestUtils::sendResponse(417, "you gone and done it now boy", "application/html");
      }
    }

    private function addUser($data)
    {
      $user = $this->getUser( $data->getRequestVars() );
      if (!$user)
      {      
	RestUtils::sendResponse(417, "User not found", "application/html");
	return;
      }
      
      if ($data->getHttpAccept() == "json")
      {
	RestUtils::sendResponse(200, $user->getJSON(), 'application/json');
      }
      else if ($data->getHttpAccept() == "xml")
      {
	// using the XML_SERIALIZER Pear Package
	$options = array(
	  'indent' => '    ',
	  'addDecl' => false
	); 
    
	$serializer = new XML_Serializer(); 
	$serializer->serialize( $user->getArray() );
	RestUtils::sendResponse(200, $serializer->getSerializedData(), 'application/xml');	
      }
    }

    private function IsUsernameUnique($data) {
      $request = $data->getRequestVars();
      if ( isset($request["username"]) && !empty($username) )
	$result =  $this->service->isUsernameUnique($username);
      else
	$result = false;
      if ($data->getHttpAccept() == "json")
      {
	RestUtils::sendResponse(200, json_encode($result), 'application/json');
      }
      else if ($data->getHttpAccept() == "xml")
      {
	// using the XML_SERIALIZER Pear Package
	$options = array(
	  'indent' => '    ',
	  'addDecl' => false
	); 
    
	$serializer = new XML_Serializer(); 
	$serializer->serialize( $result );
	RestUtils::sendResponse(200, $serializer->getSerializedData(), 'application/xml');	
      }
    }

    public function postMethod($data)
    {
      try {
	
	$registered_user = $this->createUser( $data->getRequestVars() );
	if (!$registered_user)
	{
	  RestUtils::sendResponse(400, "your data sucked", 'application/text');
	}

	$user = $this->service->addUser( $registered_user );
	if (!$user)
	{
	  RestUtils::sendResponse(400, "your data sucked " . var_export($registered_user), 'application/text');
	}

	if ($data->getHttpAccept() == "json")
	{
	  RestUtils::sendResponse(200, $user->getJSON(), 'application/json');
	}
	else if ($data->getHttpAccept() == "xml")
	{
	  $options = array(
	    'indent' => '    ',
	    'addDecl' => false
	  );
	}
	
	$serializer = new XML_Serializer();
	$serializer->serializer( $user->getArray() );
	RestUtils::sendResponse(200, $serializer->getSerializedData(), 'application/xml');
      }
      catch (UserExistsException $e)
      {
	RestUtils::sendResponse(417, $e->getMessage(), "application/html");
      }
      catch (Exception $e)
      {
	RestUtils::sendResponse(417, "you gone and done it now boy", "application/html");
      }
    }

    public function deleteMethod($data)
    {
      try
      {
	$getArray = $data->getRequestVars();
	$id = $getArray["id"];

	if ( $this->isId( $data->getRequestVars() ) )    
	  $this->service->deleteUser($id);
	else 
	  throw new BadIdException($id . " i'm gonna have to stop you there taylor, this bad exception is the best exception in the world ever");

      }
      catch (BadIdException $e)
      {
	RestUtils::sendResponse(417, "Come On... " . $e->getMessage(), "application/html");
      }
      catch (Exception $e)
      {
	RestUtils::sendResponse(417, "you gone and done it now boy", "application/html");
      }
    }

    public function putMethod($data)
    {
      try 
      {
	$getArray = $data->getRequestVars();
	
	$id = $getArray["id"];
	if ( empty($id) && !$this->isId($id) )
	    throw new BadIdException($id);

	$email = $getArray["email"];
	if ( empty($email) && !Checker::checkEmail($email) )
	    throw new BadEmailException("empty email");
	
	$user = $this->service->getUser($id);
	$user->setEmail($email);  
	$this->service->UpdateUser($user);
	
	if ($data->getHttpAccept() == "json")
	{
	  RestUtils::sendResponse(200, $user->getJSON(), 'application/json');
	}
	else if ($data->getHttpAccept() == "xml")
	{
	  // using the XML_SERIALIZER Pear Package
	  $options = array(
	    'indent' => '    ',
	    'addDecl' => false
	  ); 
      
	  $serializer = new XML_Serializer(); 
	  $serializer->serialize( $user->getArray() );
	  RestUtils::sendResponse(200, $serializer->getSerializedData(), 'application/xml');	
	}
      }
      catch (BadIdException $e)
      {
	RestUtils::sendResponse(417, "Come on... " . $e->getMessage(), "application/html");
      }
      catch (BadEmailException $e)
      {
	RestUtils::sendResponse(417, "you gone and done it now boy " . $e->getMessage(), "application/html");
      }
      catch (Exception $e)
      {
	RestUtils::sendResponse(417, "you gone and done it now boy", "application/html");
      }
    }

    private function getUser($getArray)
    {     
      if ( $this->isId($getArray) )
      {
	return $this->getUserById( $getArray["id"] );
      } 
      else if ( isset($getArray["username"]) ) 
      {	
	return $this->getUserByUsername( $getArray["username"] );
      }
    }

    private function createUser($getArray)
    {
      // parse the variables
      if ( isset( $getArray["username"] ) && isset( $getArray["password"] ) && isset( $getArray["email"] ) ) 
      {
	  return new RegisterUser( $getArray["username"],
				      $getArray["password"],
				      $getArray["email"]
				    );
      } else {
	return false;
      }
    }

    private function isId($getArray)
    {
      if ( isset( $getArray["id"] ) && ( (int)($getArray["id"]) ) )
	return true;
      else
	return false;
    }
    
    private function getUserById($userId)
    {
      return $this->service->getUser($userId);
    }

    private function getUserByUserName($username)
    {
      return $this->service->getUserByUsername($username);
    }
  }
      
?>