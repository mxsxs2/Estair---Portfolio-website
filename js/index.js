$(document).ready(function(){   
/*----------home*/
$(document).ajaxSuccess(function(status,xhr,settings){
    if(settings.username=="home"){
        setTimeout("Home_pic_change()", 3000);
    }
});
    
    
    $("#content").on({mouseenter: function() {
                        $('#home_bar').fadeIn();
                    }, mouseleave: function() {
                        $('#home_bar').fadeOut();
        }});
    $("#content").on('click', "#home_control", function(){
        if(HomeAuto==true){
            HomeAuto=false;
            $("img.home_button").attr('src',"css/img/play.png");
        }else{
            HomeAuto=true;
            Home_pic_change()
            $("img.home_button").attr('src',"css/img/pause.png");
        }   
    });
/*-----------------------contact*/
$("#content").on('click', "input#esend",function(){
    if($('#email').is(".input")){
    $.ajax({
            url : 'php/mail.php',
            type: 'POST',
            data: "name="+$("#ename").val()+"&sender="+$('#email').val()+"&subject="+$('#esubject').val()+'&message='+$('#etext').val(),
            dataType: 'html',
            beforeSend: function(){
                $('body').css('cursor', 'wait');
            },
            complete: function(){},
            success: function(html){
                result=html.split("_");
                $('#back').html(result[0]);
                if(result[1]==1){
                    $(":input").not(":button, :submit, :reset, :hidden").each(
                        function () {
                            this.value = this.defaultValue;
                    });
                }
                $('body').css('cursor', 'auto');
            }
    });
    }
});
/*-----------------------------------------album*/
$('#content').on('click', '.albumbutton', function(){
                            window.location.hash=$(this).attr('id');
                            $('p#albumtext').html($(this).html());
           });
$('#content').on({mouseenter: function(){
                                                    id=$(this).attr('id');
                                                    $("#"+id+" > div.thumb_cover").fadeToggle(300);
                                             },
                                       mouseleave: function(){
                                                    id=$(this).attr('id');
                                                    $("#"+id+" > div.thumb_cover").fadeToggle(300);
                                             },
                                       click : function(){
                                                    $(this).Current();
                                       }
                                       },'div.photo_container');
 $('#content').on({mouseenter: function(){
                                         $(this).animate({backgroundColor: "#D8D8D8", color: '#000000'});
                                  },
                                  mouseleave: function(){
                                         $(this).animate({backgroundColor: "#000000", color: "#ffffff"});
                                 }       
                  },'.albumbutton');





});