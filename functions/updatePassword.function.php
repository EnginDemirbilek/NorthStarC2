<?php
function updatePassword($username, $oldPassword, $newPassword, $conn)
{

    $addNewPassword = $conn->prepare("UPDATE users set password=? where username=? and password=?");
    if ($addNewPassword !== false)
    {
        $errorControl = $addNewPassword->bind_param("sss", $newPassword, $username, $oldPassword);
        if ($errorControl !== false)
        {
            $errorControl = $addNewPassword->execute();
            if ($errorControl !== false)
            {
                $addNewPassword->store_result();
                if ($addNewPassword->num_rows < 0)
                {
                    return "Err";
                }
                else
                {
                    return "Updated";
                }
            }
            else
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
        return "An error occured: " . $addNewPassword->error;
    }

}

?>

