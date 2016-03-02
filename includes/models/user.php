<?php

require_once("connection.php");
require_once("album.php");
require_once("photo.php");

class User{

    private $iUserID;
    private $sUsername;
    private $sPassword;
    private $sFirstName;
    private $sLastName;
    private $sEmail;
    private $sPhone;
    private $sGender;
    private $dateDOB;
    private $sType;
    private $dateDateJoined;
    private $sPhotoPath;
    private $bActive;
    
    private $aAlbums;
    private $aPhotos;

    
    public function __construct(){
        
        date_default_timezone_set('Pacific/Auckland');
        $current_date = date('d/m/Y');
     
        $this->iUserID = 0;
        $this->sUsername = "";
        $this->sPassword = "";
        $this->sFirstName = "";
        $this->sLastName = "";
        $this->sEmail = "";
        $this->sPhone = "";
        $this->sGender = "";
        $this->dateDOB = new DateTime();
        $this->sType = "";
        $this->dateDateJoined = $current_date;
        $this->sPhotoPath = "";
        $this->bActive = true;
        
        $this->aAlbums = [];
        $this->aPhotos = [];
        
    }
    
    public function __get($var){
        
        switch($var){
                
            case 'userID':
                return $this->iUserID;
                break;
                
            case 'username':
                return $this->sUsername;
                break;
            
            case 'password':
                return $this->sPassword;
                break;
                
                
            case 'firstName':
                return $this->sFirstName;
                break;
                
                
            case 'lastName':
                return $this->sLastName;
                break;
                
                
            case 'email':
                return $this->sEmail;
                break;
                
                
            case 'phone':
                return $this->sPhone;
                break;
                
                
            case 'gender':
                return $this->sGender;
                break;
                
                
            case 'dob':
                return $this->dateDOB;
                break;
                
                
            case 'type':
                return $this->sType;
                break;
                
             case 'dateJoined':
                return $this->dateDateJoined;
                break;
                
             case 'photoPath':
                return $this->sPhotoPath;
                break;
                
             case 'active':
                return $this->bActive;
                break;
                
            case 'albums':
                return $this->aAlbums;
                break;
                
            case 'photos':
                return $this->aPhotos;
                break;
                
            default:
                die($var . " is now allowed");
                break;
                
        }
        
    }
    
    public function __set($var, $value){
        
        switch($var){
                
            case 'userID':
                $this->iUserID = $value;
                break;
                
            case 'username':
                $this->sUsername = $value;
                break;
            
            case 'password':
                $this->sPassword = $value;
                break;
                
                
            case 'firstName':
                $this->sFirstName = $value;
                break;
                
                
            case 'lastName':
                $this->sLastName = $value;
                break;
                
                
            case 'email':
                $this->sEmail = $value;
                break;
                
                
            case 'phone':
                $this->sPhone = $value;
                break;
                
                
            case 'gender':
                $this->sGender = $value;
                break;
                
                
            case 'dob':
                $this->dateDOB = $value;
                break;
                
                
            case 'type':
                $this->sType = $value;
                break;
                
             case 'dateJoined':
                $this->dateDateJoined = $value;
                break;
                
             case 'photoPath':
                $this->sPhotoPath = $value;
                break;
                
             case 'active':
                $this->bActive = $value;
                break;
                
            case 'albums':
                $this->aAlbums = $value;
                break;
                
            case 'photos':
                $this->aPhotos = $value;
                break;
                
            default:
                die($var . " is now allowed");
                break;
                
        }
        
    }
    
    public function load($iUserID){
        
        $oCon = new Connection();
        
        $sSql = "SELECT UserID, Username, Password, FirstName, LastName, Email, Phone, Gender, DOB,Type, DateJoined, PhotoPath, Active  FROM tbuser WHERE UserID = " .$iUserID;
        
        $oResultSet = $oCon->query($sSql);
        
        $aRow = $oCon->fetchArray($oResultSet);
        
        $this->iUserID = $aRow["UserID"];
        $this->sUsername = $aRow["Username"];
        $this->sPassword = $aRow["Password"];
        $this->sFirstName = $aRow["FirstName"];
        $this->sLastName = $aRow["LastName"];
        $this->sEmail = $aRow["Email"];
        $this->sPhone = $aRow["Phone"];
        $this->sGender = $aRow["Gender"];
        $this->dateDOB = $aRow["DOB"];
        $this->sType = $aRow["Type"];
        $this->dateDateJoined = $aRow["DateJoined"];
        $this->sPhotoPath = $aRow["PhotoPath"];
        $this->bActive = $aRow["Active"];
        
        $sSql = "SELECT AlbumID FROM tbalbum WHERE UserID = ". $iUserID . " AND Active = 1";
        
        $oResultSet = $oCon->query($sSql);
        
        while($aRow = $oCon->fetchArray($oResultSet)){
                    
            $iAlbumID = $aRow["AlbumID"];
            $oAlbum = new Album();
            
            $oAlbum->load($iAlbumID);
            $this->aAlbums[] = $oAlbum;
        }
        
        $sSql = "SELECT PhotoID FROM tbphoto WHERE UserID = ". $iUserID . " AND Active = 1";
        
        $oResultSet = $oCon->query($sSql);
        
        while($aRow = $oCon->fetchArray($oResultSet)){
            $iPhotoID = $aRow["PhotoID"];
            $oPhoto = new Photo();
            
            $oPhoto->load($iPhotoID);
            $this->aPhotos[] = $oPhoto;
        }        
                
        $oCon->close();    
        
    }
    
    public function save(){
        
        $oCon = new Connection();
        
        $sSql = "INSERT INTO tbuser (UserID, Username, Password, FirstName, LastName, Email, Phone, Gender, DOB, Type, DateJoined, PhotoPath, Active) VALUES ('"
            .$this->iUserID."', '"
            .$oCon->escape($this->sUsername)."', '"
            .$oCon->escape($this->sPassword)."', '"
            .$oCon->escape($this->sFirstName)."', '"
            .$oCon->escape($this->sLastName)."', '"
            .$oCon->escape($this->sEmail)."', '"
            .$oCon->escape($this->sPhone)."', '"
            .$this->sGender."', '"
            .$this->dateDOB."', '"
            .$this->sType."', '"
            .$this->dateDateJoined."', '"
            .$this->sPhotoPath."', '"
            .$this->bActive."'
            )";

        $bResult = $oCon->query($sSql);

        if($bResult){

            $this->iUserID = $oCon->getInsertID();

        }else{
            
            $sSql = "Update tbuser
            SET
                Username = '".$this->sUsername."',
                Password = '".$this->sPassword."',
                FirstName = '".$oCon->escape($this->sFirstName)."',
                LastName = '".$oCon->escape($this->sLastName)."',
                Email = '".$oCon->escape($this->sEmail)."',
                Phone = '".$oCon->escape($this->sPhone)."',
                Gender = '".$this->sGender."',
                DOB = '".$this->dateDOB."',   
                Type = '".$this->sType."',
                DateJoined = '".$this->dateDateJoined."',
                PhotoPath = '".$this->sPhotoPath."',
                Active = '".$this->bActive."'
            WHERE UserID = ".$this->iUserID;
            
            $bResult = $oCon->query($sSql);
            
            if(!$bResult){
                die($sSql . " did not run");
            }
        }
        
        $oCon->close();
        
    }
    
    public function loadByUsername($sUsername){
        
        $oCon = new Connection();
        
        $sSql = "SELECT UserID FROM tbuser WHERE Username = '" .$oCon->escape($sUsername) ."'";
        
        $oResultSet = $oCon->query($sSql);
            
        $aRow = $oCon->fetchArray($oResultSet);
        
        if($aRow){               
            $this->load($aRow["UserID"]);
            return true;
        }else{        
            return false;
        }
     
        $oCon->close();
    }
    
    
    
}

?>