<?php
require "../include/header.php";

session_start();
session_unset();
session_destroy();
header("location:../mainPage/index.php");

require "../include/footer.php";
?>