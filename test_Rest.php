<?php
  require_once("global_settings.php");

  // understanding query strings 
  $get = $_GET;
  $post = $_POST;
?>
<html>
  <head>
    <title>Testing REST</title>
    <script src="jquery-1.9.1.min.js"></script>
    <script>

      new_user = "";

      function getDataJSON() {
	$.ajax({
	  type : "GET",
	  dataType : "json",
	  url  : "UserController.php",
	  data : { 
		    action : "add-user",
		    id : "1",
		    vars : "wtf",
		  },
	  success: getUserJSONSuccess
	});
      }

      function getUserJSONSuccess(user) {
	$(".test-get").append("Using JSON: user.username = " + user.username + "<br />");
	getDataXML();
      }

      function getDataXML() {
	$.ajax({
	  type : "GET",
	  url : "UserController.php",
	  data : { 
		    action : "add-user",
		    username : "sunil" 
		 },
	  dataType : "xml",
	  success: getUserXMLSuccess
	});
      }

      function getUserXMLSuccess(user) {
	var id = user.getElementsByTagName("username")[0].childNodes[0].nodeValue;
	$(".test-get").append("Using XML: user.id = " + id + "<br />");
	getIsUsernameTaken();
      }

      function getIsUsernameTaken()
      {
	$.ajax({
	  type : "GET",
	  dataType : "json",
	  url  : "UserController.php",
	  data : { 
		    action : "is-username-unique",
		    username : "sunijl",
		    vars : "wtf",
		  },
	  success: getIsUsernameTakenJSONSuccess()
	});
      }

      function getIsUsernameTakenJSONSuccess(data)
      {
	if (data == "true")
	  $(".test-username-unique").append("This Username is avialable");
	else
	  $(".test-username-unique").append("This Username is taken");
	postDataJSON();
      }

      function postDataJSON() {
	$.ajax({
	  type : "POST",
	  dataType : "json",
	  url  : "UserController.php",
	  data : { 
		    username : "kokobware",
		    password : "password",
		    email    : "spawn57@yahoo.com"
		  },
	  success: postUserJSONSuccess
	});
      }

      function postUserJSONSuccess(user) {
	new_user = user
	s = "Using JSON: <br />" +
	    "user.id = " + user.id + "<br />" +
	    "user.username = " + user.username + "<br />" +
	    "user.email = " + user.email + "<br />";
	$(".test-post").append(s);	    

	updateUser();
      }

      function updateUser() {
	$.ajax({
	  type : "PUT",
	  dataType : "json", 
	  url  : "UserController.php",
	  data : {
		    id : new_user.id,
		    email : "spawnie@yahoo.com"
		 },
	  success : updateUserSuccess,
	  error : updateUserFail
	});
      }

      function updateUserSuccess(user) {
	msg = "user id: " + user.id + "<br />";
	msg = msg + " user name : " + user.username + "<br />";
	msg = msg + " email : " + user.email + "<br />";
	$(".test-put").append(msg);
	deleteUser();
      }

      function updateUserFail() {
	$(".test-put").append("update failed");
	deleteUser();
      }

      function deleteUser() {
	$.ajax({
	  type : "DELETE",
	  url : "UserController.php",
	  data : {
		    id : new_user.id,
		  },
	  success: deleteUserSuccess,
	  fail : deleteUserFail,
	  error : deleteUserFail
	});
      }

      function deleteUserSuccess(user) {
	$(".test-delete").append("OK");
      }

      function deleteUserFail(user) {
	$(".test-delete").append("Failed to delete " + new_user.id);
      }

      $(document).ready(function() {
	getDataJSON();
      });


    </script>
  </head>
  <body>
    <h1>Request Method</h1>
    <p>Request Method : <?php print $_SERVER['REQUEST_METHOD']; ?></p>
    <h1>This is a GET request</h1>
    <p><?php var_dump($get); ?></p>
    <h1>This is a POST request</h1>
    </p><?php var_dump($post); ?></p>

    <h1>Is there an id in the GET array</h1>
    <p>Has ID:
    <?php	
	if ( isset($_GET["id"]) )
	{
	  print "yes: " . print $_GET["id"];
	} else {
	  print "no"; 
	}
    ?>
    </p>

    <h1>Test if get is an integer</h1>
    <p>
    <?php      
      if ( isset($get["id"]) && is_int( (int) $get["id"]) )  
      {
	print "id provided and it's an integer:" . $get["id"];
      } else {
	print " it's not an integer, or no id provided";
      }
    ?>
    </p>
    <h1>Testing Get Method: Getting user "sunil"</h1>
    <p class="test-get"></p>
    <h1>Testing if Username is "sunil" is Unique</h1>
    <p class="test-username-unique"></p>
    <h1>Testing Post Method: Adding user kokobware</h1>
    <p class="test-post"></p>
    <h1>Testing Put Method: Changing user's email to spawnie@yahoo.com</h1>
    <p class="test-put"></p>
    <h1>Testing Post Method: Deleting user kokobware</h1>
    <p class="test-delete"></p>
  </body>
</html>