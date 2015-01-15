<?php

class DB {

    private $user = "UMLC_data";
    private $pass = "UMLC_data";
    private $host = "localhost";
    private $base = "umlc_data";
    private $conn = false;

    public function __construct() {
        $this->conn = new \mysqli($this->host, $this->user, $this->pass, $this->base);
    }

    public function query($sql) {
        $result = $this->conn->query($sql);
        if ($result === false) {
            echo "Query Failed: " . $sql;
            return false;
        } else if ($result === true) {
            if ($this->conn->insert_id) {
                return $this->conn->insert_id;
            }
            return true;
        } else {
            $res = [];
            for ($i = 0; $result->data_seek($i); $i++) {
                $res[] = $result->fetch_assoc();
            }
            return $res;
        }
        return false;
    }

    public function __destruct() {
        $this->conn->close();
    }

}
