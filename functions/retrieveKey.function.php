<?php

function retrieveXorKey($conn, $str)
{
$retrieveKey = $conn->prepare("SELECT slaveKey from slaves where slaveId=?");
  if($retrieveKey !== false)
    {
      $errorControl = $retrieveKey->bind_param("s", $str);
        if($errorControl !== false)
          {
            $errorControl = $retrieveKey->execute();
                if($errorControl !== false)
                  {
                    $retrieveKey->bind_result($key);
                    $retrieveKey->store_result();
                    $retrieveKey->fetch();
                     return $key;
                  }
                else{
                  return "An error occured:" . $retrieveKey-error;
                }
          }
          else{
          return "An error occured:" . $retrieveKey-error;
          }
    }
    else{
    return "An error occured:" . $conn-error;
    }

}
?>
