<?php
ob_start();
session_start();

require_once("includes/template/head.php");

require_once("includes/models/user.php");

require_once("includes/views/view.php");

require_once("includes/models/photo.php");

require_once("includes/models/form.php");

require_once("includes/models/album.php");

require_once("includes/models/contestmanager.php");


$oView = new View();

$oUser = new User();

$oForm = new Form();

$oContest = new Contest();

$oContestManager = new ContestManager();

if(!isset($_SESSION["UserID"])){    
    header("Refresh: 0; URL=index.php");
    exit;
}

if(isset($_POST["submit"])){
    
    $oForm->data = $_POST;
    $oForm->files = $_FILES;
    
    $oForm->checkRequired("title");
    $oForm->checkRequired("theme");
    $oForm->checkFileUploaded("file");
    
    if($oForm->valid){
        $newName = "assets/images/".$oUser->username."".time().".jpg";
        
        $oForm->moveFile("file", $newName);
        
        $oContest->userID = $_SESSION["UserID"];
        $oContest->contestName = $_POST["title"];
        $oContest->theme = $_POST["theme"];
        $oContest->photoPath = $newName;
        $oContest->active = 1;
        
        $oContest->save();
                
        $oAlbum = new Album();
        
        $oAlbum->userID = $_SESSION["UserID"];
        $oAlbum->albumName = $_POST["title"];
        $oAlbum->caption = $_POST["theme"];
        $oAlbum->photoPath = $newName;
        $oAlbum->active = 1;
        $oAlbum->contestID = $oContest->contestID;
        
        $oAlbum->save();   
        
        header("Refresh: 0; contests.php");
        exit;
        
    }
    
}


$oUser->load($_SESSION["UserID"]);

echo $oView::renderNav($oUser);

echo $oView::renderUserDetails($oUser);

$aAllContest = $oContestManager::all();

$oForm->makeTextInput("Title", "title");
$oForm->makeDate("Start Date", "start_date");
$oForm->makeDate("End Date", "end_date");
$oForm->makeTextArea("Theme", "theme");
$oForm->makeFileInput("file", "File");
$oForm->makeSubmit("Add Contest");


echo $oView::renderContests(array_reverse($aAllContest), $oForm->html);

require_once("includes/template/foot.php");
?>