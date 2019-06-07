<?php
require_once "PostQuery.php";

$result = PostQuery::getPostByText("post");

var_dump($result);


