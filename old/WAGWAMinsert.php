
<?php
$servername = "localhost";
$username = "UMLC_data";
$password = "UMLC_data";

//MySQL Database Connect 
include 'UMLCdatalogin.php';

$name=mysql_real_escape_string($_POST['name']);
$class=mysql_real_escape_string($_POST['class']);
$complete=mysql_real_escape_string($_POST['complete']);
$evidence=mysql_real_escape_string($_POST['evidence']);
$improve=mysql_real_escape_string($_POST['improve']);
$modify=mysql_real_escape_string($_POST['modify']);

$what=mysql_real_escape_string($_POST['what']);
$when=mysql_real_escape_string($_POST['when']);
$location=mysql_real_escape_string($_POST['location']);
//$times = $perday*perweek
$times=mysql_real_escape_string($_POST['times']);
$comment=mysql_real_escape_string($_POST['comment']);

//question on the following concerning coding
//following only appears at weeks 3,6,9.
$emotion=mysql_real_escape_string($_POST['emotion']);
//following only appears in week 12
$met=mysql_real_escape_string($_POST['met']);

//mysql_select_db("UMLC_data") or die(mysql_error()); IS THIS NEEDED SINCE ABOVE HAS ALREADY INCLUDED UMLCdatalogin.php ?
//not needed because when we created the 'mysqli' object in UMLCdatalogin.php, we included the name of the database
 
//echo "Connected to Database"."<BR>";

//$query = "INSERT INTO 4DX VALUES ('','$Name','$Class','$Complete','$Evidence','$Improve','$What','$When','$Location','$Frequency',$Comment')";
//mysql_query($query);
//mysqli_query($query); SHOULD THIS QUERY WITH THE 'i' REPLACE THE ABOVE QUERY? 
//mysql_close();
//mysqli_close(); SHOULD THIS CLOSE WITH THE 'i' REPLACE THE ABOVE QUERY? 

/*
ALL DATABASE OPERATIONS ARE CARRIED OUT IN THIS MANOR:
 * $DB->method
 * EXAMPLES:
 * $DB->query();
 * $DB->close();
*/
?>