<?php
include 'session.php';
 ?>
 
<?php
if(isset($_GET["slave"])){
  $name = $_GET["slave"];
}
else if(isset($_POST["slave"])){
  $name = $_POST["slave"];
}
else
{
  header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
  echo "<html><title>Not Found</title><h1>PAGE NOT FOUND</h1><html>";
exit;
}

?>
