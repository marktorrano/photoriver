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

$oForm = new Form;

$oView = new View();

$oFormUploadProfilePic = new Form();

$oUser = new User();

$oUser->load($_SESSION["UserID"]);

if(isset($_POST["submit"]) && isset($_POST["albumID"])){
    
    date_default_timezone_set('Pacific/Auckland');
    $current_date = date('d/m/Y == H:i:s');

    $oForm->data = $_POST;
    $oForm->files = $_FILES;
    
    $oForm->checkFileUploaded("file");
    
    if($oForm->valid){
        $newName = "assets/images/".$oUser->username."".time().".jpg";
        
        $oForm->moveFile("file", $newName);
        
        $oPhoto = new Photo();
        
        $aAlbums =  $oUser->albums;
        
        if(!isset($_POST["albumID"])){
            $oPhoto->albumID =  $aAlbums[0]->albumID;
        }else{
            $oPhoto->albumID = $_POST["albumID"];
        }
        $oPhoto->userID = $oUser->userID;
        $oPhoto->contestID = 1;
        $oPhoto->photoPath = $newName;
        $oPhoto->caption = $_POST["caption"];
        $oPhoto->dateUploaded = $current_date;
        $oPhoto->active = 1;        
                
        $oPhoto->save();
    
        header("Refresh: 0; userprofile.php");
        exit;
        
    }
    
}



if(isset($_POST["submit"])){
    
    $oFormUploadProfilePic->files = $_FILES;
    
    $oFormUploadProfilePic->checkFileUploaded("file");
    
    if($oForm->valid){
        $newName = "assets/images/".$oUser->username."".time().".jpg";
        
        $oFormUploadProfilePic->moveFile("file", $newName);
        
        $oUser->photoPath = $newName;
        $oUser->save();
        
        echo "<h1>Saving profile pic</h1>";
        
        header("Refresh: 0; URL=userprofile.php");
        exit;
    }
}

$oAlbum = new Album();
if(!isset($_GET["albumID"])){
    $currentAlbumID = $oAlbum->albumID;
}else{
    $currentAlbumID = $_GET["albumID"];
}

echo $oView::renderNav($oUser);

echo $oView::renderUserDetails($oUser);

$oAlbum->loadByUserID($_SESSION["UserID"]);
$aAllAlbums = $oAlbum->albums;

foreach($aAllAlbums as $aAlbumID => $iAlbumID){
    $oAlbumNames = new Album();
    $oAlbumNames->load($iAlbumID);
    $aAllAlbumNames[] = $oAlbumNames->albumName;
}

$oForm->makeFileInput("file", "File");
$oForm->makeTextInput("Caption", "caption");
$oForm->makeSelect("Albums", "albumID", $aAllAlbums, $aAllAlbumNames, $currentAlbumID);
$oForm->makeSubmit("Upload");

echo $oView::renderPhotoAlbumNav($aAllAlbums, $currentAlbumID);

$oFormUploadProfilePic->makeFileInput("file", "File");
$oFormUploadProfilePic->makeSubmit("Upload");

echo $oView::renderModalUploadProfilePicture($_SESSION["UserID"], $oFormUploadProfilePic->html);

echo $oView::renderModalUpload(0, $oForm->html);

if(isset($_GET["albumID"])){
    $oAlbum = new Album();
    $oAlbum->load($_GET["albumID"]);
    $aAllPhotos = $oAlbum->photos;
}else{
    $aAllPhotos = $oUser->photos;
}

echo $oView::renderPhotoStreamUserProfile(array_reverse($aAllPhotos));

require_once("includes/template/foot.php");