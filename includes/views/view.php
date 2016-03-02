<?php


class View{
    
    static public function renderContests($aAllContests, $sHTMLAddContest){
        
        $oPhoto = new Photo();
        $iTotalLikes = 0;
        
        $sHTML = '
            <div id="modalAddContest" class="modal modal-fixed-footer">
                <div class="modal-content">
                <h4>Create Contest</h4>
                  '.$sHTMLAddContest.'
                </div>
            </div>
            
            
        
            <div class="col l8"><a href="#modalAddContest" class="btn-floating btn-large right waves-effect waves-light cyan left modal-trigger"><i class="material-icons">add</i></a><h4>List of contest</h4>
               <div class="card">';
        
        foreach($aAllContests as $aContests => $oContests){  
            $oContest = new Contest();
            $oAlbum = new Album();
            $oContest->load($oContests->contestID);
            if($oPhoto->loadByContestID($oContest->contestID)){
                $aAllPhotos = $oPhoto->loadByContestID($oContest->contestID);

                foreach($aAllPhotos as $aPhotos => $oPhotos){
                    $oPhoto = new Photo();
                    $oPhoto->load($oPhotos->photoID);
                    $iTotalLikes += count($oPhoto->likes);
                }
            }
            
            
        $sHTML .= '
        <h5><a href="entries.php?albumID='.$oAlbum->loadByContestID($oContests->contestID).'">'.htmlentities($oContest->contestName).'</a></h5>
                   
                   <div ><a href="entries.php?albumID='.$oAlbum->loadByContestID($oContests->contestID).'"><img class="contest-image" src="'.$oContest->photoPath.'"/></a></div>
                   <ul>
                       <li>Theme: '.htmlentities($oContest->theme).'</li>
                       <li>Total Votes:'.$iTotalLikes.'</li>
                       <li><a href="entries.php?albumID='.$oAlbum->loadByContestID($oContests->contestID).'">See entries</a></li>                       
                   </ul>                   
                   
                   <div class="divider"></div>
                   ';
            
        }
                           
        $sHTML .= '
                </div> 
            </div>
        ';
        
        return $sHTML;
    }
    
    static public function renderNav($oUser){

        $sHTML = '
        <nav class="cyan">
                <div class="nav-wrapper">
                    <a href="userprofile.php" class="brand-logo">Photo River</a>
                    <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
                    <ul class="right hide-on-med-and-down">
                        <li><a href="userprofile.php">Hello, '.htmlentities($oUser->firstName).'</a></li>
                        <li><a href="contests.php">Contests</a></li>
                        <li><a href="river.php">River</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                    <ul class="side-nav" id="mobile-demo">
                        <li><a href="userprofile.php">Profile</a></li>
                        <li><a href="contests.php">Contests</a></li>
                        <li><a href="river.php">River</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </nav>
            <div class="row content">
        ';
        
        return $sHTML;
    }

    
    static public function renderUserDetails($oUser){
     
        $sHTML = '
                       <div class="row"></div>
                <div class="col l3">
                    <div class="col l10 m6 push-l1">
                        <a class="modal-trigger" href="#modal'.htmlentities($oUser->username).'"><img class="profile-picture" src="'.$oUser->photoPath.'"><div class="userprofile-overlay"></div></img></a>
                        <div class="img-overlay"></div>
                    </div>
                    <div class="col l10 push-l1 grey-text text-darken-2">
                        <p>Name: '.htmlentities($oUser->firstName).' '.htmlentities($oUser->lastName).'</p>
                        <p>Email: '.htmlentities($oUser->email).'</p>
                        <p>Phone: '.htmlentities($oUser->phone).'</p>
                        <p><a href="userprofileedit.php">Edit Profile</a></p>
                        <p><a href="contests.php">Host a contest</a></p>
                    </div>
                </div>
                ';
        
        return $sHTML;
    }
    
    static public function renderModalUploadProfilePicture($iUserID, $html){
        
        $oUser = new User();
        $oUser->load($iUserID);        
        
         $sHTML = '
                            <div id="modal'.htmlentities($oUser->username).'" class="modal modal-fixed-footer">
                        <div class="modal-content">
                          <h4>Upload Photo</h4>'.$html.' </div>

                      </div>';

        return $sHTML;
        
    }
    
    static public function renderHeader($sTitle){
        $sHTML = '<div class="col l8">
                <div class="col l6"><h4>'.htmlentities($sTitle).'</h4>
                <div class="row"></div>
                ';
        
        return $sHTML;
    }
    
    static public function renderModalUpload($iModalID, $html){
        
     
        $sHTML = '
                            <div id="modal'.$iModalID.'" class="modal modal-fixed-footer">
                        <div class="modal-content">
                          <h4>Upload Photo</h4>'.$html.' </div>

                      </div>';

        return $sHTML;
    }
    
        
    static public function renderAllPhotos($aPhotos){
        
            $sHTML = '<div class="col l8 m12 s12">
                    <div class="row photostream">';
                        
            foreach($aPhotos as $oPhotos => $iPhotoID){                        
                $oPhoto = new Photo();
                $oPhoto->load($iPhotoID->photoID);
                $oUser = new User();
                $oUser->load($iPhotoID->userID);
                $sHTML .= '
                
                <div class="col l4 m12 photo-stream card">
                  <div class="image-holder"><a class="modal-trigger" href="#modal'.$iPhotoID->photoID.'"><img class="object-fit_scale-down" src="'.$iPhotoID->photoPath.'"/></a></div><div class="image-details">
                  <hr/>
                  <ul>
                    <li><a href="#">'.htmlentities($oUser->username).'</a></li>
                  </ul>
                  </div>              
              </div>
                
                
                ';
                
                $oView = new View();
                echo $oView::renderModalViewPhoto($iPhotoID->photoID, $oPhoto);
            }
            $sHTML .='</div>
                </div>';
        
        return $sHTML;
    }
    
    static public function renderPhotoStreamUserProfile($oPhotos){
        
        $oAlbum = new Album();
        if(count($oPhotos)!=0){
            $oAlbum->load($oPhotos[0]->albumID);  
        }else{
            $oAlbum->albumName = "";
        }
        
        
        $sHTML = '<div class="row">
                       <div class="row card center"><h5 class="grey-text text-darken-2">'.htmlentities($oAlbum->albumName).'</h5></div>
                       <div class="col l4 m12 photo-stream card upload-button image-holder">
                       <a class="modal-trigger" href="#modal0"><img class="" src="assets/images/camera.png"/></a>
                       </div>
                       
                       ';

        
        foreach($oPhotos as $aPhotoID => $oPhotoID){
            $oPhoto = new Photo();
            $oPhoto->load($oPhotoID->photoID);
            $oUser = new User();
            $oUser->load($oPhotoID->userID);
            
              $sHTML .= '<div class="col l4 m12 photo-stream card">
              <div class="image-holder "><a class="modal-trigger modal-trigger-photo" data-value="'.$oPhoto->photoID.'" href="#modal'.$oPhoto->photoID.'"><img class="object-fit_scale-down" onClick="likeRefresh()" src="'.$oPhoto->photoPath.'"/></a></div><hr/><div class="image-details"><ul>
              <li><a href="#">'.htmlentities($oUser->username).'</a><span id="likes" class="grey-text text-darken-2">'.count($oPhoto->likes).' Likes</span></li>
              <li>'.htmlentities($oPhoto->caption).'</li>
              </ul></div>
              
              </div>
              
              <div id="delete'.$oPhoto->photoID.'" class="modal">
                    <div class="modal-content">
                      <h4>Delete Photo</h4>
                      <p>Are you sure you want to delete this photo?</p>
                      <a class="btn-floating btn-large waves-effect waves-light red left modal-trigger" href="delete.php?photoID='.$oPhoto->photoID.'">Yes</a>                      
                    </div>
                  </div>
              ';    
            $oView = new View();
            echo $oView::renderModalViewPhoto($oPhoto->photoID, $oPhoto);
        }
        
        return $sHTML . '                        <div class="row"></div>
                    </div></div>';
        
        
    }
    
    static public function renderModalViewPhoto($iModalID, $oPhoto){
        
        $oUser = new User();
        $oUser->load($oPhoto->userID);
        
        $sHTML = '
                    
        
                            <div id="modal'.$iModalID.'" class="modal modal-fixed-footer">
                                <div class="modal-content all-photos  row">  
                                    <div class="col l8 m12 push-l4">
                                        <img class="view-photo card" src="'.$oPhoto->photoPath.'"/>
                                        <div class="image-details">
                                            <h5><a href="#">'.htmlentities($oUser->username).'</h5>
                                            <p>'.htmlentities($oPhoto->caption).'<p>                                          
                                        </div>
                                    </div>
                                    <div class="col l4 m12  pull-l8">
                                    
                                    
                                    ';
        
//        $oLike = new Like();
//        $oLike->loadByPhotoID($oPhoto->photoID, $_SESSION["UserID"]);
//        if($oLike->liked){
//            
//            $sHTML .=   '        <a class="btn-floating btn-large waves-effect waves-light green left like-photo like-update" href="likeupdate.php?photoID='.$oPhoto->photoID.'"><i class="material-icons thumb">thumb_up</i></a> <span class="like-feedback">'.count($oPhoto->likes).'</span> Likes';
//            
//        }else{
//            $sHTML .=   '        <a class="btn-floating btn-large waves-effect waves-light red left like-photo like-update" href="likeupdate.php?photoID='.$oPhoto->photoID.'"><i class="material-icons thumb">thumb_down</i></a> <span class="like-feedback">'.count($oPhoto->likes).'</span> Likes';
//        }
                                    
                                    
        
                            
        if(isset($_SESSION["UserID"])){              
        $sHTML .= '
        <a class="btn-floating btn-large waves-effect waves-light green left like-photo like-update" href="likeupdate.php?photoID='.$oPhoto->photoID.'"><i class="material-icons thumb">thumb_up</i></a> 
        <a class="like-refresh" href="likerefresh.php?photoID='.$oPhoto->photoID.'"></a>
        ';
        }
        
        if(isset($_SESSION["UserID"]) && $oPhoto->userID == $_SESSION["UserID"]){
            
        $sHTML .= '
        <a class="btn-floating btn-large waves-effect waves-light red left modal-trigger" href="#delete'.$oPhoto->photoID.'"><i class="material-icons">delete</i></a>
        
        ';
        }
        
        $sHTML .= '<span class="like-feedback">';
        if(count($oPhoto->likes) == 0){
            $sHTML .= 'Be the first person to like this';
            
        }if(count($oPhoto->likes) >= 1){
            $sHTML .= count($oPhoto->likes) . ' Likes this';
        }
        $sHTML .= '</span>';
        
        $aAllComments = $oPhoto->comments;
                    
        if(isset($_SESSION["UserID"])){      
            $sHTML.= '      <div class="row">
                                    <div class="row"></div>
                                    <div class="col l12 comments">';
            
            
            foreach($aAllComments as $iComments => $oComments){ 
                $oComment = new Comment();
                $oComment->load($oComments->commentID);
                $oUser = new User();
                $oUser->load($oComment->userID);
                $sHTML .= '        <ul class="comments-list">
                                        <li><a href="">'.htmlentities($oUser->username).'</a></li>
                                        <li>'.htmlentities($oComment->comment).'</li>';
                                        
                                        
                if(isset($_SESSION["UserID"]) && $oComment->userID == $_SESSION["UserID"]){
                    
                $sHTML .=     '         <a class="delete-comment" href="delete.php?commentID='.$oComment->commentID.'"><i class="fa fa-times-circle"></i></a>';
                                        
                }
                
                $sHTML .= '</ul>';
                
                }
            
            
            
            $oFormComment = new Form();
            $oFormComment->makeTextArea("Write something...", "comment");
            
            $sHTML .= '<div id="comment-feedback" class="col l12"></div>
            
                                    </div>

                                </div>

                       </div>
                       <div class="col l6 push-l4 add-comment input-field">'.$oFormComment->html.'
                                           <a class="enter-comment" href="addcomment.php?photoID='.$oPhoto->photoID.'" data-value="'.$oPhoto->photoID.'"><i class="fa ">Send</i></a>
                               '  ;
                                   
        }
                               
          $sHTML .= '       </div>
                            
                             
                          </div>

                      </div>';

        return $sHTML;
        
        
    }
    
    static public function renderModalViewAlbums($iUserID){
        
        $oUser = new User();
        $oUser->load($iUserID);
        
        $aAllAlbums = $oUser->albums;
        
    
        $sHTML = '
                        <div id="modalViewAlbums'.$iUserID.'" class="modal modal-fixed-footer">
                            <div class="modal-content all-albums row">';
        
        foreach($aAllAlbums as $iAlbum => $oAlbum){
            
        
        $sHTML .=              '<div class="col l4 card album-image-container"><br/>
                                    <a href="userprofile.php?albumID='.$oAlbum->albumID.'"><img src="assets/images/icon-image.png"/></a>
                                    <div class="album-details">
                                        <h5><a href="userprofile.php?albumID='.$oAlbum->albumID.'">'.htmlentities($oAlbum->albumName).'</a></h5>
                                        <p>'.count($oAlbum->photos).' Photos</p>
                                         <a class="btn-floating btn-large waves-effect waves-light red " href="delete.php?albumID='.$oAlbum->albumID.'"><i class="material-icons">delete</i></a>
                                    </div>
                                </div>
                                ';
            }
                                    
                                    
        $sHTML .= '        </div> 
                        </div>';

        return $sHTML;
        
        
    }
    
    static public function renderPhotoAlbumNav($aAllAlbums, $currentAlbumID){
        
        $oAlbum = new Album();
        $oAlbum->load($currentAlbumID);
        
        $oForm = new Form();
        $oForm->makeTextInput("Album Name", "album_name");
        $sForm = $oForm->html;
        
        $sHTML = '                <div class="col l8"><h2 class="header grey-text text-darken-2">My Photo Stream</h2>
                    <div class="row">    
                       <ul>
                            <li><div class="input-field col s12 l4 m6">
    <select id="select-album">
      <option value="" disabled selected>'.htmlentities($oAlbum->albumName).'</option>
      ';
            foreach($aAllAlbums as $aAlbumID => $iAlbumID){
                
                $oAlbum = new Album();
                $oAlbum->load($iAlbumID);
                $sHTML .= '<option value="'.$iAlbumID.'"><a href="?albumID='.$iAlbumID.'">'.htmlentities($oAlbum->albumName).'</a></option>';
            }
            
        $sHTML .= '
      
    </select><label>Select Album</label>
  </div></li>
  <li><div class="col l4 m4">'. $sForm . '</div><a type="submit" id="add-album" class="btn-floating btn-large waves-effect waves-light cyan"><i class="material-icons">add</i></a> <a href="#modalViewAlbums'.$oAlbum->userID.'" class="btn-floating btn-large waves-effect waves-light cyan modal-trigger"><i class="material-icons">view_module</i></a></li>

  <li><div id="feedback"></div></li>
                        </ul>
                    </div>';
        
        $oView = new View();
        echo $oView::renderModalViewAlbums($oAlbum->userID);
        
        return $sHTML;
    }
    
    
    
}

?>
