<?php
function updateLogs($conn, $logType, $logContent, $logUserAgent, $logClient, $logIP)
{

    $updateLog = $conn->prepare("INSERT INTO logs(logDate, logClient, logType, logIP, logUserAgent, logContent) values(NOW(),?,?,?,?,?)");
    if ($updateLog !== false)
    {
        $errorControl = $updateLog->bind_param("sssss", $logClient, $logType, $logIP, $logUserAgent, $logContent);
        if ($errorControl !== false)
        {
            $errorControl = $updateLog->execute();
            if ($errorControl === false)
            {
                return "An error occured: " . $updateLog->error;
            }
        }
        else
        {
            return "An error occured: " . $updateLog->error;
        }

    }
    else
    {
        return "An error occured: " . $conn->error;
    }

}

?>
