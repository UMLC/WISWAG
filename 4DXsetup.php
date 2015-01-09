<!DOCTYPE html>
<html>
    <head>
        <title>4DX WIS</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php
        require_once 'UMLCdatalogin.php';
        $vals = [
            "statement" => "",
            "explanation" => "",
            "wisl1" => "",
            "wisl2" => "",
            "wisl3" => "",
            "wisl4" => "",
            "dates" => [""],
            "sbrperson" => "0",
            "sbcompleted" => "",
            "wagformready" => "0",
            "formative" => "",
            "summative" => "",
            "notes" => ""
        ];
        if (empty($_POST["new_wis"])) {
            // get latest WIS info from database to fill fields
            $wis = $DB->query("SELECT * FROM wis ORDER BY id DESC LIMIT 1");
            $wis = $wis->fetch_all(MYSQLI_ASSOC)[0];
            foreach ($vals as $key => $value) {
                $vals[$key] = $wis[$key];
            }
            $vals["dates"] = explode(",", $vals["dates"]);
            ?>
            <form method="post" action="4DXsetup.php">
                <input type="submit" value="CREATE NEW WIS" name="new_wis">
            </form>
            <?php
        }
        ?>
        <form method="post" action="4DXsetupinsert.php">
            <h3>4DX Discipline One - Wildly Important Standard (WIS)</h3>
            <label>
                <div>What is the simple, short, and general Wildly Important Standard?</div>
                <textarea cols=100 rows="3" name="statement"><?php echo $vals["statement"]; ?></textarea>
            </label>
            <label>
                <div>Is there any explanation needed to better understand the WIS?</div>
                <textarea cols="100" rows="3" name="explanation"><?php echo $vals["explanation"]; ?></textarea>
            </label>
            <label>What is the short, and quantifiable WIS for each age level? (must include a number)</label>
            <label>Level 1: <input type="text" maxlength="140" size="100" name="wisl1" value="<?php echo $vals["wisl1"]; ?>"> (youngest)</label>
            <label>Level 2: <input type="text" maxlength="140" size="100" name="wisl2" value="<?php echo $vals["wisl2"]; ?>"></label>
            <label>Level 3: <input type="text" maxlength="140" size="100" name="wisl3" value="<?php echo $vals["wisl3"]; ?>"></label>
            <label>Level 4: <input type="text" maxlength="140" size="100" name="wisl4" value="<?php echo $vals["wisl4"]; ?>"> (oldest)</label>
            <label>This WIS focus will last <input type="number" maxlength="2" id="wis_length" value="<?php echo count($vals["dates"]); ?>"> weeks.</label>
            <label>
                <select id="dateselect" name="dates[]" size="20" multiple>
                    <?php
                    $today = new DateTime();
                    $today->setTimezone(new DateTimeZone('Asia/Chongqing'));
                    while ($today->format("D") !== "Mon") {
                        $today->add(new DateInterval("P1D"));
                    }
                    if(!empty($vals["dates"][0])){
                        // set $today = $vals["dates"][0]
                        echo $vals["dates"][0];
                        $today = DateTime::createFromFormat('Y-m-d', $vals["dates"][0], new DateTimeZone('Asia/Chongqing'));
                    }
                    $today->sub(new DateInterval("P7D"));
                    for ($i = 0; $i < 25; $i++) {
                        $today->add(new DateInterval("P7D"));
                        ?>
                        <option value="<?php $sqldate = $today->format("Y-m-d"); echo $sqldate; ?>"<?php echo in_array($sqldate, $vals["dates"]) ? " selected" : ""; ?>><?php echo $today->format("D, d M Y"); ?></option>
                        <?php
                    }
                    ?>
                </select>
            </label>
            <h3>4DX Discipline Two - Scoreboard</h3>
            <label>Who is responsible for the scoreboard? <select name="sbrperson">
                    <option value="0">-- Choose --</option>
                    <?php
                    $teacher_data = $DB->query("SELECT * FROM teacher ORDER BY name ASC");
                    $teachers = $teacher_data->fetch_all(MYSQLI_ASSOC);
                    foreach ($teachers as $teacher) {
                        ?>
                        <option value="<?php echo $teacher["id"]; ?>" <?php
                        if (intval($teacher["id"]) === intval($vals["sbrperson"])) {
                            echo "selected";
                        }
                        ?>><?php echo $teacher["name"]; ?></option>
                                <?php
                            }
                            ?>
                </select></label>
            <label>When will the scoreboard be completed? <select id="weekday">
                    <?php
                    // write php to get weekday and weeks-from-now using $vals["sbcompleted"]
                    ?>
                    <option value="0">Sunday</option>
                    <option value="1">Monday</option>
                    <option value="2">Tuesday</option>
                    <option value="3">Wednesday</option>
                    <option value="4">Thursday</option>
                    <option value="5">Friday</option>
                    <option value="6">Saturday</option>
                </select>, <input id="weeks_from_now" type="number" value="1"> week(s) from now.</label>
            <label><span id="days_date"></span><input name="sbcompleted" type="hidden"></label>
            <a href="scoreboard.html"><button type="button">Link to Scoreboard</button></a>

            <h3>4DX Discipline Three - Weekly Action Goal (WAG)</h3>

            <label>Is the Weekly Action Goal Form ready?</label>
            <label><input type="radio" name="wagformready" value="1" <?php
                if (intval($vals["wagformready"]) === 1) {
                    echo "checked";
                }
                ?>> Yes <input type="radio" name="wagformready" value="0" <?php
                          if (intval($vals["wagformready"]) === 0) {
                              echo "checked";
                          }
                          ?>> No</label>
            <a href="WAGWAM.html"><button type="button">Link to WAG Form</button></a>
            <h3>4DX Discipline Four - Weekly Accountability Meetings (WAM)</h3>
            <label><div>Describe the formative assessment process.</div>
                <textarea maxlength="300" name="formative" rows="3" cols="100" placeholder="Enter evaluation process here..."><?php echo $vals["formative"]; ?></textarea></label>
            <label><div>Describe the summative assessment process.</div>
                <textarea maxlength="300" name="summative" rows="3" cols="100" placeholder="Enter evaluation process here..."><?php echo $vals["summative"]; ?></textarea></label>
            <label><div>Is there any other information that would be good to capture at this time that might be important for understanding, developing or evaluating this WIS?</div> 
                <textarea maxlength="300" name="notes" rows="3" cols="100" placeholder="Enter your information here..."><?php echo $vals["notes"]; ?></textarea></label>
            <input type="Submit" value="I'm Done!">
        </form>
        <script>
            var selector = document.getElementById("dateselect");
            function setWisLength() {
                var wisLengthBox = document.getElementById("wis_length");
                wisLengthBox.value = selector.selectedOptions.length;
            }
            setWisLength();
            selector.addEventListener("click", function () {
                setWisLength();
            });
            function updateDaysDate() {
                var weekday = document.getElementById("weekday").selectedOptions[0].value;
                var weeks = document.getElementById("weeks_from_now").value;
                var date1 = new Date();
                date1.setHours(0, 0, 0, 0);
                var date = new Date(date1.getTime());
                var nth = date.getDate();
                var sunday = nth - date.getDay();
                var plusweeks = sunday + (7 * weeks);
                var realday = plusweeks + parseInt(weekday, 10);
                date.setDate(realday);
                var days = (date.getTime() - date1.getTime()) / (1000 * 60 * 60 * 24);
                var el = document.getElementById("days_date");
                var hiddenInput = document.getElementsByName("sbcompleted")[0];
                el.innerHTML = date.toDateString() + ", " + days + " day(s) from today.";
                hiddenInput.value = date.toISOString().split("T")[0];
            }
            updateDaysDate();
            var weekday = document.getElementById("weekday");
            var weeks = document.getElementById("weeks_from_now");
            weekday.addEventListener("change", function () {
                updateDaysDate();
            });
            weeks.addEventListener("change", function () {
                updateDaysDate();
            });
        </script>
    </body>
</html>