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
            //get latest wag for wis from teacher
            $res = $DB->query("SELECT * FROM wagwam WHERE wis = '" . $wis["id"] . "' AND teacher = '" . $teacher_id . "' ORDER BY id DESC LIMIT 1");
            $lastwag = $res->fetch_all(MYSQLI_ASSOC)[0];
            // which week is lastwag for?
            $res = $DB->query("SELECT * FROM class WHERE id = '" . $lastwag["class"] . "' LIMIT 1");
            $class = $res->fetch_all(MYSQLI_ASSOC)[0];
            $res = $DB->query("SELECT c.id, c.name FROM teacher_class tc JOIN class c ON(tc.class = c.id) WHERE tc.teacher = '" . $teacher_id . "'");
            $teacher_classes = $res->fetch_all(MYSQLI_ASSOC);
            ?>
            <form action="WAGWAMinsert.php" method="post">
                <h1><?php echo $wis["statement"]; ?></h1>
                <h2>4DX Weekly Action Goal (WAG)</h2>
                <h3>Week <?php echo $week + 1; ?> of <?php echo $number_of_weeks; ?> weeks</h3>
                <?php if ($week > 0) { ?>
                    <h4>Last Week:</h4>
                    <label>Did you attend the Weekly Accountability Meeting (WAM)? <input type="radio" name="attend" value="1">Yes! <input type="radio" name="attend" value="0" checked>No</label>
                    <label>Did you complete your WAG? <input type="radio" name="complete" value="1">Yes! <input type="radio" name="complete" value="0" checked>No</label>
                    <label>Did you modify the scoreboard to reflect your progress? <input type="radio" name="modified_score" value="1">Yes!<input type="radio" name="modified_score" value="0" checked>No</label>
                    <!-- shown when week# is % 3 = 0, when week# < totalWeeks: -->
                    <?php if (($week + 1) % 3 === 0 && ($week + 1) < $number_of_weeks) { ?>
                        <label>With <?php echo $number_of_weeks - $week - 1; ?> weeks left to "<?php echo $wis["wisl" . $class["level"]]; ?>" I'm feeling...</label>
                        <label>
                            <select name="emotion">
                                <option value="0">-- Choose One --</option>
                                <option value="3">we may surpass our goal</option>
                                <option value="2">confident we will make our goal</option>
                                <option value="1">behind, but hopeful we can reach our goal</option>
                                <option value="0">behind, and doubtful we can reach our goal</option>
                            </select>
                        </label>
                    <?php } ?>
                    <?php if (($week + 1) === $number_of_weeks) { ?>
                        <!-- Appears last week --><label>I attained our wildly important standard: <input type="radio" name="wis_met" value="1">Yes!<input type="radio" name="wis_met" value="0" checked>No</label>
                    <?php } ?>
                    <!-- Repeat following two fields for each action (1-3) giving action 'what' from wag_action -->
                    <label>Give Evidence that WAG leveraged WIS:</label><label><textarea name="evidence"></textarea></label>
                    <label>How could WAG have been better?</label><label><textarea name="improve"></textarea></label>
                <?php
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
                        ?><input type="hidden" name="class" value="<?php echo $teacher_classes[0]["id"]; ?>"><?php }
                    ?>
                    <task>
                        <label>Describe the action:</label>
                        <label><textarea name="what"></textarea></label>
                        <label>Before ..., during ..., after ...:</label>
                        <label><textarea name="when"></textarea></label>
                        <label>Where will the action take place?</label>
                        <label><textarea name="location"></textarea></label>
                        <label>This action will take place <input type="number" min="1" max="99" name="perday"> times per day, and <input type="number" min="1" max="6" name="perweek"> days per week.</label>
                    </task>
                    <label><button type="button" onclick="document.getElementById('two').style.display = 'block';">+ Action</button></label>
                    <task id="two">
                        <label>Describe the action:</label>
                        <label><textarea name="what"></textarea></label>
                        <label>Before ..., during ..., after ...:</label>
                        <label><textarea name="when"></textarea></label>
                        <label>Where will the action take place?</label>
                        <label><textarea name="location"></textarea></label>
                        <label>This action will take place <input type="number" min="1" max="99" name="perday"> times per day, and <input type="number" min="1" max="6" name="perweek"> days per week.</label>
                    </task>
                    <label><button type="button" onclick="document.getElementById('three').style.display = 'block';">+ Action</button></label>
                    <task id="three">
                        <label>Describe the action:</label>
                        <label><textarea name="what"></textarea></label>
                        <label>Before ..., during ..., after ...:</label>
                        <label><textarea name="when"></textarea></label>
                        <label>Where will the action take place?</label>
                        <label><textarea name="location"></textarea></label>
                        <label>This action will take place <input type="number" min="1" max="99" name="perday"> times per day, and <input type="number" min="1" max="6" name="perweek"> days per week.</label>
                        <!-- when wanting to deal with total times, SELECT (perday * perweek) AS times FROM wag_action -->
                    </task>
    <?php } ?>
                <label>Comment:</label>
                <label><textarea name="comment"></textarea></label>
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