<?php
session_start();

require_once("includes/models/album.php");

require_once("includes/models/form.php");

if(!isset($_SESSION["UserID"])){
    header("Refresh: 0; URL=index.php");
}
$oForm = new Form();

$oAlbum = new Album();

if($oForm->valid){
    $oAlbum->userID = $_SESSION["UserID"];
    $oAlbum->albumName = $_POST["album_name"];
    $oAlbum->caption = "";
    $oAlbum->photoPath = "assets/images/icon-image.png";
    $oAlbum->active = 1;

    $oAlbum->save();    

    echo $oAlbum->albumID;
}

?>