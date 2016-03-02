<?php

require_once("connection.php");
require_once("photo.php");
require_once("voter.php");


class Contest{
 
    private $iContestID;
    private $iUserID;
    private $sContestName;
    private $sTheme;
    private $sPhotoPath;
    private $dateDateStart;
    private $dateDateEnd;
    private $bActive;
    
    private $aPhotos;
    private $aVoters;
    
    public function __construct(){
     
        date_default_timezone_set('Pacific/Auckland');
        $current_date = date('d/m/Y');
        
        
        
        $this->iContestID = 0;
        $this->iUserID = 0;
        $this->sContestName = "";
        $this->sTheme = "";
        $this->sPhotoPath = "";
        $this->dateDateStart = $current_date;
        $this->dateDateEnd = "0000-00-00";
        $this->bActive = 1;
        
        $this->aPhotos = [];
        $this->aVoters = [];
    }
    
    public function __get($var){
     
        switch($var){
                
            case 'contestID':
                return $this->iContestID;
                break;
                
            case 'userID':
                return $this->iUserID;
                break;
                
            case 'contestName':
                return $this->sContestName;
                break;
                
            case 'theme':
                return $this->sTheme;
                break;
                
            case 'photoPath':
                return $this->sPhotoPath;
                break;
                
            case 'dateStart':
                return $this->dateDateStart;
                break;
                
            case 'dateEnd':
                return $this->dateDateEnd;
                break;
                
            case 'active':
                return $this->bActive;
                break;
                
            case 'photos':
                return $this->aPhotos;
                break;
            
            case 'voters':
                return $this->aPhotos;
                break;
                
            default:
                die($var . " is not allowed");
                break;
            
        }
    }
    
    public function __set($var, $value){
     
        switch($var){
                
            case 'contestID':
                $this->iContestID = $value;
                break;
                
            case 'userID':
                $this->iUserID = $value;
                break;
                
            case 'contestName':
                return $this->sContestName = $value;
                
                
            case 'theme':
                $this->sTheme = $value;
                break;
                
            case 'photoPath':
                $this->sPhotoPath = $value;
                break;
                
            case 'dateStart':
                $this->dateDateStart = $value;
                break;
                
            case 'dateEnd':
                $this->dateDateEnd = $value;
                break;
                
            case 'active':
                $this->bActive = $value;
                break;
                
            case 'photos':
                $this->aPhotos = $value;
                break;
            
            case 'voters':
                $this->aPhotos = $value;
                break;
                
            default:
                die($var . " is not allowed");
                break;
            
        }
    }
    
    public function load($iContestID){
        
        $oCon = new Connection();
        
        $sSql = "SELECT ContestID, UserID, ContestName, Theme, PhotoPath, DateStart, DateEnd, Active FROM tbcontest WHERE ContestID = " .$iContestID;
        
        $oResultSet = $oCon->query($sSql);
        
        $aRow = $oCon->fetchArray($oResultSet);
        
        $this->iContestID = $aRow["ContestID"];
        $this->iUserID = $aRow["UserID"];
        $this->sContestName = $aRow["ContestName"];
        $this->sTheme = $aRow["Theme"];
        $this->sPhotoPath = $aRow["PhotoPath"];
        $this->dateDateStart = $aRow["DateStart"];
        $this->dateDateEnd = $aRow["DateEnd"];
        $this->bActive = $aRow["Active"];
        
        $sSql = "SELECT PhotoID FROM tbphoto WHERE ContestID = " . $iContestID . " AND Active = 1";
        
        $oResultSet = $oCon->query($sSql);
        
        while($aRow = $oCon->fetchArray($oResultSet)){
                    
            $iPhotoID = $aRow["PhotoID"];
            $oPhoto = new Photo();
            
            $oPhoto->load($iPhotoID);
            $this->aPhotos[] = $oPhoto;
        }
        
        $sSql = "SELECT VoterID FROM tbvoter WHERE ContestID = " . $iContestID . " AND Active = 1";
        
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
        
        $sSql = "INSERT INTO tbcontest (ContestID, UserID, ContestName, Theme, PhotoPath, DateStart, DateEnd, Active) VALUES ('"
            .$this->iContestID."', '"
            .$this->iUserID."', '"
            .$oCon->escape($this->sContestName)."', '"
            .$oCon->escape($this->sTheme)."', '"
            .$this->sPhotoPath."', '"
            .$this->dateDateStart."', '"
            .$this->dateDateEnd."', '"
            .$this->bActive."'
            )";
        
        $bResult = $oCon->query($sSql);
        
        if($bResult){

            $this->iContestID = $oCon->getInsertID();

        }else{
            
            $sSql = "Update tbcontest
            SET
                UserID = '".$this->iUserID."',
                ContestName = '".$oCon->escape($this->sContestName)."',
                Theme = '".$oCon->escape($this->sTheme)."',
                PhotoPath = '".$this->sPhotoPath."',
                DateStart = '".$this->dateDateStart."',
                DateEnd = '".$this->dateDateEnd."',
                Active = '".$this->bActive."'
            WHERE ContestID = ".$this->iContestID;
            
            $bResult = $oCon->query($sSql);
            
            if(!$bResult){
                die($sSql . " did not run");
            }
            
        }
        
        $oCon->close();
    }
    
    
}

?>














