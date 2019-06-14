<?php
session_start(); // To ensure if we are using same session.
session_destroy();
try{unset($_SESSION['userToken']);}
catch (Exception $e){}
header("Location: " . "login.php");
exit();
