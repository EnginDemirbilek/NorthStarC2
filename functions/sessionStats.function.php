<?php

function getSessionStats($name, $conn){

  $getSessionStats = $conn->prepare("SELECT slaveIsAdmin,slaveIp,slaveMachineName,slaveOperatingSystem,slaveUser,slaveProcessId,slaveLatestImagePath,slaveWorkingDir from slaves where slaveId=?");

    if( $getSessionStats !== false)
    {
      $errorControl = $getSessionStats->bind_param("s", $name);

        if($errorControl !== false)
        {
            $errorControl = $getSessionStats->execute();
              if($errorControl !== false)
              {
                $getSessionStats->bind_result($isAdmin, $ipAddress, $hostName,$operatingSystem,$userName, $processId, $imagePath, $workingDir);
                $getSessionStats->store_result();
                $getSessionStats->fetch();
                return array($isAdmin, $ipAddress, $hostName, $operatingSystem, $userName, $processId, $imagePath, $workingDir);
              }
              else{
                return "An error occured: ". $errorControl->error;
              }
        }
        else{
            return "An error occured: ". $errorControl->error;
        }
    }
    else{
        return "An error occured: ". $getSessionStats->error;
    }
}
 ?>
