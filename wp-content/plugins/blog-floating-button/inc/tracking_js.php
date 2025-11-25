<?php

    if ( is_home() || is_front_page() ){
        $post_id = 0;
    }else{
        $post_id = get_the_ID();
    }

    $url = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $referer = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
    $ua = $_SERVER['HTTP_USER_AGENT'];

    if( isset($this->optimize_id) ){
        $optimize_id = $this->optimize_id;
    }else{
        $optimize_id = '';
    }
    if( isset($this->optimize_type) ){
        $optimize_type = $this->optimize_type;
    }else{
        $optimize_type = '';
    }

?>

<script>

//var bfb_tracking_access_url = '<?php echo plugins_url( 'api/tracking_access.php', dirname(__FILE__) ); ?>';
//var bfb_tracking_click_url = '<?php echo plugins_url( 'api/tracking_click.php', dirname(__FILE__) ); ?>';
var api_endpoint = '<?php echo home_url('/wp-json/bfb/api/bfb_write_log'); ?>';

var bfb_post_id = <?php echo esc_attr($post_id); ?>;
var bfb_post_url = '<?php echo esc_attr($url); ?>';
var bfb_referer = '<?php echo esc_attr($referer); ?>';
var bfb_ua = '<?php echo esc_attr($ua); ?>';

(function($){

    bfb_write_access();

    var bfb_tracking_click_data = {};

    $(document).on("click", '[id^="bfb_content_"] a', function () {

        //e.preventDefault();
        
        var bfb_linked_url = $(this).attr('href');
        var bfb_memo = $(this).attr('bfb-memo');
        var bfb_target = $(this).attr('target');
        var bfb_optimize_id = $(this).attr('bfb-optimize-id');
        var bfb_optimize_type = $(this).attr('bfb-optimize-type');

        bfb_tracking_click_data['linked_url'] = bfb_linked_url;
        bfb_tracking_click_data['memo'] = bfb_memo;
        bfb_tracking_click_data['target'] = bfb_target;
        bfb_tracking_click_data['optimize_id'] = bfb_optimize_id;
        bfb_tracking_click_data['optimize_type'] = bfb_optimize_type;

        bfb_write_click(bfb_tracking_click_data);

    });
    
})(jQuery);

function bfb_write_access(){

    var bfb_optimize_id = '';
    var bfb_optimize_type = '';

    <?php
        if( $this->is_mobile() ){
            if( !empty($this->bfb_optId_sp) && !empty($this->optimize_type_sp) ){
                echo 'bfb_optimize_id = \''.$this->bfb_optId_sp."';\n";
                echo 'bfb_optimize_type = \''.$this->optimize_type_sp."';\n";
            }
        }else{
            if( !empty($this->bfb_optId_pc) && !empty($this->optimize_type_pc) ){
                echo 'bfb_optimize_id = \''.$this->bfb_optId_pc."';\n";
                echo 'bfb_optimize_type = \''.$this->optimize_type_pc."';\n";
            }
        }
    ?>

    var data = {
        post_id: bfb_post_id,
        post_url: bfb_post_url,
        referer: bfb_referer,
        optimize_id: bfb_optimize_id,
        optimize_type: bfb_optimize_type,
    };

    jQuery.ajax({
        url: api_endpoint,
        type: 'post',
        data: {
            'bfb_db_action': 'bfb_tracking_access',
            'data': data,
        },
    }).done(function(res){
    }).fail(function(res){
    }).always(function(res){
    });
}
function bfb_write_click(data){

    var post_data = {
        post_id: bfb_post_id,
        post_url: bfb_post_url,
        linked_url: data['linked_url'],
        ua: bfb_ua,
        memo: data['memo'],
        optimize_id: data['optimize_id'],
        optimize_type: data['optimize_type'],
    };

    try{
        jQuery.ajax({
            url: api_endpoint,
            type: 'post',
            data: {
                'bfb_db_action': 'bfb_tracking_click',
                'data': post_data,
            },
        }).done(function(res){
        }).fail(function(res){
        }).always(function(res){
        });
    }catch( e ) {
    }

}
</script>