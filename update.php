<?php

require_once("includes/models/photo.php");

$iPhotoID = $_POST["photoID"];

$oPhoto = new Photo();
$oPhoto->load($iPhotoID);
$iCount = count($oPhoto->likes);
echo json_encode($iCount);
?>
