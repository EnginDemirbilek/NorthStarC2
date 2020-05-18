<?php
function createKey()
{

    $characters = 'abcdefghjklmnopqrstuvwxyz';
    $keyString = '';

    for ($i = 0;$i < 6;$i++)
    {
        $index = rand(0, strlen($characters) - 1);
        $keyString .= $characters[$index];
    }

    return $keyString;

}

function register($conn, $str, $ip)
{
    $clearKey = createKey();
    $regCli = $conn->prepare("INSERT INTO slaves(slaveId, slaveIp, slaveKey,slaveDate) values(?,?,?,CURDATE())");
    if ($regCli !== false)
    {
        $errorControl = $regCli->bind_param("sss", $str, $ip, $clearKey);
        if ($regCli !== false)
        {
            $errorControl = $regCli->execute();
            if ($regCli === error)
            {
                return "An error occured: " . $regCli->error;
            }
            else
            {
                return $clearKey;
            }
        }
        else
        {
            return "An error occured: " . $regCli->error;
        }
    }
    else
    {
        return "An error occured: " . $conn->error;
    }

}

?>
