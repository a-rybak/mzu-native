<?php
session_start();

// define connection constants
defined('DB_SERVER') or define('DB_SERVER', 'localhost');
defined('DB_USERNAME') or define('DB_USERNAME', 'root');
defined('DB_PASSWORD') or define('DB_PASSWORD', '');
defined('DB_NAME') or define('DB_NAME', 'mzu');

require_once "measure.php";

// set default pagination parameters
$_SESSION['pageSize'] = 10; //records on page
$_SESSION['page'] = 1; //default page

//connect to DB
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

if(false === $link){
    die("ERROR: Couldn't connect to MySQL server. " . mysqli_connect_error());
}

// setting up a db (create, check and fill data)
require_once "setup.php";