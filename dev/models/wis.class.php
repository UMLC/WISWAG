<?php

class UMLC_WIS {

    private $tz = null;

    public function __construct($id = 0) {
        $this->tz = new DateTimeZone('Asia/Chongqing');
        $DB = get_connection();
        $data = $DB->query("SELECT * FROM wis WHERE id = '" . $id . "'")[0];
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
        $this->dates = explode(",", $this->dates);
        foreach ($this->dates as $i => $date) {
            $this->dates[$i] = DateTime::createFromFormat("Y-m-d", $date, $this->tz);
        }
        $this->get_week_ranges();
    }

    public function get_week_ranges() {
        $this->weeks = [];
        $last_week_end = clone $this->dates[0];
        $one_day = new DateInterval("P1D");
        $six_days = new DateInterval("P6D");
        $last_week_end->sub(new DateInterval("P4D"));
        foreach ($this->dates as $i => $date) {
            $this_week_start = clone $last_week_end;
            $this_week_start->add($one_day);
            $this_week_end = clone $this_week_start;
            $this_week_end->add($six_days);
            $last_week_end = clone $this_week_end;
            $this->weeks[] = [
                "number" => $i + 1,
                "start" => $this_week_start,
                "end" => $this_week_end
            ];
        }
        unset($this->dates);
    }

    public function week_number($date) {
        $seconds = $date->format("U");
        foreach ($this->weeks as $week) {
            if ($seconds >= $week["start"]->format("U") && $seconds <= $week["end"]->format("U")) {
                return $week["number"];
            }
        }
        return 0;
    }

    public function wag($teacher, $week_num = 1) {
        $week = $this->weeks[$week_num - 1];
        $DB = get_connection();
        $wag_id = $DB->query("SELECT * FROM wagwam WHERE date BETWEEN '" . $week["start"]->format("Y-m-d") . "' AND '" . $week["end"]->format("Y-m-d") . "' AND teacher = '" . $teacher . "' ORDER BY id LIMIT 1")[0]["id"];
        if (intval($wag_id) > 0) {
            $wag = new UMLC_WAG($wag_id);
            return $wag;
        } else {
            return false;
        }
    }

}
