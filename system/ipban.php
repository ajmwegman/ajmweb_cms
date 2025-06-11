<?php
//$iplist = array("117.41.184.199", "83.86.101.106"); // the list of banned IPs
$iplist = array("117.41.184.199", "60.169.78.172"); // the list of banned IPs

$ip = getenv("REMOTE_ADDR"); // get the visitors IP address
// echo "$ip";
$found = false;
foreach ($iplist as $value) { // scan the list
  if (strpos($ip, $value) === 0){
    $found = true;
  }
}

if ($found == true) {
  header("location: ../404.html"); // page to divert to
}
?>