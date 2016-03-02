<?php

require_once("connection.php");

class Like{
    
    private $iLikeID;
    private $iPhotoID;
    private $bActive;
    private $iUserID;
    
    private $bLiked;
    
    public function __construct(){
                
        $this->iLikeID = 0;
        $this->iPhotoID = 0;
        $this->iUserID = 0;
        $this->bActive = 1;
        
        $this->bLiked = 0;
        
    }
    
    public function __get($var){
        
        switch($var){
                
            case 'likeID':
                return $this->iLikeID;
                break;
                
            case 'photoID':
                return $this->iPhotoID;
                break;
                
            case 'userID':
                return $this->iUserID;
                break;
                
            case 'active':
                return $this->bActive;
                break;
                
            case 'liked':
                return $this->bLiked;
                break;
                
            default:
                die($var . ' is not allowed');
                break;
        }
        
    }
    
    public function __set($var, $value){
        
        switch($var){
                
            case 'likeID':
                $this->iLikeID = $value;
                break;
                
            case 'photoID':
                $this->iPhotoID = $value;
                break;
                
            case 'userID':
                $this->iUserID = $value;
                break;
                
            case 'active':
                $this->bActive = $value;
                break;
                
            case 'liked':
                $this->bLiked = $value;;
                break;
                
            default:
                die($var . ' is not allowed');
                break;
        }
        
    }
    
    public function load($iLikeID){
        
        $oCon = new Connection();
        
        $sSql = "SELECT LikeID, PhotoID, UserID, Active FROM tblike WHERE LikeID = " .$iLikeID;
        
        $oResultSet = $oCon->query($sSql);
        
        $aRow = $oCon->fetchArray($oResultSet);
        
        $this->iLikeID = $aRow["LikeID"];
        $this->iPhotoID = $aRow["PhotoID"];
        $this->iUserID = $aRow["UserID"];
        $this->bActive = $aRow["Active"];
        
        $oCon->close();
        
        
    }
    
    public function save(){
        
        $oCon = new Connection();
        
        $sSql = "INSERT INTO tblike (LikeID, PhotoID, UserID, Active) 
        VALUES ('"
            .$this->iLikeID."', '"
            .$this->iPhotoID."', '"
            .$this->iUserID."', '"
            .$this->bActive."'
            )";
        
        $bResult = $oCon->query($sSql);

        if($bResult){

            $this->iLikeID = $oCon->getInsertID();

        }else{
            
            $sSql = "Update tblike
            SET
                PhotoID = '".$this->iPhotoID."',
                UserID = '".$this->iUserID."',
                Active = '".$this->bActive."'
                
            WHERE LikeID = " .$this->iLikeID;
            
            $bResult = $oCon->query($sSql);
            
            if(!$bResult){
                die($sSql . " did not run");
            }
        }
        
        $oCon->close();
            
    }
    
    public function loadByPhotoID($iPhotoID, $iUserID){
        
        $oCon = new Connection();
        
        $sSql = "SELECT LikeID FROM tblike WHERE PhotoID = " .$iPhotoID. " AND UserID = ".$iUserID;
        
        $oResultSet = $oCon->query($sSql);
        
        $aRow = $oCon->fetchArray($oResultSet);
        
        $oPhoto = new Photo();
        if($aRow){
            $this->load($aRow["LikeID"]);
            $oPhoto->load($iPhotoID);
            return true;
        }else{
            return false;
        }
        
        $sSql = "SELECT LikeID FROM tblike WHERE PhotoID = " .$iPhotoID. " AND UserID = ".$iUserID. " AND Active = 1";
        
        $oResultSet = $oCon->query($sSql);
        
        $aRow = $oCon->fetchArray($oResultSet);
        
        if($aRow){
            //is active
            $this->bLiked = 1; 
        }else{
            //is not active
            $this->bLiked = 0;
        }
        
        $oCon->close();

    }
   
}

?>
