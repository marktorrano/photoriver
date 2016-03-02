$(document).ready(function(){
    
    $('.scroll').jscroll();
    
    $('.scroll').jscroll({
    loadingHtml: '<img src="loading.gif" alt="Loading" /> Loading...',
    padding: 20,
    nextSelector: 'a.jscroll-next:last',
    contentSelector: 'li'
    });
    
    $(".button-collapse").sideNav();
    $('input#input_text, textarea#textarea1').characterCounter();
    $(".dropdown-button").dropdown();
    $('select').material_select();

    $('.datepicker').pickadate({
        selectMonths: true, 
        selectYears: 50 
    });
    
    $(".contest").click(function(){
       
        $(this).toggleClass("expand");
        $(this).trans(this, 1);        
        
    });
    
    $('#comment').trigger('autoresize');

    $('.comment-box-trigger').click(function(){
        $('.comments').toggleClass('expand-comment-box');
        $('.add-comment').css('display', 'block');
        trans('comments', 1);
    });
    
    $('.modal-trigger').leanModal({
         complete: function() { 
             $("#comment-feedback").html("");  
        }        
    }); 
      
    
    $('#no').closeModal();
    
    $('#select-album').change(function(){
        var optionSelected = $("option:selected", this);
        var valueSelected = this.value;
        
        location = "userprofile.php?albumID=" + valueSelected;

    });
    
    $('.modal-trigger-photo').click(function(){
        
        var photoID = $(this).data('value');
        $.post("update.php", {photoID: photoID}, function(data){

            aData = JSON.parse(data);
            $(".like-feedback").html(aData + " Likes");

        });
        
        
        
    });
    
    $("#add-album").click(function(){
    
        var album_name = $("#album_name").val();
        if(album_name!=""){
            $.post('addalbum.php', {album_name: album_name }, function(data){
                $('#feedback').html("Album Successfully Added! Page will refresh");
                var newurl = "userprofile.php?albumID=" + data;
                setTimeout(function(){ window.location = newurl }, 1000);
            });
        }else{
            $('#feedback').html("Please enter album name");
        }
    });
    
    $(".like-update").click(function(e){
        e.preventDefault();
    
        url = $(this).attr('href');
        var link = this;
        
        $.get(url,function(data){
            
            aData = JSON.parse(data);
            
            if(aData[0]){
//                liked again, change to dislike button
                    if(aData[1] == 1){
                        $(".like-feedback").html("You like this");                        
                    }if(aData[1] > 1){                        
                        $(".like-feedback").html("You and " + (aData[1]-1) + " other likes this");
                    }
            }else{
//                disliked, change to like button
                    if(aData[1]==0){
                        $(".like-feedback").html("Be the first person to like this");
                    }else{
                        $(".like-feedback").html(aData[1] + " Likes this photo");
                    }
                    
            }
            
            
        });       
        
    });
    
//    $(".photo-stream").click(function(){
//       $(".comments").html('');
//    });
    

    $(".enter-comment").each(function(i,el){
        
        $(el).click(function(e){
            e.preventDefault();
            var photoID = $(this).data("value");         
            
            var comment = $(this).prev().find("textarea").val();

            var that = this;

            if(comment!=""){
                $.post("addcomment.php", {comment: comment, photoID: photoID}, function(data){
                    aData = JSON.parse(data);
                    var NewsHTML = '<ul class="comments-list"><li><a href="">'+aData[0]+'</a></li><li>'+aData[1]+'</li><a class="delete-comment" href="delete.php?commentID='+aData[2]+'"><i class="fa fa-times-circle"></i></a></ul>';

                    $(that).parent().prev().find(".comments").append(NewsHTML);

                });
            }

            $(this).prev().find("textarea").val('');

        });
        
    
    
    });
        
    
    $(".delete-comment").click(function(e){
        e.preventDefault();
    
        url = $(this).attr('href');
        var link = this;
        
        $.get(url,function(){
            $(link).parent().remove();            
        });
       
        
    });

});

(function() {
    "use strict";
    var toggles = document.querySelectorAll(".c-hamburger");
    for (var i = toggles.length - 1; i >= 0; i--) {
      var toggle = toggles[i];
      toggleHandler(toggle);
    };
    function toggleHandler(toggle) {
      toggle.addEventListener( "click", function(e) {
        e.preventDefault();
        (this.classList.contains("is-active") === true) ? this.classList.remove("is-active") : this.classList.add("is-active");
      });
    }
  })();




$(window).load(function () {
    
    $('.Collage').collagePlus(
        {
            'effect' : 'effect-1',
             'allowPartialLastRow' : true,
            'fadeSpeed'       : "slow",
            'direction'       : 'horizontal'
        }    
    );
    
});



function trans(elem, sec) {
    $(elem).css('-webkit-transition' , 'all ' +sec+ 's ease');
    $(elem).css('-moz-transition' , 'all ' +sec+ 's ease');
    $(elem).css('-ms-transition' , 'all ' +sec+ 's ease');
    $(elem).css('-o-transition' , 'all ' +sec+ 's ease');
    $(elem).css('transition' , 'all ' +sec+ 's ease');
}

function likeRefresh(){
        
        url = $(".like-refresh").attr("href");
        
        $.get(url,function(data){
            
            aData = JSON.parse(data);
            
            if(aData[0]==1 && aData[1]){
                //you and no other like the photo
                $(".like-feedback").html("You like this");
//                $(".like-feedback").siblings().prev().find("i").html("thumb_down");
//                $(".like-feedback").siblings().prev().find("i").removeClass("green");
//                $(".like-feedback").siblings().prev().find("i").addClass("red");
            }if(aData[0]>1 && aData[1]){
                //you and others like this
                $(".like-feedback").html("You and " + aData[0] - 1 + " others like this");
//                $(".like-feedback").siblings().prev().find("i").html("thumb_down");
//                $(".like-feedback").siblings().prev().find("i").removeClass("green");
//                $(".like-feedback").siblings().prev().find("i").addClass("red");
            }if(aData[0] == 0){
                //no one liked the photo yet
                $(".like-feedback").html("Be the first person to like this");
//                $(".like-feedback").siblings().prev().find("i").html("thumb_up");
//                $(".like-feedback").siblings().prev().find("i").removeClass("red");
//                $(".like-feedback").siblings().prev().find("i").addClass("green");
            }if(aData[0]>1 && aData[1] == 0){
                //Others like the photo but you havn't
                $(".like-feedback").html(aData[0] + " Likes");
//                $(".like-feedback").siblings().prev().find("i").html("thumb_up");
//                $(".like-feedback").siblings().prev().find("i").removeClass("red");
//                $(".like-feedback").siblings().prev().find("i").addClass("green");
                
            }
            
            
 
        });
    
}
