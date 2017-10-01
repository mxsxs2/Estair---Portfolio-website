(function($) {
    $.core = {
        parameters : {
          current :     0,
          winHeight: parseInt($(window).height()),
          winWidth:  parseInt($(window).width()),
        },
        _makeBox : function (){
                box=this;
                box.parent=$("<div>").attr({id: 'box_parent'})
                                     .appendTo($("body"))
                                     .css({zIndex: 1000}); 
                    box.Navi=$("<div>").attr({id: 'box_navi'})
                                       .appendTo(box.parent);
                        box.imageContainer=$("<div>").attr({id: "box_imageContainer"})
                                                     .appendTo(box.Navi);
                        box.close=$("<div>").attr({id: 'box_close'})
                                            .appendTo(box.Navi)
                                            .bind('click', function(){
                                                box._Close();
                                            });    
                        box.descContainer=$("<div>").attr({id: "box_descContainer"})
                                                    .appendTo(box.Navi);
                            box.Title=$("<span>").attr({id: "box_descTitle"})
                                                 .appendTo(box.descContainer);                   
                            box.Desc=$("<p>").attr({id: "box_descDesc"})
                                             .appendTo(box.descContainer);   
                    box.leftArrow=$("<div>").attr({id: 'box_leftArrow'})
                                            .addClass('box_arrow')
                                            .appendTo(box.parent)
                                            .bind('click', function(){
                                                box._setImage(this);
                                            });
                    box.rightArrow=$("<div>").attr({id: "box_rightArrow"})
                                             .addClass('box_arrow')
                                             .appendTo(box.parent)
                                             .bind('click', function(){
                                                box._setImage(this);
                                             });
                    box.Overlay=$('<div>').attr({id: 'box_overlay'})
					                      .appendTo($('body'))
                                          .css({height:  $(window).height()})
                                          .bind('click', function(){
                                                box._Close();
                                          });
                                                       
        },
        _addImage : function (id){
                box=this;
                sz=1;
                srcBase=$("#h_"+id).val().split("_!_");
                imgSrc="imgs/"+srcBase[0]+"/"+srcBase[1];
                imgTitle=$("#t_"+id+" > span.photo_title").html();
                imgDesc=$("#t_"+id+" > span.photo_desc").html();
                var img = new Image();                      //Make a new image object    
                $(img).load(function () {                   //Load the image        
                        $(this).hide();                     //Hide the image        
                        box.imageContainer.html('');              //Empty the container
                        box.descContainer.hide();                 //Hide description container
                        box.imageContainer.append(this);          //Add the image into container
                            $(this).css({maxWidth : box.parameters.winWidth-350,
                                         maxHeight : box.parameters.winHeight-5});
                        images=this;          
                        box._setSize(function(){                //Resize and centre
                                $(images).fadeIn();             //Show the image
                                box.descContainer.show();       //Show description container
                                box.parameters.current=id;      //Change the current image ID
                        });                                  
                                                                
                  })
                  .attr({ src :imgSrc,                            
                          id  : 'showedimg',
                          alt: imgTitle});        
                box.Title.html(imgTitle);
                box.Desc.html(imgDesc);
        },
        _Resize : function(){
               box.parameters.winHeight=parseInt($(window).height()),
               box.parameters.winWidth=parseInt($(window).width()),
               box=this;
               img=box.imageContainer.find("img");
               img.css({maxWidth : box.parameters.winWidth-350,
                        maxHeight : box.parameters.winHeight-5});
               box._setSize(function(){});        
        },
        _setImage : function(ClickedArrow){
            img=this.parameters.current;
            Number(img);
            if($(ClickedArrow).attr('id')=="box_leftArrow"){
                img--;
            }else{
                img++;
            }
            if($("#h_"+img).is(".small_hidden")){
                this._addImage(img);
            } 
        },
        _setSize : function(callback){
            var self=this;
                contWidth=self.imageContainer.find($("img")).width();
                contHeight=self.imageContainer.find($("img")).height();
                self.imageContainer.height(contHeight);
                self.Navi.animate({ width: contWidth+10 }, 200, function(){
                            self.imageContainer.width(contWidth);
                    });    
                callback();                
        },
        _overlay : function(status) {       
			box=this;
            switch( status ) {
				case 'show':
					box.Overlay.show();
				    break;
				default:
					box.Overlay.hide();
				    break;
			};
            $(window).resize(function(){
                  box.Overlay.css({height: $(window).height()});
            });
        },
        _Open : function (){
            this._makeBox();
        },
        _Close : function(){
            this._overlay('hide');
            this.parent.hide();
            this.Navi.css('width', '100');
            this.imageContainer.html('');
        }, 
        _ShowBox :function(){
          this._overlay('show'); 
          this.parent.show();
          this.parent.css("position", "fixed"); 
        }
       
    };
    $.Box=function(){
        var self=this;
        $.core._Open();
    };
    $.fn.Current=function(){
        id=$(this).attr("id").split("_");
        $.core._ShowBox();
        $.core._addImage(id[1]);
    }
    $(window).resize(function(){
        $.core._Resize();
    });
})(jQuery);
$(document).ready(function(){   
    $.Box();
});