
<?php
$servername = "localhost";
$username = "UMLC_data";
$password = "UMLC_data";

//MySQL Database Connect

// the file you want is in this folder's parent folder: ../
require_once "../UMLCdatalogin.php";

//we can use a foreach loop to escape_string all the post variables

$assoc_array = [
    "aname" => "a value",
    "nametwo" => "value two"
];

echo $assoc_array["aname"]; // echoes 'a value'

foreach($_POST as $variable_name => $value){
    $_POST[$variable_name] = $DB->escape_string($value);
}

$statement=$_POST['statement'];
$explanation=$_POST['explanation'];
$wisl1=$_POST['wisl1'];
$wisl2=$_POST['wisl2'];
$wisl3=$_POST['wisl3'];
$wisl4=$_POST['wisl4'];
$length=$_POST['length'];

$sbrperson=$_POST['sbrperson'];
$sbcompleted=$_POST['sbcompleted'];
$WAGready=$_POST['WAGready'];

$start=$_POST['start'];
$skip=$_POST['skip'];
$summative=$_POST['summative'];
$notes=$_POST['notes'];

$query = "INSERT INTO wis (statement, explanation, wisl1, wisl2, wisl3, wisl4, length, sbrperson, sbcompleted, WAGready, start, skip, summative, notes)
      VALUES ('$statement','$explanation','$wisl1','$wisl2','$wisl3','$wisl4','$length','$sbrperson','$sbcompleted','$WAGready',$start', '$skip', '$summative','$notes')";
$DB->query($query);

$DB->close();

/*
 * I'M NOT CERTAIN IF I NEED TO PUT $DB-> IN THE ABOVE SCRIPT
ALL DATABASE OPERATIONS ARE CARRIED OUT IN THIS MANOR:
 * $DB->method
 * EXAMPLES:
 * $DB->query();
 * $DB->close();
*/
?>
