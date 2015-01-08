<!DOCTYPE html>
<html>
    <head>
        <title>4DX WIS</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <form method="post" action="4DXsetupinsert.php">
            <h3>4DX Discipline One - Wildly Important Standard (WIS)</h3>
            <!--This WIS statement is the most general statement and applies to everyone.-->
            <!--I would like to have this statement appear on the top of the WAGWAM form-->
            <!--insert $statement in table WIS (text 100)-->
            <label>What is the simple, short, and general Wildly Important Standard?</label>

            <!--
            
            1. get the number of completed wis weeks
            2. if the wis isn't over (weeks haven;t been completed)
            3. edit last wis
            4. If the previous wis was completed, create new wis
            
            -->
            <textarea name="statement"></textarea>
            <!--This is only for informational purposes. There are no plans at this point for printing.-->
            <!--insert $explanation into table WIS (textarea 255)-->
            <label>Is there any explanation needed to better understand the WIS?</label>
            <textarea name="explanation"></textarea>
            <label>What is the short, and quantifiable WIS for each age level? (must include a number)</label>
            <label>Level 1: <input type="text" maxlength="140" size="140" name="WISL1"> (youngest)</label>
            <label>Level 2: <input type="text" maxlength="140" size="140" name="WISL2"></label>
            <label>Level 3: <input type="text" maxlength="140" size="140" name="WISL3"></label>
            <label>Level 4: <input type="text" maxlength="140" size="140" name="WISL4"> (oldest)</label>
            <label>This WIS focus will last <input type="number" maxlength="2" id="wis_length" name="length" value="12"> weeks.</label>
            <label><select id="dateselect" size="20" multiple></select></label>
            <h3>4DX Discipline Two - Scoreboard</h3>

            <!--insert $sbrperson in table wis (text 20)
            $sbrperson will be selected and inserted into responsible person's WAG form-->
            <label>Who is responsible for the scoreboard? <select name="sbrperson">
                <option value="0">-- Choose --</option>
                <?php
                require_once 'UMLCdatalogin.php';
                $teacher_data = $DB->query("SELECT * FROM teacher ORDER BY name ASC");
                $teachers = $teacher_data->fetch_all(MYSQLI_ASSOC);
                foreach ($teachers as $teacher) {
                    ?>
                    <option value="<?php echo $teacher["id"]; ?>"><?php echo $teacher["name"]; ?></option>
                <?php } ?>
            </select></label>

            <!--insert $sbcompleted in Table wis (date) Due date would also come up in responsible person's WAG form
            $sbrperson WAG form with a radio button choice of incomplete / complete until it is complete. 
            If not complete at due date I would really like an Email sent to me.-->
            <label>When will the scoreboard be completed? <select id="weekday">
                <option value="sunday">Sunday</option>
                <option value="monday">Monday</option>
                <option value="tuesday">Tuesday</option>
                <option value="wednesday">Wednesday</option>
                <option value="thursday">Thursday</option>
                <option value="friday">Friday</option>
                <option value="saturday">Saturday</option>
            </select>, <input id="weeks_from_now" type="number" value="1"> week(s) from now.</label>
            <label><span id="days_date"></span></label>

            <!--Not sure if this code is right or not - just want an easy way to go to the scoreboard page if necessary.-->
            <a href="scoreboard.html"><button type="button">Link to Scoreboard</button></a>

            <h3>4DX Discipline Three - Weekly Action Goal (WAG)</h3>

            <label>Is the Weekly Action Goal Form ready?</label>
            <label><input type="radio" name="WAGready" value="yes"> Yes</label>
            <label><input type="radio" name="WAGready" value="no"> No</label>

            <!--This is just a link to go look at the WAGWAS page if needed.-->
            <a href="WAGWAM.html"><button type="button">Link to WAG Form</button></a>

            <h3>4DX Discipline Four - Weekly Accountability Meetings (WAM)</h3>

            <!--insert $start in table WIS (date)-->
            <!--insert $summative in table WIS (textarea 300)-->
            <label>Describe the summative assessment process.</label>
            <textarea maxlength="300" name="summative" rows="3" cols="100" placeholder="Enter evaluation process here..."></textarea>
            <!--insert $notes in Table WIS (textarea 300)-->
            <label>Is there any other information that would be good to capture at this time that might be important for understanding, developing or evaluating this WIS?</label> 
            <textarea maxlength="300" name="notes" rows="3" cols="100" placeholder="Enter your information here..."></textarea>
            <input type="Submit" value="I'm Done!">
        </form>
        <script>
            var selector = document.getElementById("dateselect");
            function baseDate() {
                var date = new Date();
                return new Date(date.getFullYear(), date.getMonth(), date.getDate());
            }
            var A_DAY = 1000 * 60 * 60 * 24;
            function day(which, wd) {
                if (typeof wd === 'undefined') {
                    wd = 'monday';
                }
                if (typeof which === 'undefined') {
                    which = 'this';
                }
                var wds = {
                    "monday": 1,
                    "tuesday": 2,
                    "wednesday": 3,
                    "thursday": 4,
                    "friday": 5,
                    "saturday": 6,
                    "sunday": 0
                };
                var whichday = wds[wd];
                var date = baseDate();
                var year = date.getFullYear();
                var month = date.getMonth();
                var day = (date.getDate() - date.getDay()) + whichday;
                var theday = new Date(year, month, day);
                var longAgo = date.getTime() - theday.getTime();
                if (which === 'next' && longAgo >= A_DAY) {
                    theday.setDate(day + 7);
                }
                return theday;
            }

            function getMondays() {
                var mondays = [];
                var monday = day('next', 'monday');
                for (var i = 0; i < 25; i++) {
                    mondays.push(monday.getTime() + (A_DAY * 7 * i));
                }
                return mondays;
            }
            function addOption(monday) {
                var dt = new Date(monday);
                var option = document.createElement("option");
                option.text = dt.toDateString();
                option.value = dt.getFullYear() + "-" + ("0" + (dt.getMonth() + 1)).slice(-2) + "-" + ("0" + dt.getDate()).slice(-2);
                selector.add(option);
            }
            getMondays().forEach(function (i) {
                addOption(i);
            });
            for (var i = 0; i < 12; i++) {
                selector.options[i].setAttribute("selected", "true");
            }
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
                var date = day('this', weekday);
                date = new Date(date.getTime() + (parseInt(weeks) * A_DAY * 7));
                var days = (date.getTime() - baseDate().getTime()) / A_DAY;
                var el = document.getElementById("days_date");
                el.innerHTML = date.toDateString() + ", " + days + " day(s) from today.";
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