<!DOCTYPE html>
<html>
    <head>
        <title>Baseline Form</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <h2>4DX Independence and Responsibility Focus</h2>
    <h3>Determining the Baseline</h3>

<form method="post" action="baseline.php">
Name:<input type='text' maxlength="25" name='name_en' placeholder='FirstEnglish FamilyPinyin'>
<button type="submit">GO ></button>
</form>
<?php
function login(){
if(!isset($_POST["name_en"])){
?>
<?php
}
}

if(isset($_POST["name_en"])) {
    require_once "UMLCdatalogin.php";
    $result = $DB->query("SELECT * FROM Staff WHERE name_en = '" . $_POST["name_en"] . "'");
    // SELECT * FROM Staff WHERE name = 'andy'
    $result_array = $result->fetch_all();
    // if the teacher is in the DB
        if(count($result_array) > 0){
?>
        Think about each and every activity and situation that takes place with your class.<br><br>
        Write a short description of the activity in the text-box.<br><br>
        Click on the radio button to categorize the task:
        <ul>
            <li><b>Can't</b> = This task is inappropriate or not age appropriate.</li>
            <li><b>Train</b> = With training children could learn to do this task.</li>
            <li><b>Able</b> = Children are able, but most often the teacher does it.</li>
            <li><b>Does</b> = Children most often do this task.</li>
        </ul>
        <?php
        // check if $_POST continue or $_POST complete are set
        if(isset($_POST["task"])){
            // if set, insert task info into the DB
            $task = $DB->escape_string($_POST["task"]);
            $level = $DB->escape_string($_POST["level"]);
            $DB->query("INSERT INTO tasks(teacher_id, task, level) VALUES('" . $_POST["name_en"] . "','" . $task . "','" . $level . "')");
        }
        if($_POST["continue"] === "yes"){
        // check how many tasks are in the db
        $result = $DB->query("SELECT * FROM tasks WHERE staff_id = '" . $_POST["name_en"] . "' ORDER BY id DESC");
        $result_array = $result->fetch_all();
        $number = $result->num_rows;
        // return the number + 1 in order to use as ID for this task
        $task_id = $number + 1;
        ?>
        <form method="post" action="baseline.php">
        Task <?php echo $task_id; ?>
            <input type="hidden" name="name_en" value="<?php echo $_POST["name_en"]; ?>">
            <input type="text" maxlength="100" size="100" name="task">
            <input type="radio" name="level" value="can't" /><b>Can't</b>
            <input type="radio" name="level" value="train" /><b>Training</b>
            <input type="radio" name="level" value="can" /><b>Able</b>
            <input type="radio" name="level" value="does" /><b>Does</b>
            <select name="continue">
            <option value="yes">Continue</option>
            <option value="no">Completed</option>
            </select>
            <button type="submit">GO</button>
        </form>
<?php
$count = $number;
foreach($result_array as $task){
    ?>
    <div>Number: <?php echo $count; ?></div>
    <div>Task: <?php echo $task["task"]; ?></div>
    <div>Level: <?php echo $task["level"]; ?></div>
    <?php
    $count--;
}
}
// if the teacher is not in the DB
} else {
    echo "The name you entered is not in the database :(";<br> 
    echo "Make sure to enter your English name, then space, then Chinese Family name using pinyin";<br>
    echo "Here are a couple of examples, Cki Lan, or Andy Fang";
    login();
}
}
?>
</html>