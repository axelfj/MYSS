<?php
require_once "UserQuery.php";

$result = UserQuery::searchFriends("user",439832);

var_dump($result);


