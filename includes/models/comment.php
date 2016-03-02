<?php

require_once("connection.php");

class Comment{
    
    private $iCommentID;
    private $iPhotoID;
    private $sComment;
    private $iUserID;
    private $dateDateCreated;
    private $bActive;
    
    public function __construct(){
        
        date_default_timezone_set('Pacific/Auckland');
        $current_date = date('d/m/Y == H:i:s');
        
        $this->iCommentID = 0;
        $this->iPhotoID = 0;
        $this->sComment = "";
        $this->iUserID = 0;
        $this->dateDateCreated = $current_date;   
        $this->bActive = 1;
        
    }
    
    public function __get($var){
        
        switch($var){
                
            case 'commentID':
                return $this->iCommentID;
                break;
                
            case 'photoID':
                return $this->iPhotoID;
                break;
                
            case 'comment':
                return $this->sComment;
                break;
                
            case 'userID':
                return $this->iUserID;
                break;
                
            case 'dateCreated':
                return $this->dateDateCreated;
                break;
                
            case 'active':
                return $this->bActive;
                break;
                
            default:
                die($var . " is not allowed");
                break;
                
        }
    }
    
    public function __set($var, $value){
        
        switch($var){
                
            case 'commentID':
                $this->iCommentID = $value;
                break;
                
            case 'photoID':
                $this->iPhotoID = $value;
                break;
                
            case 'comment':
                $this->sComment = $value;
                break;
                
            case 'userID':
                $this->iUserID = $value;
                break;
                
            case 'dateCreated':
                $this->dateDateCreated = $value;
                break;
                
            case 'active':
                $this->bActive = $value;
                break;
                
            default:
                die($var . " is not allowed");
                break;
                
        }
    }
    
    public function load($iCommentID){
        
        $oCon = new Connection();
        
        $sSql = "SELECT CommentID, PhotoID, Comment, UserID, DateCreated, Active FROM tbcomment WHERE CommentID = " .$iCommentID;
        
        $oResultSet = $oCon->query($sSql);
        
        $aRow = $oCon->fetchArray($oResultSet);
        
        $this->iCommentID = $aRow["CommentID"];
        $this->iPhotoID = $aRow["PhotoID"];
        $this->sComment = $aRow["Comment"];
        $this->iUserID = $aRow["UserID"];
        $this->dateDateCreated = $aRow["DateCreated"];
        $this->bActive = $aRow["Active"];
        
        $oCon->close();
    }
    
    public function save(){
        
        $oCon = new Connection();
        
        $sSql = "INSERT INTO tbcomment (CommentID, PhotoID, Comment, UserID, DateCreated, Active) VALUES ('"
            .$this->iCommentID."', '"
            .$this->iPhotoID."', '"
            .$this->sComment."', '"
            .$this->iUserID."', '"
            .$this->dateDateCreated."', '"
            .$this->bActive."'
            )";

        $bResult = $oCon->query($sSql);

        if($bResult){

            $this->iCommentID = $oCon->getInsertID();


        }else{
            
            $sSql = "UPDATE tbcomment
            SET
                PhotoID = '".$this->iPhotoID."',
                Comment = '".$this->sComment."',
                UserID = '".$this->iUserID."',
                DateCreated = '".$this->dateDateCreated."',
                Active = '".$this->bActive."'                 
            WHERE CommentID = ".$this->iCommentID;
            
            $bResult = $oCon->query($sSql);
            
            if(!$bResult){
                die($sSql . " did not run");
            }
        }
        
        $oCon->close();
    }
    
    
}

?>