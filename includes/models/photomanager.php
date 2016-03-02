<?php

require_once("connection.php");
require_once("photo.php");

class PhotoManager{
 
    static public function all(){
     
        $aPhotos = array();
        
        $oCon = new Connection();
        
        $sSql = "SELECT PhotoID FROM tbphoto WHERE Active = 1";
        
        $oResultSet = $oCon->query($sSql);
        
        while($aRow = $oCon->fetchArray($oResultSet)){
            
            $iPhotoID = $aRow["PhotoID"];
            
            $oPhoto = new Photo();
            $oPhoto->load($iPhotoID);
            $aPhotos[] = $oPhoto;
            
        }
              
        $oCon->close();
              
        return $aPhotos;
        
    }
    
}

?>