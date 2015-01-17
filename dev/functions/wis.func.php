<?php

require_once 'models/wis.class.php';

function get_wis() {
    $db = get_connection();
    $wis_id = $db->query("SELECT * FROM wis WHERE convert_tz(curdate(), @@global.time_zone, 'Asia/Chongqing') BETWEEN LEFT(dates, 10) AND RIGHT(dates, 10) ORDER BY id LIMIT 1")[0]["id"];
    $wis = new UMLC_WIS($wis_id);
    return $wis;
}
