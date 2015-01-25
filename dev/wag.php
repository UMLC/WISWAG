<!DOCTYPE html>
<html>
    <head>
        <title>4DX WAG Form</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <pre>
            <?php
            session_start();

            require_once 'models/db.class.php';
            require_once 'functions.php';

            $DB = get_connection();

            teacher_login();
            if (!empty($_SESSION["teacher_id"])) {
                $WIS = get_wis();
                $this_week = $WIS->week_number(date_now());
                echo $this_week;
            }
            ?>
        </pre>
    </body>
</html>