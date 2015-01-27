<?php

class UMLC_Class {

    public function __construct($id = 0) {
        $DB = get_connection();
        $data = $DB->query("SELECT * FROM class WHERE id = '" . $id . "'")[0];
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

}
