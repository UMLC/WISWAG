<?php

require_once 'models/wis.class.php';

function get_wis() {
    $wis = new UMLC_WIS();
    return $wis;
}
