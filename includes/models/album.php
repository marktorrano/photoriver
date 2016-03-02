<?php

require_once("connection.php");
require_once("photo.php");

class Album{

    private $iAlbumID;
    private $iUserID;
    private $sAlbumName;
    private $sCaption;
    private $dateDateCreated;
    private $sPhotoPath;
    private $bActive;
    private $iContestID;
    
    private $aAlbums;
    private $aPhotos;
    
    public function __construct(){
        
        date_default_timezone_set('Pacific/Auckland');
        $current_date = date('d/m/Y');
        
        $this->iAlbumID = 0;
        $this->iUserID = 0;
        $this->sAlbumName = "";
        $this->sCaption = "";
        $this->dateDateCreated = $current_date;
        $this->sPhotoPath = "";
        $this->bActive = true;
        $this->iContestID = 0;
        
        $this->aAlbums = [];
        $this->aPhotos = [];
        
    }
    
    public function __get($var){
        
        switch($var){
                
            case 'albumID':
                return $this->iAlbumID;
                break;
                
            case 'userID':
                return $this->iUserID;
                break;
                
            case 'albumName':
                return $this->sAlbumName;
                break;
                
            case 'caption':
                return $this->sCaption;
                break;
                
            case 'dateCreated':
                return $this->dateDateCreated;
                break;
                
            case 'photoPath':
                return $this->sPhotoPath;
                break;
                
            case 'active':
                return $this->bActive;
                break;
                
            case 'contestID':
                return $this->iContestID;
                break;
                
            case 'albums':
                return $this->aAlbums;
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
                
            case 'albumID':
                $this->iAlbumID = $value;
                break;
                
            case 'userID':
                $this->iUserID = $value;
                break;
                
            case 'albumName':
                $this->sAlbumName = $value;
                break;
                
            case 'caption':
                $this->sCaption = $value;
                break;
                
            case 'dateCreated':
                $this->dateDateCreated = $value;
                break;
                
            case 'photoPath':
                $this->sPhotoPath = $value;
                break;
                
            case 'active':
                $this->bActive = $value;
                break;
                
            case 'contestID':
                $this->iContestID = $value;
                break;
                
            case 'albums':
                $this->aAlbums = $value;
                break;
                
            case 'photos':
                $this->aPhotos = $value;
                break;
                
            default:
                die($var . " is not allowed");
                break;
            
        }
        
    }
    
    public function save(){
        
        $oCon = new Connection();

        
        $sSql = "INSERT INTO tbalbum (AlbumID, UserID, AlbumName, Caption, DateCreated, PhotoPath, Active, ContestID) VALUES ('"
            .$this->iAlbumID."', '"
            .$this->iUserID."', '"
            .$oCon->escape($this->sAlbumName)."', '"
            .$oCon->escape($this->sCaption)."', '"
            .$this->dateDateCreated."', '"
            .$this->sPhotoPath."', '"
            .$this->bActive."', '"
            .$this->iContestID."'
            )";

        $bResult = $oCon->query($sSql);

        if($bResult){

            $this->iAlbumID = $oCon->getInsertID();
            

        }else{
            
            $sSql = "UPDATE tbalbum
            SET
                UserID = '".$this->iUserID."',
                AlbumName = '".$oCon->escape($this->sAlbumName)."',
                Caption = '".$oCon->escape($this->sCaption)."',
                DateCreated = '".$this->dateDateCreated."',
                PhotoPath = '".$this->sPhotoPath."',
                Active = '".$this->bActive."',
                ContestID = '".$this->iContestID."'
            WHERE AlbumID = ".$this->iAlbumID;
            
            $bResult = $oCon->query($sSql);
            
            if(!$bResult){
                die($sSql . " did not run");
            }
        }
        
        $oCon->close();
        
    }
    
    public function load($iAlbumID){
        
        $oCon = new Connection();
        
        $sSql = "SELECT AlbumID, UserID, AlbumName, Caption, DateCreated, PhotoPath, Active, ContestID FROM tbalbum WHERE AlbumID = " .$iAlbumID;
        
        $oResultSet = $oCon->query($sSql);
        
        $aRow = $oCon->fetchArray($oResultSet);
        
        $this->iAlbumID = $aRow["AlbumID"];
        $this->iUserID = $aRow["UserID"];
        $this->sAlbumName = $aRow["AlbumName"];
        $this->sCaption = $aRow["Caption"];
        $this->dateDateCreated = $aRow["DateCreated"];
        $this->sPhotoPath = $aRow["PhotoPath"];
        $this->bActive = $aRow["Active"];
        $this->iContestID = $aRow["ContestID"];
        
        $sSql = "SELECT PhotoID FROM tbphoto WHERE AlbumID = " .$iAlbumID. " AND Active = 1";
        
        $oResultSet = $oCon->query($sSql);
        
        while($aRow = $oCon->fetchArray($oResultSet)){
            $iPhotoID = $aRow["PhotoID"];
            $oPhoto = new Photo();
            
            $oPhoto->load($iPhotoID);
            $this->aPhotos[] = $oPhoto;
        }
        
        $oCon->close();
        
    }
    
    public function loadByUserID($iUserID){
        
        $oCon = new Connection();
        
        $sSql = "SELECT AlbumID FROM tbalbum WHERE UserID = " . $iUserID . " AND Active = 1";
        
        $oResultSet = $oCon->query($sSql);
        
        while($aRow = $oCon->fetchArray($oResultSet)){
            
            $this->aAlbums[] = $aRow["AlbumID"];
            
        }
        
        
        $oCon->close();
    }
    
    public function loadByContestID($iContestID){
        
        $oCon = new Connection();
        
        $sSql = "SELECT AlbumID FROM tbalbum WHERE ContestID = " . $iContestID . " AND Active = 1";
        
        $oResultSet = $oCon->query($sSql);
        
        $aRow = $oCon->fetchArray($oResultSet);
        
        $oCon->close();
        
        return $aRow["AlbumID"];        
        
    }
    
}

?>