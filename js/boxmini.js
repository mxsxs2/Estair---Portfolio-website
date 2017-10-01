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
                        box.Container=$("<div>").attr({id: "box_Container"})
                                                     .appendTo(box.Navi);
                        box.close=$("<div>").attr({id: 'box_close'})
                                            .appendTo(box.Navi)
                                            .bind('click', function(){
                                                box._Close();
                                            });    
                    box.Overlay=$('<div>').attr({id: 'box_overlay'})
					                      .appendTo($('body'))
                                          .css({height:  $(window).height()})
                                          .bind('click', function(){
                                                box._Close();
                                          });  
                                                       
        },
        _addContent : function (aurl, param){
                box=this;
                $.ajax({
                    url : aurl,
                    type: "GET",
                    data: param,
                    dataType: 'html',
                    beforeSend: function(){
                        box.Container.html('<img src="../css/img/load.gif" alt="" border="0">');
                    },
                    success: function(html){
                        box.Container.html(html);
                    }
                });
        },
        _setSize : function(callback){
            var self=this;
                contWidth=self.imageContainer.find($("img")).width();
                contHeight=self.imageContainer.find($("img")).height();
                self.imageContainer.height(contHeight-10);
                self.Navi.animate({ width: contWidth+100 }, 200, function(){
                            self.imageContainer.width(contWidth);
                    });    
                callback();                
        },
        _overlay : function(status) {       
			box=this;
			box.Overlay.toggle(status);
            $(window).on('resize',function(){
                  box.Overlay.css({height: $(window).height()});
            });
        },
        _Open : function (){
            this._makeBox();
        },
        _Close : function(){
            this._overlay(false);
            this.parent.hide();
            this.Navi.css('width', '');
            this.Container.html('');
        }, 
        _ShowBox :function(){
          this._overlay(true); 
          this.parent.show();
          this.parent.css("position", "fixed"); 
        }
       
    };
    $.Box=function(){
        $.core._Open();
    };
    $.fn.Boxmini=function(url, param){
        $.core._ShowBox();
        $.core._addContent(url,param);
    };
    $.Boxmini_close=function(){
        $.core._Close();
    };
    $.fn.Current=function(){}
})(jQuery);