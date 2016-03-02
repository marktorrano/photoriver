<?php
session_start();

require_once("includes/models/comment.php");

require_once("includes/models/user.php");
 
if(!isset($_SESSION["UserID"])){
    header("Refresh: 0; URL=index.php");
}
if(isset($_POST["comment"]) && isset($_POST["photoID"])){
    
    date_default_timezone_set('Pacific/Auckland');
    $current_date = date('d/m/Y == H:i:s');

    $oComment = new Comment();
    $oComment->comment = $_POST["comment"];
    $oComment->photoID = $_POST["photoID"];
    $oComment->userID = $_SESSION["UserID"];
    $oComment->dateCreated = $current_date;
    $oComment->save();

    $oUser = new User();
    $oUser->load($_SESSION["UserID"]);
    
    
    $aData = [$oUser->username, $oComment->comment, $oComment->commentID];

    echo json_encode($aData);

}

?>
