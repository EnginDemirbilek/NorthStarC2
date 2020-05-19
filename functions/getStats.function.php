<?php
function getStats($conn)
{

  $botDetails = $conn->prepare("SELECT  COUNT(id) as total,
      (SELECT COUNT(id) FROM slaves WHERE slaveOperatingSystem LIKE '%Windows 10%') as Windows10Count,
      (SELECT COUNT(id) FROM slaves WHERE slaveOperatingSystem LIKE '%Windows Server 2012%') as WindowsServer2012Count,
      (SELECT COUNT(id) FROM slaves WHERE slaveOperatingSystem LIKE '%Windows Server 2016%') as WindowsServer2016Count,
      (SELECT COUNT(id) FROM slaves WHERE slaveOperatingSystem LIKE '%Windows Server 2008%') as WindowsServer2008Count,
      (SELECT COUNT(id) FROM slaves WHERE slaveOperatingSystem LIKE '%Windows 8%') as Windows8Count,
      (SELECT DISTINCT COUNT(id) FROM slaves WHERE slaveStatus='online') as TotalOnline,
      (SELECT COUNT(id) FROM slaves WHERE slaveIsAdmin='TRUE') as TotalAdmins
  FROM  slaves");
	if($botDetails !== false)
  	  {
	     $errorControl = $botDetails->execute();
		if($errorControl !== false)
		{
			$botDetails->store_result();
  			$botDetails->bind_result($totalMachines,$Win10Count,$Server2012Count,$Server2016Count,$Server2008Count,$Windows8Count, $totalOnline, $totalAdmins);
  			$botDetails->fetch();
		}
 	     else
		return "An error occured: ". $botDetails->error;
	}
	else
	    return "An error occured: ". $conn->error;
	   

  if($totalMachines != 0){
  $Server2016Percentage = 100 * $Server2016Count / $totalMachines;
  $Server2012Percentage = 100 * $Server2012Count / $totalMachines;
  $Server2008Percentage = 100 * $Server2008Count / $totalMachines;
  $Windows10Percentage = 100 * $Win10Count / $totalMachines;
  $Windows8Percentage = 100 * $Windows8Count / $totalMachines;
    return array($totalMachines, $totalOnline, $totalAdmins, $Server2016Count, $Server2012Count, $Server2008Count, $Win10Count, $Windows8Count);
  }
  else{
  return array(0,0,0,0,0,0,0,0);
  }
}
?>
