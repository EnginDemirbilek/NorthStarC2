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
  <title>NorthStar - Logs</title>
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
  <script>


  function reload()
  {
    setTimeout(function(){
      location.reload();
  }, 8000);

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
            <h1 class="h3 mb-0 text-gray-800">LOGS</h1>


          </div>

          <div class="table-responsive p-3">
            <table class="table align-items-center table-flush" id="dataTable">

              <thead class="thead-light">
                  <tr>
                    <th>ID</th>
                    <th>Log Date</th>
                    <th>Log Type</th>
                    <th>Log Client</th>
                    <th>Log Content</th>
                    <th>Log IP</th>
                    <th>
                  </tr>
                </thead>
                <tbody>

                  <?php

                  $num = 1;
                  $result = $conn->query("select logDate, logType, logClient, logContent, logIp from logs ORDER by id DESC");
		if($result->num_rows >0){
                  while($row = $result->fetch_assoc())
                  {
                    echo "<tr>"."<td>". $num . "</td>"."<td>" . $row['logDate'] . "</td>" . "<td>".  $row["logType"] . "</td>" . "<td>" . $row["logClient"] ."</td>". "<td>" . htmlspecialchars($row["logContent"]) ."</td>"."<td>" . $row["logIp"] ."</td>". "<td>"  ."</td>" ."</tr>";
                    $num++;

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
</body>

</html>
