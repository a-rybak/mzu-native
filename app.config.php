<?php
session_start();

defined('DB_SERVER') or define('DB_SERVER', 'localhost');
defined('DB_USERNAME') or define('DB_USERNAME', 'root');
defined('DB_PASSWORD') or define('DB_PASSWORD', '');
defined('DB_NAME') or define('DB_NAME', 'mzu');

require_once "measure.php";

$_SESSION['pageSize'] = 10; //records on page
$_SESSION['page'] = 1; //default page

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

if(false === $link){
    die("ERROR: Couldn't connect to MySQL server. " . mysqli_connect_error());
}

require_once "setup.php";