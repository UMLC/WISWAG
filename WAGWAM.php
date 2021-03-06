<!DOCTYPE html>
<html>
    <head>
        <title>4DX WAG Form</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php

        function show() {
            global $DB;
            global $teacher_id;
            $result = $DB->query("SELECT * FROM wis ORDER BY id DESC LIMIT 1");
            $wis = $result->fetch_all(MYSQLI_ASSOC)[0];
            $dates = explode(",", $wis["dates"]);
            $number_of_weeks = count($dates);
            $tz = new DateTimeZone("Asia/Chongqing");
            $today = new DateTime("now", $tz);
            $week = 0;
            foreach ($dates as $i => $date) {
                $date = DateTime::createFromFormat("Y-m-d", $date, $tz);
                if ($date->format("U") <= $today->format("U")) {
                    $week = $i;
                }
            }
            // if today is 3 or more days after $dates[$week], $week++
            $date = DateTime::createFromFormat("Y-m-d", $dates[$week], $tz);
            $seconds_in_a_day = 60 * 60 * 24;
            $diff = ($today->format("U") - $date->format("U")) / $seconds_in_a_day;
            if ($diff >= 3) {
                $week++;
            }
            $date = DateTime::createFromFormat("Y-m-d", $dates[$week], $tz);
            //get wag for last week and wag for this week
            $lastweek = DateTime::createFromFormat("Y-m-d", $dates[$week - 1]);
            $thisweek = DateTime::createFromFormat("Y-m-d", $dates[$week]);
            $lastweek->sub(new DateInterval("P4D"));
            $lastweekstart = DateTime::createFromFormat("Y-m-d", $lastweek->format("Y-m-d"), $tz);
            $lastweek->add(new DateInterval("P6D"));
            $lastweekend = DateTime::createFromFormat("Y-m-d", $lastweek->format("Y-m-d"), $tz);
            $thisweek->sub(new DateInterval("P4D"));
            $thisweekstart = DateTime::createFromFormat("Y-m-d", $thisweek->format("Y-m-d"), $tz);
            $thisweek->add(new DateInterval("P6D"));
            $thisweekend = DateTime::createFromFormat("Y-m-d", $thisweek->format("Y-m-d"), $tz);
            $sql = "SELECT * FROM wagwam WHERE teacher = '" . $teacher_id . "' AND date BETWEEN '" . $lastweekstart->format("Y-m-d") . "' AND '" . $lastweekend->format("Y-m-d") . "' ORDER BY id DESC LIMIT 1";
            $res = $DB->query($sql);
            if ($res->num_rows < 1) {
                $lastwag = null;
            } else {
                $lastwag = $res->fetch_all(MYSQLI_ASSOC)[0];
            }
            $sql = "SELECT * FROM wagwam WHERE teacher = '" . $teacher_id . "' AND date BETWEEN '" . $thisweekstart->format("Y-m-d") . "' AND '" . $thisweekend->format("Y-m-d") . "' ORDER BY id DESC LIMIT 1";
            $res = $DB->query($sql);
            if ($res->num_rows < 1) {
                $today = new DateTime("now", $tz);
                $sql = "INSERT INTO wagwam(teacher, wis, date) VALUES('" . $teacher_id . "','" . $wis["id"] . "','" . $today->format("Y-m-d") . "')";
                $DB->query($sql);
                // needs to select between lastweekend + 1 and thisweekend
                $sql = "SELECT * FROM wagwam WHERE teacher = '" . $teacher_id . "' AND date BETWEEN '" . $thisweekstart->format("Y-m-d") . "' AND '" . $thisweekend->format("Y-m-d") . "' ORDER BY id DESC LIMIT 1";
                echo $sql;
                $res = $DB->query($sql);
                $thiswag = $res->fetch_all(MYSQLI_ASSOC)[0];
            } else {
                $thiswag = $res->fetch_all(MYSQLI_ASSOC)[0];
            }
            $res = $DB->query("SELECT * FROM class WHERE id = '" . $lastwag["class"] . "' LIMIT 1");
            $class = $res->fetch_all(MYSQLI_ASSOC)[0];
            $res = $DB->query("SELECT c.id, c.name FROM teacher_class tc JOIN class c ON(tc.class = c.id) WHERE tc.teacher = '" . $teacher_id . "'");
            $teacher_classes = $res->fetch_all(MYSQLI_ASSOC);
            ?>
            <form action="WAGWAMinsert.php" method="post">
                <input type="hidden" name="teacher" value="<?php echo $teacher_id; ?>">
                <h1><?php echo $wis["statement"]; ?></h1>
                <h2>4DX Weekly Action Goal (WAG)</h2>
                <h3>Week <?php echo $week + 1; ?> of <?php echo $number_of_weeks; ?> weeks</h3>
                <?php if ($lastwag) { ?>
                    <h4>Last Week:</h4>
                    <label>Did you attend the Weekly Accountability Meeting (WAM)? <input type="radio" name="attend" value="1"<?php
                        if (intval($lastwag["attend"]) === 1) {
                            echo " checked";
                        }
                        ?>>Yes! <input type="radio" name="attend" value="0"<?php
                                                                                          if (intval($lastwag["attend"]) !== 1) {
                                                                                              echo " checked";
                                                                                          }
                                                                                          ?>>No</label>
                    <label>Did you complete your WAG? <input type="radio" name="complete" value="1"<?php
                        if (intval($lastwag["complete"]) === 1) {
                            echo " checked";
                        }
                        ?>>Yes! <input type="radio" name="complete" value="0"<?php
                                                             if (intval($lastwag["complete"]) !== 1) {
                                                                 echo " checked";
                                                             }
                                                             ?>>No</label>
                    <label>Did you modify the scoreboard to reflect your progress? <input type="radio" name="modified_score" value="1"<?php
                        if (intval($lastwag["modified_score"]) === 1) {
                            echo " checked";
                        }
                        ?>>Yes!<input type="radio" name="modified_score" value="0"<?php
                                                                                          if (intval($lastwag["modified_score"]) !== 1) {
                                                                                              echo " checked";
                                                                                          }
                                                                                          ?>>No</label>
                    <!-- shown when week# is % 3 = 0, when week# < totalWeeks: -->
                    <?php if (($week + 1) % 3 === 0 && ($week + 1) < $number_of_weeks) { ?>
                        <label>With <?php echo $number_of_weeks - $week - 1; ?> weeks left to "<?php echo $wis["wisl" . $class["level"]]; ?>" I'm feeling...</label>
                        <label>
                            <select name="emotion">
                                <option value="">-- Choose One --</option>
                                <option value="3"<?php
                                if (intval($lastwag["emotion"]) === 3) {
                                    echo " selected";
                                }
                                ?>>we may surpass our goal</option>
                                <option value="2"<?php
                                if (intval($lastwag["emotion"]) === 2) {
                                    echo " selected";
                                }
                                ?>>confident we will make our goal</option>
                                <option value="1"<?php
                                if (intval($lastwag["emotion"]) === 1) {
                                    echo " selected";
                                }
                                ?>>behind, but hopeful we can reach our goal</option>
                                <option value="0"<?php
                                if ($lastwag["emotion"] === '0') {
                                    echo " selected";
                                }
                                ?>>behind, and doubtful we can reach our goal</option>
                            </select>
                        </label><?php
                    }
                    // get wag_action(s) for previous WAG
                    $res = $DB->query("SELECT * FROM wag_action WHERE wagwam = '" . $lastwag["id"] . "'");
                    $wagactions = $res->fetch_all(MYSQLI_ASSOC);
                    // loop through and show evidence/improve for each
                    foreach ($wagactions as $action) {
                        ?>
                        <!-- Repeat following two fields for each action (1-3) giving action 'what' from wag_action -->
                        <h5>For action: "<?php echo $action["what"]; ?>"</h5>
                        <label>Give Evidence that WAG leveraged WIS:</label><label><textarea name="action<?php echo $action["id"]; ?>evidence"><?php echo $action["evidence"]; ?></textarea></label>
                        <label>How could WAG have been better?</label><label><textarea name="action<?php echo $action["id"]; ?>improve"><?php echo $action["improve"]; ?></textarea></label>
                        <?php if (($week + 1) === $number_of_weeks) { ?>
                            <!-- Appears on last week --><label>I attained our wildly important standard: <input type="radio" name="wis_met" value="1"<?php
                                if ($lastwag["wis_met"] === '1') {
                                    echo " checked";
                                }
                                ?>>Yes!<input type="radio" name="wis_met" value="0"<?php
                                                                                                                 if ($lastwag["wis_met"] === '0') {
                                                                                                                     echo " checked";
                                                                                                                 }
                                                                                                                 ?>>No</label>
                                <?php
                            }
                        }
                    }
                    if ($week + 1 < $number_of_weeks) {
                        ?>
                    <!-- If week# !== totalWeeks -->
                    <h5>My WAG for <?php echo $date->format("Y.m.d"); ?> through <?php
                        $friday = $date->add(new DateInterval("P5D"));
                        echo $friday->format("Y.m.d");
                        ?>:</h5>
                    <!-- If teacher is related to more than one class --><label>Which class will you be working with?</label>
                    <?php if (count($teacher_classes) > 1) { ?>
                        <label>
                            <select name="class">
                                <option value="0">-- Choose One --</option>
                                <?php foreach ($teacher_classes as $class) { ?>
                                    <option value="<?php echo $class["id"]; ?>"><?php echo $class["name"]; ?></option>
                                <?php } ?>
                            </select>
                        </label>
                    <?php } else {
                        ?><input type="hidden" name="class" value="<?php echo $teacher_classes[0]["id"]; ?>"><?php
                    }
                    $newactions = [
                        [
                            "id" => "[]",
                            "what" => "",
                            "during" => "",
                            "location" => "",
                            "perday" => "",
                            "perweek" => ""
                        ],
                        [
                            "id" => "[]",
                            "what" => "",
                            "during" => "",
                            "location" => "",
                            "perday" => "",
                            "perweek" => ""
                        ],
                        [
                            "id" => "[]",
                            "what" => "",
                            "during" => "",
                            "location" => "",
                            "perday" => "",
                            "perweek" => ""
                        ]
                    ];
                    if ($thiswag) {
                        $sql = "SELECT * FROM wag_action WHERE wagwam = '" . $thiswag["id"] . "'";
                        $res = $DB->query($sql);
                        $thisweekactions = $res->fetch_all(MYSQLI_ASSOC);
                        foreach ($thisweekactions as $i => $thisaction) {
                            $newactions[$i] = $thisaction;
                        }
                    }
                    foreach ($newactions as $i => $newaction) {
                        ?>
                        <task>
                            <h5>Task <?php echo $i + 1; ?></h5>
                            <label>Describe the action:</label>
                            <label><textarea name="actionwhat<?php echo $newaction["id"]; ?>"><?php echo $newaction["what"]; ?></textarea></label>
                            <label>Before ..., during ..., after ...:</label>
                            <label><textarea name="actionduring<?php echo $newaction["id"]; ?>"><?php echo $newaction["during"]; ?></textarea></label>
                            <label>Where will the action take place?</label>
                            <label><textarea name="actionlocation<?php echo $newaction["id"]; ?>"><?php echo $newaction["location"]; ?></textarea></label>
                            <label>This action will take place <input type="number" min="1" max="99" name="actionperday<?php echo $newaction["id"]; ?>" value="<?php echo $newaction["perday"]; ?>"> times per day, and <input type="number" min="1" max="6" name="actionperweek<?php echo $newaction["id"]; ?>" value="<?php echo $newaction["perweek"]; ?>"> days per week.</label>
                        </task>
                        <?php
                    }
                }
                ?>
                <label>Comment:</label>
                <label><textarea name="comment"><?php echo $lastwag["comment"]; ?></textarea></label>
                <input type="Submit" value="Report">
            </form>
            <?php
        }

        function login() {
            ?>
            <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                Name:<input type='text' maxlength="20" name='teacher' placeholder='English Name'>
                <button type="submit">GO ></button>
            </form>
            <?php
        }

        if (empty($_POST["teacher"])) {
            login();
        } else if (!empty($_POST["teacher"])) {
            require_once "UMLCdatalogin.php";
            $sql = "SELECT * FROM teacher WHERE name = '" . $_POST["teacher"] . "'";
            $result = $DB->query($sql);
            // SELECT * FROM Teacher WHERE name = 'andy'
            $result_array = $result->fetch_all(MYSQLI_ASSOC);
            // if the teacher is in the DB
            if (count($result_array) > 0) {
                $teacher_id = $result_array[0]["id"];
                //teacher logged in, show real page
                show();
            } else {
                login();
            }
        }
        ?>
    </body>
</html>