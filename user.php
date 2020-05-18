<?php
include 'session.php';
include 'conn.php';
include 'functions/updatePassword.function.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>NorthStar</title>
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  
<script>

  function broadcastPing(){

    var xhr = new XMLHttpRequest();
    xhr.open("POST", '/interact.php', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("slave=broadcast&command=ping");
    document.getElementById("reloadButton").value = " ";
    document.getElementById("reloadButton").disabled = true;
    document.getElementById("reloadButton").className = "spinner-border";
    setTimeout(function(){
      location.reload();
  }, 14000);

  }

  </script>
</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
    <ul class="navbar-nav sidebar sidebar-light">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">

          <div class="sidebar-brand-text mx-3">NorthStar</div>
        </a>
        <hr class="sidebar-divider my-0">
        <li class="nav-item">
          <a class="nav-link" href="index.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="clients.php">
            <i class="fas fa-fw fa-desktop"></i>
            <span>Sessions</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logs.php">
            <i class="fas fa-fw fa-history"></i>
            <span>Server Logs</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="user.php">
            <i class="fas fa-fw fa-user"></i>
            <span>User Settings</span></a>
        </li>
        <hr class="sidebar-divider">
        <div class="version" id="version-ruangadmin"></div>
      </ul>

    <!-- Sidebar -->
  <div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
      <!-- TopBar -->
      <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
          <!-- TopBar -->
          <nav class="navbar navbar-expand navbar-light bg-navbar topbar mb-4 static-top">
            <ul class="navbar-nav ml-auto">
              <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="/logout.php"  role="button"  aria-expanded="false">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw text-gray-400"></i>
                  <span class="ml-2 d-none d-lg-inline text-white small">Logout</span>
                </a>

              </li>
            </ul>
          </nav>

        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>

          </div>

          <div class="row mb-3">
            <!-- -->

            <!-- Area Chart -->
            <div class="col-xl-8 col-lg-7">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Login Logs</h6>
                  <div class="dropdown no-arrow">

                  </div>
                </div>
                <div class="card-body">



                    <div class="table-responsive p-3">
                      <table class="table align-items-center table-flush" id="dataTable">
                      <tbody>

                            <?php

                            $num = 1;
                            $result = mysqli_query($conn, "select logDate, logIP, logUserAgent from logs where logType='login' ORDER by id DESC");

                            while($row = mysqli_fetch_array($result))
                            {
                              echo "<tr>"."<td>" . $row['logDate'] . "</td>"  ."<td>" . $row["logIP"] ."</td>"."</tr>";


                            }

                             ?>



                          </tbody>
                        </table>
                      </div>

                  </div>
                </div>
              </div>

            <!--  -->
            <div class="col-xl-4 col-lg-5">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Change Password</h6>
                  <div class="dropdown no-arrow">

                  </div>
                </div>
                <div class="card-body">
                  <div class="mb-5">
                                        <form class="user" method="post" action="">
                                          <div class="form-group">
                                            <input type="password" class="form-control" name="currentpassword" placeholder="Current Password">
                                          </div>
                                          <div class="form-group">
                                            <input type="password" class="form-control" name="newpassword" placeholder="New Password">
                                          </div>
                                          <div class="form-group">
                                            <div class="custom-control custom-checkbox small" style="line-height: 1.5rem;">

                                            </div>
                                          </div>
                                          <div class="form-group">
                                            <input type="submit" name="submit" value="Update" class="btn btn-primary btn-block"></a>
                                          </div>

                                        </form>
                </div>
                <div class="card-footer text-center">

                </div>
              </div>
            </div>
		<!--  Modals -->
		      <div class="modal fade" id="notifyModal" tabindex="-2" role="dialog" aria-labelledby="notifyModalTitle" aria-hidden="true">
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
                          <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>


		      <div class="modal fade" id="updatedModal" tabindex="-1" role="dialog" aria-labelledby="updatedModalTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="notifyModalCenterTitle">Success</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          Password Successfully Updated !
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>

		<!-- modals -->

                <div class="card-footer"></div>
              </div>
            </div>
            </div>
              </div>
            </div>
          </div>
        </div>
        <!---Container Fluid-->
      </div>
      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>copyright &copy; <script> document.write(new Date().getFullYear()); </script> - developed by
              <b><a href="https://engindemirbilek.github.io" target="_blank">Engin Demirbilek</a></b>
            </span>
          </div>
        </div>
      </footer>
      <!-- Footer -->
    </div>
  </div>

  <!-- Scroll to top -->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  <script src="vendor/chart.js/Chart.min.js"></script>
  <script src="js/chart-area.php" type="text/javascript"></script>
</body>
<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){

  $oldPassword = MD5("NorthyBoi" . $_POST['currentpassword'] . "NorthyBoi");
  $password = MD5("NorthyBoi". $_POST["newpassword"] . "NorthyBoi");
 $returnVal = updatePassword($_SESSION["username"], $oldPassword, $password, $conn);
	if($returnVal == "Err"){
		echo '<script>$(document).ready(function(){ $("#updatedModal").modal(\'show\');});</script>';
		}
	else if($returnVal == "Updated"){
		echo '<script>$(document).ready(function(){ $("#notifyModal").modal(\'show\');});</script>';
		}
}
 ?>
</html>
