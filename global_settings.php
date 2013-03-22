<?php 
  // this file is included in every single php file in this program  
  require_once("config.php");  
  
  if (DEBUG)
  {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
  }
  
  function trace($message)
  {
    global $TRACECOUNT;
    
    if (DEBUG)
    {
      echo '<hr />' . $TRACECOUNT++ . '<code>' . $message . '</code><hr />';
    }
  }
  
  function tarr($arr)
  {
    global $TRACECOUNT; 
    if(DEBUG) 
    { 
        echo '<hr />'.$TRACECOUNT++.'<code>'; 
        print_r($arr); 
        echo '</code><hr />'; 
    } 
  } 
?>
