<?php

function get_connection() {
    static $DB = null;
    if (empty($DB)) {
        $DB = new DB();
    }
    return $DB;
}

require_once 'teacher.func.php';
require_once 'wis.func.php';
