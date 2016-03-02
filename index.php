<?php
ob_start();

session_start();

require_once("includes/template/head.php");

require_once("includes/models/form.php");

require_once("includes/models/user.php");

require_once("includes/views/view.php");

require_once("includes/models/like.php");

require_once("includes/models/photomanager.php");

if(isset($_SESSION["UserID"])){
    
    header("Refresh: 0; url=userprofile.php");
}

$oAuthentication = new User();

$oPhotoManager = new PhotoManager();

$aAllPhotos = $oPhotoManager->all();

$oForm = new Form();

$oView = new View();
?>
 
           <div class="row content main-page">
               <div class="row"></div>
               
            
                <div class="col l4 m12 s12 login-div">
                  <div class="row">
                       <div class="col l10 m12 s12 login"><h4>Login</h4>
                       
                       <?php
                           
                           if(isset($_POST["submit"])){
                               
                                $postUsername = $_POST["username"];
                                $postPassword = $_POST["password"];        

                                if(!$oAuthentication->loadByUsername($postUsername)){

                                        $oForm->raiseCustomError("username", "bad name");
                                }
                                else{
                                    if(password_verify($oAuthentication->password,$postPassword)){
                                        $oForm->raiseCustomError("password", "bad password");
                                    }
                                }

                                if($oForm->valid){

                                    

                                    $_SESSION["UserID"] = $oAuthentication->userID;

                                    echo "Successfully logged in! You will be redirected shortly";        

                                    header('Refresh: 1; URL=userprofile.php');
                                }
                               
                           }
                        
                           $oForm->makeTextInput("Username", "username");
                           $oForm->makePasswordInput("Password", "password");
                           $oForm->makeSubmit("Login");
                           echo $oForm->html;
                           
                        ?>

                      <div>
                                    <a href="register.php">Create Account</a>
                                </div>
                       </div>
                    </div>               
               </div>
               <?php

            echo $oView::renderAllPhotos(array_reverse($aAllPhotos));

            ?>
       </div>
   
<?php

require_once("includes/template/foot.php");

?>