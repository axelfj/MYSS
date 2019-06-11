<?php
require_once "UserQuery.php";
require_once "../Controller/Controller.php";

$controller = new Controller();
$SESSION["key"] = 565498;
$result = $controller->filterUsername("user");

var_dump($result);


