<?php
require_once "UserQuery.php";

$result = UserQuery::getUsersStartingWith("Y");

var_dump($result);


