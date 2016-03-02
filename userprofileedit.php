<?php
ob_start();

session_start();

require_once("includes/template/head.php");

require_once("includes/models/user.php");

require_once("includes/models/form.php");

require_once("includes/views/view.php");

if(!isset($_SESSION["UserID"])){
    
    header("Refresh: 0; URL=index.php");
    exit;
}

$oView = new View();

$oUser = new User();

$oForm = new Form();

$oUser->load($_SESSION["UserID"]);

$aExistingUser["first_name"] = $oUser->firstName;
$aExistingUser["last_name"] = $oUser->lastName;
$aExistingUser["email"] = $oUser->email;
$aExistingUser["phone"] = $oUser->phone;
$aExistingUser["gender"] = $oUser->gender;
$oForm->data = $aExistingUser;

echo $oView::renderNav($oUser);

echo $oView::renderUserDetails($oUser);

echo $oView::renderHeader("Edit User Profile");


    if(isset($_POST["submit"])){

        $oForm->checkRequired("first_name");
        $oForm->checkRequired("last_name");
        $oForm->checkRequired("email");
        $oForm->checkRequired("phone");

        if($oForm->valid){ 

            $oUser->firstName = $_POST["first_name"];
            $oUser->lastName = $_POST["last_name"];
            $oUser->email = $_POST["email"];
            $oUser->phone = $_POST["phone"];

            $oUser->save();

            echo "Successfully updated profile.";
            header("Refresh: 1; URL=userprofile.php");
            exit;
        }

    }


    $oForm->makeTextInput("First Name", "first_name");
    $oForm->makeTextInput("Last Name", "last_name");
    $oForm->makeTextInput("Email Address", "email");
    $oForm->makeTextInput("Phone", "phone");
    $oForm->makeSubmit("Update");

    echo $oForm->html;
?>

                
                
                </div>
            </div>
           
<?php

require_once("includes/template/foot.php");

?>