<?php
ob_start();
session_start();

require_once("includes/template/head.php");

require_once("includes/models/user.php");

require_once("includes/models/form.php");

require_once("includes/views/view.php");

require_once("includes/models/photo.php");

require_once("includes/models/album.php");

require_once("includes/models/like.php");

if(!isset($_SESSION["UserID"])){
    
    header("Refresh: 0; URL=index.php");
    exit;
}

$iAlbumID = $_GET["albumID"];

$oUser = new User();

$oView = new View();

$oForm = new Form();

echo $oView::renderNav($oUser);

if(isset($_GET["albumID"])){
    $oAlbum = new Album();
    $oAlbum->load($_GET["albumID"]);
    $aAllPhotos = $oAlbum->photos;    
}

if(isset($_POST["submit"])){
    
    date_default_timezone_set('Pacific/Auckland');
    $current_date = date('d/m/Y == H:i:s');
    
    $oForm->data = $_POST;
    $oForm->files = $_FILES;

    $oForm->checkFileUploaded("file");
    
    if($oForm->valid){
        
        $newName = "assets/images/".$oUser->username."".time().".jpg";
        
        $oForm->moveFile("file", $newName);
        
        $oPhoto = new Photo();
        
        $oPhoto->albumID = $_GET["albumID"];
        $oPhoto->userID = $_SESSION["UserID"];
        $oPhoto->contestID = $oAlbum->contestID;
        $oPhoto->photoPath = $newName;
        $oPhoto->dateUploaded = $current_date;
        $oPhoto->caption = "";
        $oPhoto->active = 1;
        
        $oPhoto->save();
        
        header("Refresh: 1; entries.php?albumID=".$_GET["albumID"]."");
        exit;
        
    }
}

echo $oView::renderPhotoStreamUserProfile(array_reverse($aAllPhotos));

$oForm->makeFileInput("file", "File");
$oForm->makeSubmit("Upload");

echo $oView::renderModalUpload(0, $oForm->html);

require_once("includes/template/foot.php");

?>