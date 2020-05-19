<?php
include 'session.php';
include 'conn.php';
include 'functions/getStats.function.php';
include 'functions/updateLogs.function.php';

$stats = getStats($conn);

if(is_array($stats) && count($stats) > 7 && $stats[0] > 0){
$totalMachines = $stats[0];
$totalOnline = $stats[1];
$totalAdmins = $stats[2];
$Server2016Count = $stats[3];
$Server2012Count = $stats[4];
$Server2008Count =$stats[5];
$Win10Count = $stats[6];
$Windows8Count = $stats[7];
$Server2016Percentage = 100 * $stats[3] / $totalMachines;
$Server2012Percentage = 100 * $stats[4] / $totalMachines;
$Server2008Percentage = 100 * $stats[5] / $totalMachines;
$Windows10Percentage = 100 * $stats[6] / $totalMachines;
$Windows8Percentage = 100 * $stats[7] / $totalMachines;
}
else{ 
  updateLogs($conn, "Error", "An error occured while retrieving stats", " ", " ", " ");
  $totalMachines = 0;
  $totalOnline = 0;
  $totalAdmins = 0;
  $Server2016Count = 0;
  $Server2012Count = 0;
  $Server2008Count = 0;
  $Win10Count = 0;
  $Windows8Count = 0;
  $Server2016Percentage = 0;
  $Server2012Percentage = 0;
  $Server2008Percentage = 0;
  $Windows10Percentage = 0;
  $Windows8Percentage = 0;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>NorthStar - Dashboard</title>
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
  <script>
var broadcasting = false;
  function reload()
  {

    setTimeout(function(){
      if(!broadcasting)
        location.reload();
  }, 90000);

  }

   function broadcastPing(){
    broadcasting = true;
    var csrfToken = "<?php echo $_SESSION['token'];?>";
    var xhr = new XMLHttpRequest();
    xhr.open("POST", '/functions/setCommand.nonfunction.php', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() { // Call a function when the state changes.
        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
          if(!xhr.responseText.includes("error"))
          {
            document.getElementById("reloadButton").value = " ";
            document.getElementById("reloadButton").disabled = true;
            document.getElementById("reloadButton").className = "spinner-border";
            setTimeout(function(){
              broadcasting = false;
              location.reload();
          }, 14000);
          }
          else
          {
            document.getElementById("reloadErrControl").innerHTML = xhr.responseText;
          }
        }
    }

    xhr.send("slave=broadcast&command=ping&token=" + csrfToken);
  }
reload();
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
           
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Total Sessions</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalMachines;?></div>
                      <div class="mt-2 mb-0 text-muted text-xs">
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-desktop fa-2x text-primary"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Alive Sessions</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalOnline;?></div>

                      <div class="mt-2 mb-0 text-muted text-xs">
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-arrow-up fa-2x text-success"></i><br>
                      <input type="button" id="reloadButton" value="Reload" class="btn btn-sm btn-primary" onclick="broadcastPing();" />
                    <br>
                   <p id="reloadErrControl"></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- New User Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Admin Privileges</div>
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $totalAdmins;?></div>
                      <div class="mt-2 mb-0 text-muted text-xs">
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-info"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Online Web Users</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">1</div>
                      <div class="mt-2 mb-0 text-muted text-xs">

                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-user fa-2x text-warning"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Area Chart -->
            <div class="col-xl-8 col-lg-7">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Machine Report</h6>
                  <div class="dropdown no-arrow">

                  </div>
                </div>
                <div class="card-body">
                  <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                  </div>
                </div>
              </div>
            </div>
            <!-- Pie Chart -->
            <div class="col-xl-4 col-lg-5">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Operating Systems</h6>
                  <div class="dropdown no-arrow">

                  </div>
                </div>
                <div class="card-body">
                  <div class="mb-3">
                    <div class="small text-gray-500">Windows Server 2016
                      <div class="small float-right"><b><?php echo $Server2016Count;?> of <?php echo $totalMachines;?> </b></div>
                    </div>
                    <div class="progress" style="height: 12px;">
                      <div class="progress-bar bg-warning" role="progressbar" style="width:<?php echo $Server2016Percentage;?>%" aria-valuenow="80"
                        aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                  </div>
                  <div class="mb-3">
                    <div class="small text-gray-500">Windows Server 2012
                      <div class="small float-right"><b><?php echo $Server2012Count;?> of <?php echo $totalMachines;?></b></div>
                    </div>
                    <div class="progress" style="height: 12px;">
                      <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $Server2012Count;?>%" aria-valuenow="70"
                        aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                  </div>
                  <div class="mb-3">
                    <div class="small text-gray-500">Windows Server 2008
                      <div class="small float-right"><b><?php echo $Server2008Count;?> of <?php echo $totalMachines;?></b></div>
                    </div>
                    <div class="progress" style="height: 12px;">
                      <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo $Server2008Count;?>%" aria-valuenow="55"
                        aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                  </div>
                  <div class="mb-3">
                    <div class="small text-gray-500">Windows 10
                      <div class="small float-right"><b><?php echo $Win10Count;?> of <?php echo $totalMachines;?></b></div>
                    </div>
                    <div class="progress" style="height: 12px;">
                      <div class="progress-bar bg-info" role="progressbar" style="width:<?php echo $Windows10Percentage;?>%" aria-valuenow="50"
                        aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                  </div>
                  <div class="mb-3">
                    <div class="small text-gray-500">Windows 8
                      <div class="small float-right"><b><?php echo $Windows8Count;?> of <?php echo $totalMachines;?></b></div>
                    </div>
                    <div class="progress" style="height: 12px;">
                      <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $Windows8Percentage;?>%" aria-valuenow="30"
                        aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                  </div>
                </div>
                <div class="card-footer text-center">

                </div>
              </div>
            </div>

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

</html>
