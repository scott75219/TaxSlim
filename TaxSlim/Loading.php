<html>
<link rel="stylesheet" type="text/css" href="Objects.css" />

<head></head>
<body>
     <div class="headers" >
     	<a href="download/TaxSlim.rar" target="_blank">Download</a>
     	<a href="mailto:scott.goldweber@gmail.com">Contact</a>
     </div>
     <div class="Back">
     </div>
    <p>LOADING</p>

<?php
echo "<html><body><table border=1>";
$f = fopen("files/TaxSlim_3D_50.csv", "r");
$fr = fread($f, filesize("files/TaxSlim_3D_50.csv"));
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
       echo "<td>".$cells[$k]."</td>";
    }
    // for k end
    echo "</tr>";
}
// for i end
?>
</body>
</html>