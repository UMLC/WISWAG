<pre>
<?php
include 'UMLCdatalogin.php';
foreach($_POST as $key => $value){
    $_POST[$key] = $DB->escape_string($value);
}
echo json_encode($_POST, JSON_PRETTY_PRINT);
$DB->close();