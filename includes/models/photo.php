<?php

require_once("connection.php");
require_once("comment.php");
require_once("like.php");

class Photo{
    
    private $iPhotoID;
    private $iAlbumID;
    private $iUserID;
    private $iContestID;
    private $sPhotoPath;
    private $sCaption;
    private $dateDateUploaded;
    private $bActive;
    
    private $aComments;
    private $aLikes;
    private $aPhotos;
    
    public function __construct(){
        
        $this->iPhotoID = 0;
        $this->iAlbumID = 0;
        $this->iUserID = 0;
        $this->iContestID = 0;
        $this->sPhotoPath = "";
        $this->sCaption = "";
        $this->dateDateUploaded = new DateTime();
        $this->bActive = true;
        
        $this->aComments = array();
        $this->aLikes = array();
        $this->aPhotos = array();
                
    }
    
    public function __get($var){
        
        switch($var){
                
            case 'photoID':
                return $this->iPhotoID;
                break;
                
            case 'albumID':
                return $this->iAlbumID;
                break;
                
            case 'userID':
                return $this->iUserID;
                break;
                
            case 'contestID':
                return $this->iContestID;
                break;
                
            case 'photoPath':
                return $this->sPhotoPath;
                break;
                
            case 'caption':
                return $this->sCaption;
                break;
                
            case 'dateUploaded':
                return $this->dateDateUploaded;
                break;
                
            case 'active':
                return $this->bActive;
                break;
                
            case 'comments':
                return $this->aComments;
                break;
                
            case 'likes':
                return $this->aLikes;
                break;
                
            case 'photos':
                return $this->aPhotos;
                break;
                
            default:
                die($var . " is not allowed");
                break;
                
        }
        
    }
        
    public function __set($var, $value){
        
        switch($var){
                
            case 'photoID':
                $this->iPhotoID = $value;
                break;
                
            case 'albumID':
                $this->iAlbumID = $value;
                break;
                
            case 'userID':
                $this->iUserID = $value;
                break;
                
            case 'contestID':
                $this->iContestID = $value;
                break;
                
            case 'photoPath':
                $this->sPhotoPath = $value;
                break;
                
            case 'caption':
                $this->sCaption = $value;
                break;
                
            case 'dateUploaded':
                $this->dateDateUploaded = $value;
                break;
                
            case 'active':
                $this->bActive = $value;
                break;
                
            case 'comments':
                $this->aComments = $value;
                break;
                
            case 'likes':
                $this->aLikes = $value;
                break;
                
            case 'photos':
                $this->aPhotos = $value;
                break;
                
            default:
                die($var . " is not allowed");
                break;
                
        }
        
    }
    
    public function load($iPhotoID){
        
        $oCon = new Connection();
        
        $sSql = "SELECT PhotoID, AlbumID, UserID, ContestID, PhotoPath, Caption, DateUploaded, Active FROM tbphoto WHERE PhotoID = " .$iPhotoID;
        
        $oResultSet = $oCon->query($sSql);
        
        $aRow = $oCon->fetchArray($oResultSet);
        
        $this->iPhotoID = $aRow["PhotoID"];
        $this->iAlbumID = $aRow["AlbumID"];
        $this->iUserID = $aRow["UserID"];
        $this->iContestID = $aRow["ContestID"];
        $this->sPhotoPath = $aRow["PhotoPath"];
        $this->sCaption = $aRow["Caption"];
        $this->dateDateUploaded = $aRow["DateUploaded"];
        $this->bActive = $aRow["Active"];
        
        $sSql = "SELECT CommentID FROM tbcomment WHERE PhotoID = " .$iPhotoID." AND Active = 1";    
        
        $oResultSet = $oCon->query($sSql);
        
        while($aRow = $oCon->fetchArray($oResultSet)){
            $iCommentID = $aRow["CommentID"];            
            $oComment = new Comment();
            
            $oComment->load($iCommentID);
            $this->aComments[] = $oComment;
        
        }
        
        $sSql = "SELECT LikeID FROM tblike WHERE PhotoID = " .$iPhotoID." AND Active = 1"; 

        $oResultSet = $oCon->query($sSql);
        
        while($aRow = $oCon->fetchArray($oResultSet)){
            $iLikeID = $aRow["LikeID"];            
            $oLike = new Like();
            
            $oLike->load($iLikeID);
            $this->aLikes[] = $oLike;
        
        }
        
        $oCon->close();
        
    }
    
    public function save(){
        
        $oCon = new Connection();

        
        $sSql = "INSERT INTO tbphoto (PhotoID, AlbumID, UserID, ContestID, PhotoPath, Caption, DateUploaded, Active) VALUES ('"
            .$this->iPhotoID."', '"
            .$this->iAlbumID."', '"
            .$this->iUserID."', '"
            .$this->iContestID."', '"
            .$this->sPhotoPath."', '"
            .$this->sCaption."', '"
            .$this->dateDateUploaded."', '"
            .$this->bActive."'
            )";

        $bResult = $oCon->query($sSql);

        if($bResult){

            $this->iPhotoID = $oCon->getInsertID();
            
            echo "SAVED";

        }else{
            
            $sSql = "UPDATE tbphoto
            SET
                AlbumID = '".$this->iAlbumID."',
                UserID = '".$this->iUserID."',
                ContestID = '".$this->iContestID."',
                PhotoPath = '".$this->sPhotoPath."',
                Caption = '".$this->sCaption."',
                DateUploaded = '".$this->dateDateUploaded."',
                Active = '".$this->bActive."'                 
            WHERE PhotoID = ".$this->iPhotoID;
            
            $bResult = $oCon->query($sSql);
            
            if(!$bResult){
                die($sSql . " did not run");
            }else{
                echo 'Updated';   
            }
            
        }
        
        $oCon->close();
    }
    
    public function loadByContestID($iContestID){
        
        $oCon = new Connection();
        
        $sSql = "SELECT PhotoID from tbphoto WHERE ContestID =" .$iContestID;
        
        $oResultSet = $oCon->query($sSql);
        
        $aPhotos = array();
        
        while($aRow = $oCon->fetchArray($oResultSet)){            
            $iPhotoID = $aRow["PhotoID"];
            
            $oPhoto = new Photo();
            $oPhoto->load($iPhotoID);
            $aPhotos[] = $oPhoto;            
        }

        
        $oCon->close();
        if(!$aPhotos){   
            return false;
             
        }else{
            return $aPhotos;
            
        }
        
    }
    
}

?>
