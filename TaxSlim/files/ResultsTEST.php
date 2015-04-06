

<html>
<link rel="stylesheet" type="text/css" href="css/Mine.css" />
<link rel="stylesheet" href="css/hive.css" type="text/css" />
<link rel="stylesheet" href="css/qpstyles.css" type="text/css" />
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<link rel="stylesheet" href="old/oldcss/menu.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="old/oldcss/styles.css" />
<head></head>
<body>
<b id="tool-heading">Results</b>
<div id="inner-container" style="width=100%" >
<?php

  makeHeader("Tax Slim");
  makeTable();


function makeHeader($name){
echo "<html><body><table border=0 style='font-weight:bold;'>";
echo "<th >".$name."</th>";
echo"</table>";
}

function makeTable($name,$type){
echo "<html><body><table border=1 id='result-table'>";

$f = fopen("TaxSlim_3D_50.csv", "r");
$fr = fread($f, filesize("TaxSlim_3D_50.csv"));
fclose($f);
$lines = array();
$lines = explode("\n\r",$fr);
for($i=0;$i<count($lines);$i++)
{
    echo "<tr>";
    $cells = array();
    $cells = explode(",",$lines[$i]);
    for($k=0;$k<count($cells);$k++)
    {
       if($i==0){
       echo "<td style='background-color:#F4F4F4;
		'>".$cells[$k]."</td>";
       }
       else{
       echo "<td>".$cells[$k]."</td>";}
    }
    // for k end
    echo "</tr>";
}
// for i end
echo "</table>";
}

function makeLog($type){
echo "<html><body><table border=1 id='result-table'>";

$f = fopen($type, "r");
$fr = fread($f, filesize($type));
fclose($f);
$lines = array();
$lines = explode("\n",$fr);
for($i=0;$i<count($lines)-1;$i++)
{
    echo "<tr>";
    $cells = array();
    $cells = explode(",",$lines[$i]);
    for($k=0;$k<count($cells);$k++)
    {
       echo "<td>".$cells[$k]."</td>";
    }
    // for k end
    echo "</tr>";
}
// for i end
echo "</table>";
}
?>
</div>
</body>
</html>