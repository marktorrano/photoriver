<?php

require_once("connection.php");

class Voter{
    
    private $iVoterID;
    private $iUserID;
    private $iContestID;
    private $bActive;
    
    public function __construct(){
     
        $this->iVoterID = 0;
        $this->iUserID = 0;
        $this->iContestID = 0;
        $this->bActive = 1;
        
    }
    
    public function __get($var){
        
        switch($var){
                
            case 'voterID':
                return $this->iVoterID;
                break;
                
            case 'userID':
                return $this->iUserID;
                break;
                
            case 'contestID':
                return $this->iContestID;
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
                
            case 'voterID':
                $this->iVoterID = $value;
                break;
                
            case 'userID':
                $this->iUserID = $value;
                break;
                
            case 'contestID':
                $this->iContestID = $value;
                break;
                
            case 'active':
                $this->bActive = $value;
                break;
                
            default:
                die($var . " is not allowed");
                break;           
                
        }
        
    }
    
    public function load($iVoterID){
        
        $oCon = new Connection();
        
        $sSql = "SELECT VoterID, UserID, ContestID, Active FROM tbvoter WHERE VoterID = " . $iVoterID;
        
        $oResultSet = $oCon->query($sSql);
        
        $aRow = $oCon->fetchArray($oResultSet);
        
        $this->iVoterID = $aRow["VoterID"];
        $this->iUserID = $aRow["UserID"];
        $this->iContestID = $aRow["ContestID"];
        $this->bActive = $aRow["Active"];        
        
        $oCon->close();
        
    }
    
    public function save(){
        
        $oCon = new Connection();
        
        $sSql = "INSERT INTO tbvoter (VoterID, UserID, ContestID, Active) VALUES ('"
            .$this->iVoterID."', '"
            .$this->iUserID."', '"
            .$this->iContestID."', '"
            .$this->bActive."'
            )";
        
        $bResult = $oCon->query($sSql);

        if($bResult){

            $this->iUserID = $oCon->getInsertID();

        }else{
            
            $sSql = "Update tbvoter
            SET
                UserID = '".$this->iUserID."',
                ContestID = '".$this->iContestID."',
                Active = '".$this->bActive."'
            WHERE VoterID = ".$this->iVoterID;
            
            $bResult = $oCon->query($sSql);
            
            if(!$bResult){
                die($sSql . " did not run");
            
            }
        }
        
        $oCon->close();           
        
    }    
    
}

?>