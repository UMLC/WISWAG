<pre>
    <?php
    require_once "UMLCdatalogin.php";

    foreach ($_POST as $variable_name => $value) {
        if (is_string($value)) {
            $_POST[$variable_name] = $DB->escape_string($value);
        } else {
            $_POST[$variable_name] = join(",", $value);
        }
    }

    //if $_POST["wisid"] === "new"
    if($_POST["wisid"] === "new"){
        //INSERT ...
        $DB->query("INSERT INTO wis(id) VALUES('')");
        $_POST["wisid"] = $DB->insert_id;
    }
    // UPDATE ... WHERE id='$_POST["wisid"]'
    $wisid = array_shift($_POST);
    foreach($_POST as $key => $value){
        $DB->query("UPDATE wis SET " . $key . " = '" . $value . "' WHERE id = '" . $wisid . "'");
    }
    
    $DB->close();
    header("Location: http://www.umlc.org/data/4DXsetup.php");