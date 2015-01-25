<?php

function get_wis() {
    $db = get_connection();
    $wis_id = $db->query("SELECT * FROM wis WHERE convert_tz(curdate(), @@global.time_zone, 'Asia/Chongqing') BETWEEN SUBDATE(LEFT(dates, 10), INTERVAL 4 DAY) AND ADDDATE(RIGHT(dates, 10), INTERVAL 2 DAY) ORDER BY id LIMIT 1")[0]["id"];
    if (intval($wis_id) > 0) {
        $wis = new UMLC_WIS($wis_id);
        return $wis;
    } else {
        throw new Exception("No WIS is defined for the current time period.");
    }
}

function date_now(){
    $tz = new DateTimeZone('Asia/Chongqing');
    return new DateTime("now", $tz);
}