<?php
session_start();

require_once("includes/models/like.php");

require_once("includes/models/photo.php");

if(!isset($_SESSION["UserID"])){
    header("Refresh: 0; URL=index.php");
}

$oLike = new Like();
$oPhoto = new Photo();

$oPhoto->load($_GET["photoID"]);

$bResult = $oLike->loadByPhotoID($_GET["photoID"], $_SESSION["UserID"]);

if($bResult && $oLike->active){
    $aData[] = count($oPhoto->likes);
    $aData[] = 1;
}else{
    $aData[] = count($oPhoto->likes);
    $aData[] = 0;
}

echo json_encode($aData);
