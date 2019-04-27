<?php
session_start(); // To ensure if we are using same session.
session_destroy();
header("Location: " . "login.php");
exit();
