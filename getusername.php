<?php
session_start();

require_once("includes/models/user.php");

$oUser = new User();
$oUser->load($_SESSION["UserID"]);


echo $oUser->username;


?>