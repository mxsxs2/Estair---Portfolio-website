ajaxload="";
function fill(loaddiv,hash){ //the hash is an array
    param='';
    if(hash[1]!==undefined){
        param=hash[1];
    }
    ajaxload=$.ajax({
            url : 'php/'+hash[0]+'.php',
            type: 'GET',
            data: param,
            username: hash[0],
            dataType: 'html',
            beforeSend: function(){
                $('body').css('cursor', 'wait');
            },
            complete: function(){},
            success: function(html){
                $("#"+loaddiv).animate({
                    opacity: 0.1,
                    scrollTop: $("#"+loaddiv).offset().top}, 200, function(){
                        $(this).filter(function(){
                               $(this).html(html)
                                      .animate({opacity: 1},200);
                               $('#leftside').css('height', $('#content').innerHeight());
                        });
                        $('html,body').animate({
                                                scrollTop: $("#frame").offset().top},
                                                'slow');
                        });
                $('body').css('cursor', 'auto');
                
            }
    });
}
function classChanger(){
           Hash=window.location.hash.slice(1,window.location.hash.length).split('?');
           if(Hash[0]!=''){
                if($('.menubutton').is('#'+Hash[0]) || Hash[0]=="album"){
                    $(".menubutton").removeClass('current')
                                    .attr("style","");
                    $('#'+Hash[0]).addClass('current');
                    fill('content',Hash);
                    OLD_HASH=Hash[0];
                }
           }else if(OLD_HASH!=""){
                window.location.reload();
           }else{
            Hash[0]="home";
                $(".menubutton").removeClass('current')
                                    .attr("style","");
                    $('#'+Hash[0]).addClass('current');
                    fill('content',Hash);
           }
}
$(document).ready(function(){
        OLD_HASH=window.location.hash.slice(1,window.location.hash.length).split('?');
        $(window).bind("load", function (){
            classChanger();
        });        
        $(window).bind("hashchange", function(){
           classChanger();
        });
        $('.menubutton').on('click', function(){
            window.location.hash=$(this).attr('id');
            if($(this).is(".albumb")){
                text=$(this).html();
            }else{
                text="";
            }
            $('p#albumtext').html(text);
        });
        $('.menubutton').hover(
            function(){
                $(this).animate({backgroundColor: "#A90000", color: '#ffffff'});
            },
            function(){
                if(!$(this).is('.current')){
                    $(this).animate({backgroundColor: "#FF0000", color: "#000000"});
                }
            }
        );
        
});