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

    /*
     * statement
     * explanation
     * wisl1
     * wisl2
     * wisl3
     * wisl4
     * dates
     * sbrperson
     * sbcompleted
     * wagformready
     * formative
     * summative
     * notes

      $DB->query($query);
      $DB->close();
     * 
     */
    ?>
