<?php
include 'conn.php';
include 'chcksid.php';
include 'functions/registerSession.function.php';
include 'functions/updateLogs.function.php';
include 'ipAndUserAgent.php';


$sessionXorKey = register($conn, $str, $ip);
  if(!strpos($sessionXorKey, 'error'))
   {
     $encKey =  encrypt($sessionXorKey, "northstar");
        echo $encKey;
      updateLogs($conn, "First stage","First stage OK", $agent,$str,$ip);
    }
    else{
      updateLogs($conn, "Error","Er: first stage", $agent,$str,$ip);
    }


?>
