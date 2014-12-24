<?php
$servername = "localhost";
$username = "UMLC_data";
$password = "UMLC_data";

//MySQL Database Connect 
include 'UMLCdatalogin.php';

$Name=mysql_real_escape_string($_POST['Name']);
$Class=mysql_real_escape_string($_POST['Class']);
$Complete=mysql_real_escape_string($_POST['Complete']);
$Positive=mysql_real_escape_string($_POST['Evidence']);
$Improve=mysql_real_escape_string($_POST['Improve']);
$What=mysql_real_escape_string($_POST['What']);
$When=mysql_real_escape_string($_POST['When']);
$Location=mysql_real_escape_string($_POST['Location']);
$Frequency=mysql_real_escape_string($_POST['Frequency']);
$Comment=mysql_real_escape_string($_POST['Comment']);

//mysql_select_db("UMLC_data") or die(mysql_error()); IS THIS NEEDED SINCE ABOVE HAS ALREADY INCLUDED UMLCdatalogin.php ?
 
//echo "Connected to Database"."<BR>";

$query = "INSERT INTO 4DX VALUES ('','$Name','$Class','$Complete','$Evidence','$Improve','$What','$When','$Location','$Frequency',$Comment')";
mysql_query($query);
//mysqli_query($query); SHOULD THIS QUERY WITH THE 'i' REPLACE THE ABOVE QUERY? 
mysql_close();
//mysqli_close(); SHOULD THIS CLOSE WITH THE 'i' REPLACE THE ABOVE QUERY? 
?>