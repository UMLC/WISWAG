<?php

function get_connection() {
    static $DB = null;
    if (empty($DB)) {
        $DB = new DB();
    }
    return $DB;
}

require_once 'functions/teacher.func.php';
require_once 'functions/wis.func.php';
