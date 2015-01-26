<?php

function third_week_form(){
    $form = "";
    if($week_number % 3 === 0 && $week_number < $number_of_weeks) {
        $weeks_left = $number_of_weeks - $week_number;
        $form .= <<<FORM
                        <label>With $weeks_left weeks left to "<?php echo $wis["wisl" . $class["level"]]; ?>" I'm feeling...</label>
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
                        </label>
FORM;
    }
    return $form;
}

function response_form(){
$form = "";
if ($lastWAG) {
    $attend_yes = $lastWAG->attend ? " checked" : "";
    $attend_no = $lastWAG->attend ? "" : " checked";
    $complete_yes = $lastWAG->complete ? " checked" : "";
    $complete_no = $lastWAG->complete ? "" : " checked";
    $modified_score_yes = $lastWAG->modified_score ? " checked" : "";
    $modified_score_no = $lastWAG->modified_score ? "" : " checked";
    $thirdweek = third_week_form();
$form .= <<<FORM
                <h4>Last Week:</h4>
                    <label>
                        Did you attend the Weekly Accountability Meeting (WAM)?
                        <input type="radio" name="attend" value="1"$attend_yes>Yes!
                        <input type="radio" name="attend" value="0"$attend_no>No
                    </label>
                    <label>
                        Did you complete your WAG?
                        <input type="radio" name="complete" value="1"$complete_yes>Yes!
                        <input type="radio" name="complete" value="0"$complete_no>No
                    </label>
                    <label>
                        Did you modify the scoreboard to reflect your progress?
                        <input type="radio" name="modified_score" value="1"$modified_score_yes>Yes!
                        <input type="radio" name="modified_score" value="0"$modified_score_no>No
                    </label>
                    $thirdweek
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
FORM;
}
return $form;
}

function show_form() {
echo <<<FORM
    <form action="WAGWAMinsert.php" method="post">
                <input type="hidden" name="teacher" value="<?php echo $teacher; ?>">
                <h1>{$WIS["statement"]}</h1>
                <h2>4DX Weekly Action Goal (WAG)</h2>
                <h3>Week $week_number of $number_of_weeks weeks</h3>
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
FORM;
}