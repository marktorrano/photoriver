<?php
session_start();

require_once("includes/models/like.php");

require_once("includes/models/photo.php");

if(!isset($_SESSION["UserID"])){
    header("Refresh: 0; URL=index.php");
}

//echo "photoID is " . $_GET["photoID"];
//echo $_SESSION["UserID"]; 

$oLike = new Like();
$oPhoto = new Photo();
if(isset($_GET["photoID"]) && $oLike->loadByPhotoID($_GET["photoID"], $_SESSION["UserID"])){
    if($oLike->active){
        $oLike->active = 0;
        $oLike->save(); 
        
        $aData[] = 0;
    }else{
        $oLike->active = 1;
        $oLike->save(); 
        
        $aData[] = 1;
    }
    
    $oPhoto->load($_GET["photoID"]);
    
    $aData[] = count($oPhoto->likes);
    echo json_encode($aData);
    
//    echo count($oPhoto->likes);
}else{
    $oLike->photoID = $_GET["photoID"];
    $oLike->userID = $_SESSION["UserID"]; 
    $oLike->active = 1;
    $oLike->save();
    
    $oPhoto->load($_GET["photoID"]);
    $aData = [0, count($oPhoto->likes)];
    echo json_encode($aData);
 
//    echo count($oPhoto->likes);
}

?>