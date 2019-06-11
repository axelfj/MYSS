<?php
include_once "Controller.php";
$con = new Controller();
$cursor = $con->getPosts("YValle", "Public");

var_dump($cursor);