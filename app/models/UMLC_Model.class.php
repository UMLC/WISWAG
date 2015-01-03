<?php

namespace models;

class EGR_Model {

    public function __construct($id = "new") {
        $class = get_called_class();
        $table = preg_replace("/^.*_(.*)$/", "$1", $class);
        $real_table = strtolower($table);
        $this->tablename = $real_table;
        // check id is not null
        if (!empty($id) && intval($id) > 0) {
            $this->id = $id;
            $this->populate();
        } else if($id === "new") {
            $this->create();
        } else {
            error_log("An incorrect value '" . $id . "' was passed as ID for a " . $this->tablename);
        }
    }

    private function populate() {
        global $db;
        // check id exists in DB
        $res = $db->query("SELECT * FROM " . $this->tablename . " WHERE id = '" . intval($this->id) . "'");
        if (is_array($res) && count($res) > 0) {
            // load model
            foreach ($res[0] as $field => $value) {
                $this->$field = $value;
            }
        } else {
            error_log("Invalid ID '" . $this->id . "' supplied for model type '" . $this->tablename . "'");
            foreach($this as $attribute => $value){
                unset($this->$attribute);
            }
            return false;
        }
    }

    private function create() {
        global $db;
        // else create the model
        $id = $db->query("INSERT INTO " . $this->tablename . "(id) VALUES('')");
        $this->id = intval($id);
        $this->populate();
        error_log("DEBUG: The new " . $this->tablename . "'s ID is " . $this->id . ".");
    }

    public function set($key = "NULL", $value = "") {
        global $db;
        if (!isset($this->$key)) {
            error_log($this->tablename . ":" . $this->id . " -- Invalid key '" . $key . "' supplied for set()");
            return false;
        } else {
            $db->query("UPDATE " . $this->tablename . " SET " . $key . "=" . $value . " WHERE id='" . $this->id . "'");
            $this->populate();
        }
    }

    public function delete() {
        global $db;
        $db->query("DELETE FROM " . $this->tablename . " WHERE id='" . $this->id . "'");
        $this->populate();
    }

}
