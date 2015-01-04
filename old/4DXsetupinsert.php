
<?php
$servername = "localhost";
$username = "UMLC_data";
$password = "UMLC_data";

//MySQL Database Connect 
include 'UMLCdatalogin.php';

$statement=mysql_real_escape_string($_POST['statement']);
$explanation=mysql_real_escape_string($_POST['explanation']);
$wisl1=mysql_real_escape_string($_POST['wisl1']);
$wisl2=mysql_real_escape_string($_POST['wisl2']);
$wisl3=mysql_real_escape_string($_POST['wisl3']);
$wisl4=mysql_real_escape_string($_POST['wisl4']);

$sbrperson=mysql_real_escape_string($_POST['sbrperson']);
$sbcompleted=mysql_real_escape_string($_POST['sbcompleted']);
$WAGready=mysql_real_escape_string($_POST['WAGready']);

$start=mysql_real_escape_string($_POST['start']);
$skip=mysql_real_escape_string($_POST['skip']);
$summative=mysql_real_escape_string($_POST['summative']);
$notes=mysql_real_escape_string($_POST['notes']);

$query = "INSERT INTO wis ('', statement, explanation, wisl1, wisl2, wisl3, wisl4, sbrperson, sbcompleted, WAGready, start, skip, summative, notes) "
        . "VALUES ('','$statement','$explanation','$wisl1','$wisl2','$wisl3','$wisl4','$sbrperson','$sbcompleted','$WAGready',$start', '$skip', '$summative','$notes')";
mysql_query($query);

mysql_close();

/*
 * I'M NOT CERTAIN IF I NEED TO PUT $DB-> IN THE ABOVE SCRIPT
ALL DATABASE OPERATIONS ARE CARRIED OUT IN THIS MANOR:
 * $DB->method
 * EXAMPLES:
 * $DB->query();
 * $DB->close();
*/
?>
