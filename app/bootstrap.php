<?php
//load all routes

use App\Core\Database;

require_once(__DIR__ . "/route.php");

//load config
require_once(__DIR__ . "/config.php");

//view loader
function view($name)
{
    //directory can be accessed by "."
    str_replace('.', '/', $name);

    if (file_exists(__DIR__ . "/Views/" . $name . ".php"))
        require_once(__DIR__ . "/Views/" . $name . ".php");
    else {
        header("http/1.0 404 Not Found");
        die('View not found');
    }
}

$database = new Database($config['database']);

var_dump($database->select(['name', 'email'])->from('users')->where('id', 1)->get());
