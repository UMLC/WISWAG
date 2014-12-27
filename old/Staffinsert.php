<?php
$servername = "localhost";
$username = "UMLC_data";
$password = "UMLC_data";
//are the above really needed since these all seem to be defined in the UMLCdatalogin.php file
//MySQL Database Connect 
include 'UMLCdatalogin.php';

//$value = $_POST['name_en']; I think this is the same as the following line.

$name_en = mysql_real_escape_string($_POST['name_en']);
//$name_zh=mysql_real_escape_string($_POST['name_zh']);
$sql = "INSERT INTO Staff (name_en) Values ('$name_en')";
if (!msql_query($sql)) {
    die('die Error:' . mysql_error());
}

//mysql_select_db("UMLC_data") or die(mysql_error()); IS THIS NEEDED SINCE ABOVE HAS ALREADY INCLUDED UMLCdatalogin.php ?
//not needed because when we created the 'mysqli' object in UMLCdatalogin.php, we included the name of the database
 
//echo "Connected to Database"."<BR>";

//$query = "INSERT INTO 4DX VALUES ('','$Name','$Class','$Complete','$Evidence','$Improve','$What','$When','$Location','$Frequency',$Comment')";
//mysql_query($query);
//mysqli_query($query); SHOULD THIS QUERY WITH THE 'i' REPLACE THE ABOVE QUERY? 
//mysql_close();
//mysqli_close(); SHOULD THIS CLOSE WITH THE 'i' REPLACE THE ABOVE QUERY? 
mysql_close();
?>