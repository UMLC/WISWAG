<?php

class UMLC_WAG {

    private $tz = null;

    public function __construct($id = 0) {
        $this->tz = new DateTimeZone('Asia/Chongqing');
        $DB = get_connection();
        $data = $DB->query("SELECT * FROM wag WHERE id = '" . $id . "'")[0];
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
        $this->date = DateTime::createFromFormat("Y-m-d", $this->date, $this->tz);
    }

}
