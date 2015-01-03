<pre>
<?php

require_once 'UMLCdatalogin';

$sql = "SELECT * FROM tasks JOIN teacher ON(tasks.teacher_id = teacher.id) ORDER BY teacher.name, tasks.level ASC";

$tasks = $DB->query($sql);

$task_rows = $tasks->fetch_all(MYSQLI_ASSOC);

echo var_dump($task_rows);

?>
</pre>