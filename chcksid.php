<?php
include 'conn.php';
include 'functions/chiper.function.php';
//check if request is valid request
if(isset($_GET["sid"]) || isset($_POST["sid"])){

  $str;
  if(isset($_POST["sid"])){
  $str = decrypt($_POST["sid"], "northstar");
	}
		
  else{
    $str = decrypt($_GET["sid"], "northstar");
	
}
  // Check if sid is a valid client sid.
  if ($str[0] === 'N' && $str[strlen($str)-1] === 'q' && strlen($str) < 20) {
	
  }
  else{

  http_response_code(404);
 echo "<html><title>Not Found</title><h1>PAGE NOT FOUND</h1><html>"; 	

exit;
  }

}
else{
  //Use header function to send a 404
 http_response_code(404);
echo "<html><title>Not Found</title><h1>PAGE NOT FOUND</h1><html>";
//End the script
exit;

}
  ?>
