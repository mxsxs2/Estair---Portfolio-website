var HomeAuto=true;
function Home_pic_change(){
    if($("img").is("#homepic") && HomeAuto==true){
        Counter=$("span#home_counter").html();
        Counter++
        if($("input").is("#i"+Counter)){
            img_path=$("input#i"+Counter).val();
        }else{
            img_path=$("input#i1").val();
            Counter=1;
        }
        var img_obj = new Image();                     //Make a new image object
        $(img_obj).load(function () {                  //Load the image        
                    $(this).hide();                    //Hide the image     
                    $("#load").html('')                //Empty the container
                              .append(this);        
                    $("img#homepic").fadeOut('medium',function(){
                        $("img#homepic").attr('src',img_path);
                        $("span#home_counter").html(Counter);    
                    })
                                    .fadeIn('medium');             
               })
               .attr("src",img_path);
        setTimeout("Home_pic_change()", 3000);
    }
};