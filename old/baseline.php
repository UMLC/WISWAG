<!DOCTYPE html>
<html>
    <head>
        <title>Baseline Form</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style type="text/css">
            * {
                font-family: sans-serif;
            }
            task {
                margin-top: 1em;
            }
            task, task num {
                display: block;
            }
            task desc {
                padding-left: 5em;
            }
            .cant {
                background-color: rgba(255, 0, 0, 0.2);
            }
            .does {
                background-color: rgba(0, 255, 0, 0.2);
            }
            .can {
                background-color: rgba(255, 255, 0, 0.4);
            }
            .train {
                background-color: rgba(255, 153, 0, 0.4);
            }
            task delete {
                float: right;
            }
        </style>
    </head>
    <h2>4DX Independence and Responsibility Focus</h2>
    <h3>Determining the Baseline</h3>
    <?php

    function login() {
        ?>
        <form method="post" action="baseline.php">
            Name:<input type='text' maxlength="25" name='teacher' placeholder='FirstEnglish FamilyPinyin'>
            <button type="submit">GO ></button>
        </form>
        <?php
    }

    if (!isset($_POST["teacher"])) {
        login();
    } else if (isset($_POST["teacher"])) {
        require_once "UMLCdatalogin.php";
        $sql = "SELECT * FROM teacher WHERE name = '" . $_POST["teacher"] . "'";
        $result = $DB->query($sql);
        // SELECT * FROM Teacher WHERE name = 'andy'
        $result_array = $result->fetch_all(MYSQLI_ASSOC);
        // if the teacher is in the DB
        if (count($result_array) > 0) {
            $teacher_id = $result_array[0]["id"];
            // if deletetask is set
            if(isset($_POST["deletetask"])){
            // delete given task
                $DB->query("DELETE FROM tasks WHERE id='" . $_POST["deletetask"] . "'");
            }
            // if task info is set
            if (isset($_POST["task"])) {
                // insert task info into the DB
                $task = $DB->escape_string($_POST["task"]);
                $level = $DB->escape_string($_POST["level"]);
                $sql = "INSERT INTO tasks (teacher_id, task, level) VALUES ('" . $teacher_id . "','" . $task . "','" . $level . "')";
                $DB->query($sql);
            }
            // check how many tasks are in the db
            $result = $DB->query("SELECT * FROM tasks WHERE teacher_id = '" . $teacher_id . "' ORDER BY id DESC");
            $result_array = $result->fetch_all(MYSQLI_ASSOC);
            $number = $result->num_rows;
            // return the number + 1 in order to use as ID for this task
            $task_id = $number + 1;
            if (!isset($_POST["continue"]) || $_POST["continue"] === "yes") {
                ?>
                Think about each and every activity and situation that takes place with your class.<br><br>
                Write a short description of the activity in the text-box.<br><br>
                Click on the radio button to categorize the task:
                <ul>
                    <li><b class="cant">Can't</b> = This task is inappropriate or not age appropriate.</li>
                    <li><b class="train">Train</b> = With training children could learn to do this task.</li>
                    <li><b class="can">Able</b> = Children are able, but most often the teacher does it.</li>
                    <li><b class="does">Does</b> = Children most often do this task.</li>
                </ul>
                <form method="post" action="baseline.php">
                    Task <?php echo $task_id; ?>
                    <input type="hidden" name="teacher" value="<?php echo $_POST["teacher"]; ?>">
                    <input type="text" maxlength="100" size="100" name="task">
                    <input type="radio" name="level" value="cant" /><b>Can't</b>
                    <input type="radio" name="level" value="train" /><b>Train</b>
                    <input type="radio" name="level" value="can" /><b>Able</b>
                    <input type="radio" name="level" value="does" /><b>Does</b>
                    <select name="continue">
                        <option value="yes">Next Task</option>
                        <option value="no">I'm done</option>
                    </select>
                    <button type="submit">GO</button>
                </form>
                <?php
            }
            $count = $number;
            foreach ($result_array as $task) {
                ?>
                <task class="<?php echo $task["level"]; ?>">
                    <num>Task <?php echo $count; ?></num>
                    <desc><?php echo $task["task"]; ?></desc>
                    <delete>
                        <form method="post" action="baseline.php">
                            <input type="hidden" name="teacher" value="<?php echo $_POST["teacher"]; ?>">
                            <input type="hidden" name="deletetask" value="<?php echo $task["id"]; ?>">
                            <button type="submit">X</button>
                        </form>
                    </delete>
                </task>
                <?php
                $count--;
            }
// if the teacher is not in the DB
        } else {
            echo "The name you entered does not appear in the database.";"\n"
            echo "Try your name again, as an example Cki Lan, or Andy Fang";"\n"
            echo "If you still can't login, send Martyn a WeChat";

            login();
        }
    }
    ?>
</html>