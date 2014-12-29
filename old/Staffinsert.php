<?php include 'UMLCdatalogin.php'; ?>

<?php
// create a variable
$name_en=$_POST['name_en'];

//$name_en = mysql_real_escape_string($_POST['name_en']);  this is the way Zeal does it
//$name_zh=mysql_real_escape_string($_POST['name_zh']);
//Execute the query

mysqli_query($con,"INSERT INTO Staff (name_en) 
VALUES ('name_en')");

if(mysqli_affected_rows($con) > 0){
 echo "<p>Employee Added</p>";
 echo "<a href="Staffinfoform.html">Go Back</a>";
} else {
 echo "Staff Added<br />";
 echo mysqli_error ($con);
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
