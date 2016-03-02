<?php

class Form{
    
    private $sHTML;
    private $aData;
    private $aErrors;
    private $aFiles;
    
    public function __construct(){
        
        $this->sHTML = '
        
        <div class="row">
        <form action="" method="post" class="" enctype="multipart/form-data"/>
        <div class="row">
        ';
        
        $this->aData = [];
        
        $this->aFiles = [];
        
        $this->aErrors = [];
    }
    
    public function makeFileInput($sControlName,$sControlLabel){
        
        $sError = '';
        
        if(isset($this->aErrors[$sControlName])){
            //There is an error
            $sError = '<label class="errors" for="'.$sControlName.'">'.$sControlLabel.' '.$this->aErrors[$sControlName].'<label>';
            
        }
        
        $this->sHTML .= '
        <div class="file-field input-field">
          <div class="btn">
            <span>File</span>
            <input type="file" name="'.$sControlName.'">
          </div>
          <div class="file-path-wrapper">
            <input class="file-path validate" type="text" placeholder="">
          </div>
        </div>
        
        '.$sError;   
        
    }
    
    public function checkFileUploaded($sControlName){
        if(isset($this->aFiles[$sControlName])==false){
            $this -> aErrors[$sControlName] = "File not uploaded";
        }else{
            if($this->aFiles[$sControlName]["error"] != 0){
                $this -> aErrors[$sControlName] = "File has error";
            }
        }
    }
    
    public function moveFile($sControlName,$sNewName){
		$sNewPath = dirname(__FILE__).'/../../'.$sNewName;
		move_uploaded_file($this->aFiles[$sControlName]['tmp_name'], $sNewPath);

	}
    
    public function makeTextInput($sControlLabel, $sControlName){

        $sControlData = "";
        $sError = "";
        
        if(isset($this->aData[$sControlName])){
            $sControlData = $this->aData[$sControlName];
        }
        
        if(isset($this->aErrors[$sControlName])){
            
            $sError = '<label class="errors" for="'.$sControlName.'">'.$sControlLabel.' '.$this->aErrors[$sControlName].'<label>';
            
        }
        
        $this->sHTML .= '
            <div class="input-field">            
                <input placeholder="" type="text" class="validate" id="'. $sControlName .'" name="'. $sControlName .'" value="'. $sControlData .'">
                <label for="' .$sControlName. '">'. $sControlLabel .'</label>' . $sError . "\n
            </div>";
        
    }
    
    public function makePasswordInput($sControlLabel, $sControlName){
        
        $sControlData = "";
        $sError = "";
        
        if(isset($this->aErrors[$sControlName])){
            
            $sError = '<label class="errors" for="'.$sControlName.'">'.$this->aErrors[$sControlName].'<label>';
            
        }
        
        $this->sHTML .= '
        <div class="input-field">
            <input type="password" id="'. $sControlName .'" name="'. $sControlName .'" value="'. $sControlData .'" placeholder=""/><label>'. $sControlLabel .'</label>
        ' . $sError . "\n</div>";
        
    }
    
    public function makeHidden($sControlName, $sControlValue){
        
        $this->sHTML .= '<input type="hidden" name="'.$sControlName.'" value="'.$sControlValue.'"/>';
                
    }
    
    public function makeTextArea($sControlLabel, $sControlName){
        
        $sControlData = "";
        $sError = "";
        
        if(isset($this->aData[$sControlName])){
            $sControlData = $this->aData[$sControlName];
        }
        
        if(isset($this->aErrors[$sControlName])){
            $sError = '<p>'.$this->aErrors[$sControlName].'<p>';
            
        }
        
        $this->sHTML .= '
            <label for="'.$sControlName.'"> '.$sControlLabel.'</label>
            <textarea class="materialize-textarea" id="'.$sControlName.'" name="'.$sControlName.'">'. $sControlData .'</textarea/>' .$sError;
    }
    
    public function makeSelect($sLabel,$sName,$aOptions,$sControlLabel, $sStickyValue){
        
        $this->sHTML .= 
        '<label for="'.$sName.'">'.$sLabel.'</label>
        <select name="'.$sName.'" id="'.$sName.'">';

        for($iCount=0; $iCount<count($aOptions);$iCount++){

            $sOption = $aOptions[$iCount];

            if($sOption == $sStickyValue){
                $this->sHTML .='<option value="'.$sOption.'" selected>'.$sControlLabel[$iCount].'</option>';
            }else{

                $this->sHTML .='<option value="'.$sOption.'">'.$sControlLabel[$iCount].'</option>';

            }


        }

        $this->sHTML .='</select>';

    }
    
    public function makeRadio($aLabels, $sControlName){
        
        $this->sHTML .= '
        <p>';
        
        for($iCount = 0; $iCount<count($aLabels); $iCount++){
            
             $this->sHTML .= '
            <input type="radio" name="'.$sControlName.'" value="'.$aLabels[$iCount].'" id="'.$aLabels[$iCount].'"/>
            <label for="'.$aLabels[$iCount].'">'.$aLabels[$iCount].'</label>
            ';            
            
        }
        
        $this->sHTML .= '
        </p>';
        
    }
    
    public function makeDate($sControlLabel, $sControlName){
        
        $this->sHTML .= '<div>
        <label for="'.$sControlName.'">'.$sControlLabel.'</label>
        <input type="date" id="'.$sControlName.'" name="'.$sControlName.'" class="datepicker">        
        </div>';
        
    }
    
    public function makeSubmit($sControlLabel){
           $this->sHTML .= '<input class="waves-effect waves-light btn cyan" type="submit" name="submit" value="'.$sControlLabel.'"></a>';
    }
    
    public function checkRequired($sControlName){
   
        $sControlData = "";
        
        if(isset($this->aData[$sControlName])){
            
            $sControlData = trim($this->aData[$sControlName]);
            
        }
        
        if(strlen($sControlData) == 0){
            
            $this->aErrors[$sControlName] = "must not be empty";
            
        }
        
    }
    
    public function checkEmailIfValid($sControlName){

        if (!filter_var($this->aData[$sControlName], FILTER_VALIDATE_EMAIL)){
            
            $this->aErrors[$sControlName] = "is not a valid email address.";
            
        }        
        
    }
    
    public function checkIfNumber($sControlName){
        
        $sControlData = $this->aData[$sControlName];
            
        if(!filter_var($sControlData, FILTER_VALIDATE_INT)===0 || !filter_var($sControlData, FILTER_VALIDATE_INT)===False ){
            
            $this->aErrors[$sControlName] = "must be numbers only.";
            
        }

    }
    
    public function checkEqual($sControlName1, $sControlName2){
        
        $sData1 = $this->aData[$sControlName1];
        $sData2 = $this->aData[$sControlName2];
        
        if($sData1 != $sData2){
            $this->aErrors[$sControlName2] = "<p>Did not match</p>";
        }
        
    }
    
    public function raiseCustomError($sControlName,$sMessage){
        
        $this->aErrors[$sControlName] = $sMessage;
    }
    
    public function raiseCustomMessage($sMessage){
        
        
    }
    
    
    public function __get($var){
                
        switch($var){
         
            case 'html':
                return $this->sHTML .'</form> </div></div>';
                break;
                
            case 'valid':
                if(count($this->aErrors) == 0){
                    return true;
                }else{
                    return false;
                }
                break;
                
            case 'data':
                return $this->aData;
                break;
                
            default:
                die($var .' is not allowed');
                break;
                
        }
    }
    
    public function __set($var, $value){
                
        switch($var){
         
            case 'data':
                $this->aData = $value;
                break;
                
            case 'files':
                $this->aFiles = $value;
                break;
                
            default:
                die($var .' is not allowed');
                break;
                
        }
    }    
    
}

?>