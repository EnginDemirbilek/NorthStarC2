<?php
include 'session.php';
include 'conn.php';

/*
<explanation>
This file used for retrieving results of commands at interact page.
Take slaveID as GET request and send corresponding query to database and return response.
</explanation>
*/

if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["slave"]))
{
$retrieveResponse = $conn->prepare("SELECT slaveResponse,slaveLatestImagePath from slaves where slaveId=?");
  if($retrieveResponse !==false)
    {
        $errorControl = $retrieveResponse->bind_param("s", $_GET["slave"]);
          if($errorControl !== false)
              {
                $errorControl = $retrieveResponse->execute();
                  if($errorControl !== false)
                    {
                      $retrieveResponse->store_result();
                      $retrieveResponse->bind_result($response, $imagePath);
                      $retrieveResponse->fetch();
                    }
                  else{
                    echo "Error occured: " . $errorControl->error;
                  }
              }
              else {
                echo "Error occured: " . $errorControl->error;
              }
    }
    else{
      echo "Error occured: " . $retrieveResponse->error;
    }

/*$row = $res->fetch_array();
$response = $row["slaveResponse"];
*/
  if(strlen($response) > 1 )
  {
  echo htmlspecialchars($response);
  $setResponse = $conn->prepare("update slaves set slaveCommand ='', slaveResponse='' where slaveId=?");
    if($setResponse !==false)
      {
        $errorControl = $setResponse->bind_param("s",$_GET["slave"]);
          if($errorControl !== false)
              {
                $erorControl = $setResponse->execute();
                  if($errorControl ===false)
                    {
                      echo $errorControl->error;
                    }
              }
              else{
                echo $errorControl->error;
              }
      }
      else{
        echo $setResponse->error;
      }

  }

}
?>
