<?php

require_once("connection.php");
require_once("contest.php");

class ContestManager{
 
    static public function all(){
     
        $aContests = array();
        
        $oCon = new Connection();
        
        $sSql = "SELECT ContestID FROM tbcontest WHERE Active = 1";
        
        $oResultSet = $oCon->query($sSql);
        
        while($aRow = $oCon->fetchArray($oResultSet)){
            
            $iContestID = $aRow["ContestID"];
            
            $oContest = new Contest();
            $oContest->load($iContestID);
            $aContests[] = $oContest;
            
        }
              
        $oCon->close();
              
        return $aContests;
        
    }
    
}

?>