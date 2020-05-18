<?php
function isInCheck($conn, $machineName, $ip){

  $checkCli = $conn->prepare("SELECT slaveId, slaveProcessId,slaveIsAdmin from slaves where slaveMachineName = ? AND slaveIp = ?");
    if($checkCli !== false)
      {
        $errorControl = $checkCli->bind_param("ss", $machineName, $ip);
          if($errorControl !== false)
            {
                $errorControl = $checkCli->execute();
                  if($errorControl === false)
                      {
                        return "An error occured: " . $checkCli->error;
                      }
                    else{
                      $checkCli->execute();
                      $checkCli->bind_result($slaveId, $slaveProcess, $slaveIsAdmin);
                      $checkCli->store_result();
                      $rowCount = $checkCli->num_rows;
                      if($rowCount < 1)
                        {
                          return "new";
                        }
                      else{
                        $checkCli->fetch();
                        $clearResponse =  $slaveId. " ". $slaveProcess. " ". $slaveIsAdmin;
                        return $clearReponse;
                      }
                    }
            }
      }
}
?>

