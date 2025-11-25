<?php
add_action( 'wp_body_open', function(){

    $before_option = get_option( 'lightning_theme_options' );

    $tests_array = [
        [
            'correct' => false,
            'option' => [
                'header_layout' => 'nav-float',
            ],
        ],
        [
            'correct' => false,
            'option' => [
                'header_layout' => 'center',
            ],
        ],
        [
            'correct' => true,
            'option' => [
                'header_layout' => 'center_and_nav-penetration',
            ],
        ],
        [
            'correct' => true,
            'option' => [
                'header_layout' => 'head-sub-contact_and_nav-penetration',
            ],
        ],
        [
            'correct' => true,
            'option' => [
                'header_layout' => 'head-sub-widget_and_nav-penetration',
            ],
        ],
    ];
    // print '<pre style="text-align:left">';print_r($tests_array);print '</pre>';

    foreach ( $tests_array as $test ){
        // delete_option( 'lightning_theme_options' );
        // update_option( 'lightning_theme_options', $test['option'] );


        $return = VK_Header_Color::is_global_nav_penetration( $test['option']['header_layout'] );
        echo 'return : ' . $return . '<br>';
        echo 'correct : ' . $test['correct'] . '<br>';
        if ( $return !== $test['correct'] ){

        }
    }
    // delete_option( 'lightning_theme_options' );
    // update_option( 'lightning_theme_options', $before_option );
});


