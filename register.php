<?php
ob_start();

require_once("includes/template/head.php");

require_once("includes/models/form.php");

require_once("includes/models/user.php");

require_once("includes/models/album.php");

?>
           <div class="row content">
                <div class="col l6 m12 s12 left-content">
                    <div class="row">
                       <div class="col l6 m8 s12">
                          <h4>Register</h4>
                          
<?php
                       
$oForm = new Form();
                           
if(isset($_POST["submit"])){
    
    $oForm->data = $_POST;
    
    $oForm->checkRequired("first_name");
    $oForm->checkRequired("last_name");
    $oForm->checkEmailIfValid("email");
    $oForm->checkRequired("email");   
    $oForm->checkRequired("phone");
    $oForm->checkRequired("username");
    $oForm->checkRequired("password");
    $oForm->checkEqual("password", "password2");
    
    $oCheckUsername = new User();
    $bLoaded = $oCheckUsername->loadByUsername($_POST["username"]);
    
    if($bLoaded){
        $oForm->raiseCustomError("username","already exists");
    }
    
    if($oForm->valid){ 
        date_default_timezone_set('Pacific/Auckland');
        $current_date = date('d/m/Y');       
     
        $oNewUser = new User();
        
        $oNewUser->username = $_POST["username"];
        $oNewUser->password = password_hash($_POST["password"],PASSWORD_DEFAULT);
        $oNewUser->firstName = $_POST["first_name"];
        $oNewUser->lastName = $_POST["last_name"];
        $oNewUser->email = $_POST["email"];
        $oNewUser->phone = $_POST["phone"];
        $oNewUser->gender = $_POST["gender"];
        $oNewUser->photoPath = "assets/images/icon-user.png";
        $oNewUser->dob = $_POST["dob"];
        $oNewUser->type = "user";
        
        $oNewUser->save();
        
        $_SESSION["userID"] = $oNewUser->userID;
        
        $oAlbum = new Album();
        
        $oAlbum->userID = $oNewUser->userID;
        $oAlbum->albumName = "Default";
        $oAlbum->caption = "";
        $oAlbum->dateCreated = $current_date;
        $oAlbum->photoPath = "assets/images/icon-image.png";
        $oAlbum->active = 1;
        
        $oAlbum->save();
        
                
        header("Refresh: 0; URL=success.php");
        exit;       
        
    }
}


$oForm->makeTextInput("First Name", "first_name");
$oForm->makeTextInput("Last Name", "last_name");
$oForm->makeTextInput("Email Address", "email");
$oForm->makeTextInput("Phone", "phone");                           
$aLabels = ["male", "female"];
$oForm->makeRadio($aLabels, "gender");   
$oForm->makeDate("Date of Birth",  "dob");
$oForm->makeTextInput("Username", "username");    
$oForm->makePasswordInput("Password", "password");
$oForm->makePasswordInput("Re-Type Password", "password2");
$oForm->makeSubmit("Register");

echo $oForm->html;
?>
                      <div>
                            <p>Already have an account? <a href="index.php">Login</a></p>
                       </div>
                       </div>
                    </div>
                </div>
                <div class="col l6 m12 s12 right-content">
                    <div class="row">
                       
                    </div>
                </div>               
           </div>
<?php

require_once("includes/template/foot.php");

?>