<?php
ob_start();

session_start();

require_once("includes/template/head.php");

require_once("includes/views/view.php");

require_once("includes/models/photomanager.php");

require_once("includes/models/user.php");

require_once("includes/models/form.php");

$oPhotoManager = new PhotoManager();

$aAllPhotos = $oPhotoManager->all();

$oView = new View();

$oUser = new User();

$oUser->load($_SESSION["UserID"]);

echo $oView::renderNav($oUser);

if(isset($_SESSION["UserID"])){
    echo '<div id="river-title"><h5>Recently Uploaded Photos</h5></div>';
}

echo $oView::renderAllPhotos(array_reverse($aAllPhotos));

require_once("includes/template/foot.php");
?>
