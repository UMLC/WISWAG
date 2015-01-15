<?php

function teacher_login() {
    session_start();
    if (!teacher_is_logged_in()) {
        show_login_form();
    }
}

function show_login_form() {
    ?><form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <input type="text" name="teacherfullname">
        <button type="submit">GO</button>
    </form><?php
}

function teacher_is_logged_in() {
    if (!empty($_SESSION["loggedin"])) {
        return true;
    }
    if (!empty($_POST["teacherfullname"])) {
        return teacher_is_in_db($_POST["teacherfullname"]);
    }
    return false;
}

function teacher_is_in_db($teacher) {
    global $DB;
    if (count($DB->query("SELECT * FROM teacher WHERE name = '" . $teacher . "'")) > 0) {
        $_SESSION["loggedin"] = true;
        return true;
    }
}
