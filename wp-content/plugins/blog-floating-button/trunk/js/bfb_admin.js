//画像選択
(function($){

    var custom_uploader;
 
    $("input:button[name=bfb_select_banner]").click(function(e) {
 
        var target = $(this).attr('tdata');
        $("[name=target]").val(target);

        e.preventDefault();
 
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
 
        custom_uploader = wp.media({
            title: "画像を選択してください。",
            library: { type: "image" },
            button: { text: "画像を選択" }, 
            multiple: false
        });
 
        custom_uploader.on("select", function() {

            var images = custom_uploader.state().get("selection");
 
            images.each(function(file){

                var target = $("[name=target]").val();
                var url = file.attributes.url;
                var thumbnail = file.attributes.sizes.thumbnail.url;

                $("input:text[name="+target+"]").val("");
                $("input:text[name="+target+"]").val(url);
                $("#media").append('<img src="'+thumbnail+'" />'); 
            });
        });
 
        custom_uploader.open();
 
    });
  
})(jQuery);

//タブ切り替え
jQuery(function() {

    jQuery( "#tabs" ).tabs();
    jQuery( "#inner-tabs-pc" ).tabs();
    jQuery( "#inner-tabs-sp" ).tabs();

    var param = location.search;
    var tabs1 = bfb_getParam('tabs1',param);
    var tabs2 = bfb_getParam('tabs2',param);

    if( tabs1 > 0 ){
        jQuery( "#tabs" ).tabs({active: tabs1});
    }
    if( tabs2 > 0 ){
        if( tabs1 == 1 ){
            jQuery( "#inner-tabs-pc" ).tabs({active: tabs2});
        }else if( tabs1 == 2 ){
            jQuery( "#inner-tabs-sp" ).tabs({active: tabs2});
        }
    }

    jQuery('#tabs > ul > li').on('click',function(){
        
        var form_action = jQuery('#bfb_form').attr('action');

        var tabs1_index = jQuery(this).index();
        var tabs2_index = '';
        var page = bfb_getParam('page',form_action);

        form_action = jQuery('#bfb_form').attr('action','?page='+page+'&'+'tabs1='+tabs1_index);
        jQuery('#bfb_form').attr('tabs1',tabs1_index);
        jQuery('#bfb_form').attr('tabs2',tabs2_index);
    });
    jQuery('[id^="tabs-"] ul li').on('click',function(){
        
        var form_action = jQuery('#bfb_form').attr('action');
        
        var tabs1_index = bfb_getParam('tabs1',form_action);      
        var tabs2_index = jQuery(this).index();
        var page = bfb_getParam('page',form_action);

        var form_action = jQuery('#bfb_form').attr('action','?page='+page+'&tabs1='+tabs1_index+'&tabs2='+tabs2_index);
        jQuery('#bfb_form').attr('tabs1',tabs1_index);
        jQuery('#bfb_form').attr('tabs2',tabs2_index);
    });

});

//パラメータ取得
function bfb_getParam( key, url ){

    if( !url ){ url = window.location.href; }    
    key = key.replace(/[\[\]]/g, "\\$&");

    var regex = new RegExp("[?&]" + key + "(=([^&#]*)|&|#|$)"),
    res = regex.exec(url);

    if( !res ){ return null; }
    if( !res[2] ){ return ''; }

    return decodeURIComponent(res[2].replace(/\+/g, " "));
}

jQuery(function() {

    var window_width = jQuery(window).width();

    if( window_width > 1600 ){
        bfb_stickyHeight();

        jQuery('#tabs > ul > li').on('click',function(){
            bfb_stickyHeight();
        });
        jQuery('[id^="tabs-"] ul li').on('click',function(){
            bfb_stickyHeight();
        });
    }

});
function bfb_stickyHeight(){

    var bfb_main_height = jQuery('#bfb_main').height();
    if( bfb_main_height > 0 ){
        jQuery('#bfb_wrap').css('height',bfb_main_height);
        jQuery('#bfb_sub.bfb_pro').css('height',bfb_main_height);
    }

}

(function($){

  jQuery(".bfb_popup_help").mouseover(function() {

    var marginTop = 0;
    var marginLeft = 20;
    var speed = 0;
    var popupObj = jQuery(".bfb_popup_help_window");

    if (!popupObj.length) {
      popupObj = jQuery("<p/>").addClass("bfb_popup_help_window").appendTo(jQuery("body"));
    }

    popupObj.text(jQuery(this).attr("data-message"));

    var offsetTop = jQuery(this).offset().top + marginTop;
    var offsetLeft = jQuery(this).offset().left + marginLeft;

    popupObj.css({
      "top": offsetTop,
      "left": offsetLeft
    }).show(speed);

  }).mouseout(function() {
    jQuery(".bfb_popup_help_window").text("").hide("fast");
  });

})(jQuery);