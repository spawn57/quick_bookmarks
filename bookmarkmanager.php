<?php
  require_once("global_settings.php");

  require_once("BookmarkService.php");
  require_once("lock.php");
?>
<html>
  <head>
    <title>Bookmark Manager</title>
  </head>
  <body>
    <h1>Add a new bookmark</h1>
    <?php
      
    ?>
    <p>This is where you add a new bookmark</p>
    <h1>View all Bookmarks</h1>
    <?php
      $service = new BookmarkService();
      $userId = $_SESSION["authenticated_user"]->getId();
      $bookmarks = $service->getBookmarks($userId);
      
      $iterator = $bookmarks->getIterator();
      print("<table>\n");
      while( $iterator->valid() ) 
      {
	print("<tr>\n");
	$bookmark = $iterator->current();
	print("<td>" . $bookmark->getName() . "</td>\n");
	print("<td>" . $bookmark->getUrl() . "</td>\n");
	print("</tr>\n");

	$iterator->next();
      }
      print("</table>\n");
    ?>
  </body>
</html>