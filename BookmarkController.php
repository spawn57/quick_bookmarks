<?php
  require_once("global_settings.php");

  require_once("BookmarkService.php");
  require_once("RestController.php");


  $data = RestUtils::processRequest();
  $service = new BookmarkService();

  switch($data->getMethod())
  {
    case 'get':
      // retrieve a list of users
      $user = $service->getUser("1");      
      if ($data->getHttpAccept() == "json")
      {
	RestUtils::sendResponse(200, $user->getJSON(), 'application/json');
      }
      else if ($data->getHttpAccept() == "xml")
      {
	// using the XML_SERIALIZER Pear Package
	$options = array
	(
	  'indent' => '    ',
	  'addDecl' => false,
	  //'rootName' => $fc->getAction(), XML_SERIALIZER_OPTION_RETURN_RESULT => true
	); 
	$serializer = new XML_Serializer(); 
	$serializer->serialize( $user->getArray() );
	RestUtils::sendResponse(200, $serializer->getSerializedData(), 'application/xml');	
      }
      break;
   }
      
      
  class BookmarkController extends RestController 
  {
  
    private $service;
    
    public function __construct()
    {
      $this->serivce = new BookmarkService();
    }
    

  }
?>