<?php
session_start();
include 'conn.php';
include 'functions/login.function.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>The NorthStar Login</title>
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

</head>

<body class="bg-gradient-login">
  <!-- Login Content -->
  <div class="container-login">
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card shadow-sm my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-12">
                <div class="login-form">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Login</h1>
                  </div>
                  <form class="user" method="post" action="">
                    <div class="form-group">
                      <input type="text" class="form-control" name="username" placeholder="Username">
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control" name="password" placeholder="Password">
                    </div>
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small" style="line-height: 1.5rem;">

                      </div>
                    </div>
                    <div class="form-group">
                      <input type="submit" name="submit" value="Login" class="btn btn-primary btn-block"></a>
                    </div>

                  </form>
                  <hr>
		  <div class="modal fade" id="notifyModal" tabindex="-1" role="dialog" aria-labelledby="notifyModalTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="notifyModalCenterTitle">Warning</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          Wrong username and/or password.
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="text-center">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Login Content -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
</body>

<?php

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["password"]))
{
$username = $_POST["username"];
$password =$_POST["password"];
$agent = htmlspecialchars($_SERVER["HTTP_USER_AGENT"]);
$ip = $_SERVER["REMOTE_ADDR"];
$retVal = login($username, $password,$conn,$agent, $ip);
	
  if($retVal == "Login Failed")
  {

    echo '<script>$(document).ready(function(){ $("#notifyModal").modal(\'show\');});</script>';
  }
  elseif ($retVal == "OK")
  {
	  $_SESSION["token"] = $_SESSION["token"] = md5(uniqid(rand() , true));
          $_SESSION["username"] = $username;
	echo "<script>window.location.href = '/index.php'</script>";
 }
  else{
  	echo $retVal;
  }
}

 ?>

</html>
