<!DOCTYPE html>
<html>
    <head>
        <title>All Tasks</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            span {
                display: inline-block;
                padding: 5px;
                width: 300px;
            }
            div:nth-child(odd) {
                background-color: rgba(0, 0, 0, 0.1);
            }
        </style>
    </head>
    <body>
        <?php
        require_once 'UMLCdatalogin.php';

        $sql = "SELECT teacher.name, tasks.task, tasks.level FROM tasks JOIN teacher ON(tasks.teacher_id = teacher.id) ORDER BY teacher.name, tasks.level ASC";

        $tasks = $DB->query($sql);

        $task_rows = $tasks->fetch_all(MYSQLI_ASSOC);

        foreach ($task_rows as $task) {
            echo "<div>";
            foreach ($task as $field => $value) {
                echo "<span>" . $field . ": " . $value . "</span>";
            }
            echo "</div>";
        }
        ?>


    </body>
</html>
