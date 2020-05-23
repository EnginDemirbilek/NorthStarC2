<?php
include $_SERVER["DOCUMENT_ROOT"] . "/session.php";
include $_SERVER["DOCUMENT_ROOT"]. "/conn.php";
include 'assignCommand.function.php';
include 'uploadFile.function.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["token"]) && isset($_POST["slave"]) && isset($_POST["command"]) && !isset($_FILES["fileToUpload"]))
{
    if ($_POST["token"] == $_SESSION["token"])
    {
        $returnVal = assignCommand($_POST["slave"], $_POST["command"], $conn);
        if (strpos($returnVal, 'error') !== false)
        {
            echo $returnVal;
        }
        else if (strpos($returnVal, 'assigned'))
        {
            echo $returnVal;
        }
    }
    else
    {
        echo "error invalid csrf token.";
    }
}
else if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == UPLOAD_ERR_OK && isset($_POST["token"]) && isset($_POST["slave"]))
{

  if($_POST["token"] == $_SESSION["token"])
  {
    $returnVal = uploadFile($_FILES["fileToUpload"], $conn, $_POST["slave"]);
    if ($returnVal == "OK")
    {
        echo '<script>document.getElementById("resultTextarea").value=' . '"Upload command sended. Waiting for response";'. '</script>';
	echo "<script>isCommandSended=true;";
	echo "retrieveResponse();</script>";
    }
   else{
 echo '<script>document.getElementById("resultTextarea").value=' . $returnVal . '</script>';
	}
  }
}

?>
	
