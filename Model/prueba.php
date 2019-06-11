<?php
require_once "UserQuery.php";
require_once "../Controller/Controller.php";

$controller = new Controller();
$_SESSION["userKey"] = 608812;
$result = $controller->filterUsername("usuario");

var_dump($result);


