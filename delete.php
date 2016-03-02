<?php
ob_start();
session_start();

require_once("includes/models/photo.php");
require_once("includes/models/album.php");
require_once("includes/models/contest.php");

if(!isset($_SESSION["UserID"])){
    
    header("Refresh: 0; URL=userprofile.php");
    exit;
}

if(isset($_GET["photoID"])){
    
    $oPhoto = new Photo();    
    $oPhoto->load($_GET["photoID"]);
    
    $oPhoto->active = 0;
    $oPhoto->save();
    
    header("Refresh: 0; URL=userprofile.php");
    exit;
}

if(isset($_GET["albumID"])){  
    
    $oAlbum = new Album();
    $oAlbum->load($_GET["albumID"]);
    
    if($oAlbum->albumName == "Default"){
        echo "Cannot delete Default album.";
        header("Refresh: 0; URL=userprofile.php");
        exit;
        
    }else{
        $oAlbum->active = 0;
        if($oAlbum->contestID != 0){            
            $oContest = new Contest();
            $oContest->load($oAlbum->contestID);
            $oContest->active = 0;
            $oContest->save();
        }
        $oAlbum->save();

        header("Refresh: 0; URL=userprofile.php");
        exit;
    }
    
    
}

if(isset($_GET["commentID"])){
    
    $oComment = new Comment();
    $oComment->load($_GET["commentID"]);
    
    $oComment->active = 0;
    $oComment->save();
    
    header("Refresh: 0; URL=userprofile.php");
    exit;
    
}

?>