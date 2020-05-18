<?php
include 'conn.php';
include 'ipAndUserAgent.php';
include 'functions/isinCheck.function.php';
include 'functions/chiper.function.php';
include 'functions/updateLogs.function.php';

if(isset($_GET["mName"]) && isset($_GET["chck"]))
{
    $machine = decrypt($_GET["mName"], "northstar");

    $isInQuery = isIncheck($conn, $machine, $ip);
  if(strpos($isInQuery,'error'))
    {

    }
    elseif($isInQuery == "new") {
      http_response_code(404);
      echo "<html><title>Not Found</title><h1>PAGE NOT FOUND</h1><html>";
          }
      else{
        $throwInfo = encrypt($isInQuery, "northstar");
        echo  $throwInfo;
      }
}
else {
//Use header function to send a 404
http_response_code(404);
echo "<html><title>Not Found</title><h1>PAGE NOT FOUND</h1><html>";
//End the script
}


?>

