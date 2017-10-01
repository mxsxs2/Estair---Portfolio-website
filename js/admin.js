$(document).ready(function(){
    $(".cat_open").on("click", function(){
            self=$(this);    
            id=$(this).attr('id');
            if($("#cat_"+id).css("display")=="none"){
                $(".cat_hidden").css("display", "none");
                $(this).html(function(index,oldhtml){
                    return oldhtml+'<img src="../css/img/load.gif" height="25px" style="float:right" id="cat_load"/>';
                }); 
                $.ajax({
                    url : 'pages.php',
                    type: 'GET',
                    data: 'cat='+id,
                    dataType: 'html',
                    beforeSend: function(){
                       
                    },
                    success: function(html){
                        $("img#cat_load").remove();
                        $("#cat_"+id).filter(function(){
                              $('html, body').animate({ scrollTop: self.offset().top }, 'fast');
                              $(this).html(html)
                                     .show('fast');
                              theight=$('#cat_tree').height();
                              if(theight>665){
                                  $('#bg').css('height', theight);
                              }else{
                                  $('#bg').css('height', '665px');
                              }
                        });
                    }
                });
            }else{
                $("#cat_"+id).hide();
            }
    });
    $.Box();
    //--------------------------------------home
    $(document).on('click', '#addnewhomepic', function(){
            $(this).Boxmini("app.php","t=ahp");
    })
    .on('mouseenter mouseleave', "div.ahome_s_cont", function(){
             id=$(this).attr("id");
             $("#"+id+" > div.ahome_del").fadeToggle(300)
                                         .click(function(){
                                                    cid=id.split("_");
                                                    $(this).Boxmini("app.php","t=h&del="+$("#i_"+cid[1]).attr('src')+"&i="+id);
             });
    });
    //-----------------logout
    $("#logout").on('click',function(){
       $("#hf").load("login_p.php?lu=true"); 
    });
    //-----------------album
    $(document).on('click',".newalbum",function(){
        $(this).Boxmini("app.php","t=anew"); 
    });
});