<?php

// this function will automatically include the files for the classes we want to use
// a class in a namespace looks like this
// models\DB
function autoload($class){
    // so we convert the class name to a filepath
    // by replacing backslashes with forward slashes
    // because on linux file paths use forward slashes
    // then we add .class.php to the end
    // so if $class = models\DB
    // then $path = models/DB.class.php
    $path = str_replace("\\", "/", $class) . ".class.php";
    // here we include the file in our script
    require_once $path;
}
// this tells PHP to use our function whenever it can't find the class we are
// trying to use
spl_autoload_register('autoload');


// so here we can use a class we haven't specifically included
// and PHP will use our function to automatically include the file where it is
// defined

$db = new models\DB();
// here we will create a simple autoloader for controllers
echo var_dump($_SERVER["REQUEST_URI"]);
unset($db);