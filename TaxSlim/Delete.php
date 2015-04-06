<?php
$taxfile= $_COOKIE["Taxfile"];
$hitsfile= $_COOKIE["Hitsfile"];
$logfile= $_COOKIE["Logfile"];
$Test=$_POST["Name"];
unlink($Test);
unlink($hitsfile);
unlink($logfile);

?>