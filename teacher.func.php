<?php

function teacher_login() {
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
    if (!empty($_SESSION["teacher_id"])) {
        return true;
    }
    if (!empty($_POST["teacherfullname"])) {
        return teacher_is_in_db($_POST["teacherfullname"]);
    }
    return false;
}

function teacher_is_in_db($teacher) {
    global $DB;
    $teacher = $DB->query("SELECT * FROM teacher WHERE name = '" . $teacher . "'");
    if (count($teacher) > 0) {
        $_SESSION["teacher_id"] = $teacher[0]["id"];
        return true;
    }
}
