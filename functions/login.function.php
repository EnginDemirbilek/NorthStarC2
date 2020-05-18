<?php
include 'updateLogs.function.php';

function login($user, $pass, $conn, $agent, $ip)
{
    $saltedPassword = "NorthyBoi" . $pass . "NorthyBoi";
    $hashedPassword = MD5($saltedPassword);
    $login = $conn->prepare("select * from users where username=? and password=?");
    if ($login !== false)
    {
        $errorControl = $login->bind_param("ss", $user, $hashedPassword);
        if ($errorControl !== false)
        {
            $errorControl = $login->execute();
            if ($errorControl !== false)
            {
                $login->store_result();
                      if ($login->num_rows > 0)
                         {
                            updateLogs($conn, "login", "web login",$agent, $user, $ip);
                            return "OK";
                          }
                    else
                    {
                     return "Login Failed";
                    }
            }
            else
            {
                return "An error occured: " . $login->error;
            }

        }
        else
        {
            return "An error occured: " . $login->error;
        }
    }
    else
    {
        return "An error occured: " . $conn->error;
    }

}
?>
