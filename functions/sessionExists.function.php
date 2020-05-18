<?php
function sessionExists($name, $conn)
{
    $session = $conn->prepare("SELECT id from slaves where slaveId=?");

    if ($session !== false)
    {
        $errorControl = $session->bind_param("s", $name);
        if ($errorControl !== false)
        {
            $errorControl = $session->execute();
            if ($errorControl !== false)
            {
                $session->store_result();
                if ($session->num_rows > 0) return "Session exists";
                else return "Not found";
            }
            else
            {
                return "An error occured: " . $session->error;
            }
        }
        else
        {
            return "An error occured: " . $session->error;
        }
    }
    else
    {
        return "An error occured: " . $session->error;
    }

   }
?>
