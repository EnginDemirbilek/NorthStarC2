<?php

function updateSessionDetails($conn, $pid, $opsys, $sus, $mName, $wdir, $isAdmin, $str, $ip)
{
    $registerSlave = $conn->prepare("Update slaves set slaveProcessId=?, slaveOperatingSystem=?, slaveUser=?, slaveMachineName=?, slaveWorkingDir=?, slaveIsAdmin=?, regCompleted='true' where slaveId=?");
      if($registerSlave !== false)
        {
         $errorControl = $registerSlave->bind_param("issssss", $pid, $opsys, $sus, $mName, $wdir, $isAdmin, $str);
          if($errorControl !== false)
            {
              $errorControl = $registerSlave->execute();
                if($errorControl === false)
                  {
                    return "An error occured: " . $registerSlave->error;
                  }
                  else{

                        return "completed";
                  }
            }
            else{
                return "An error occured: " . $registerSlave->error;
            }
        }
        else{
            return "An error occured: " .  $conn->error;
        }

}

?>
