<?php
function lightning_is_base_active(){

	$base = false;

	// $base = lightning_is_base_active_by_skin();

	/*  Base setting of site general
	/*-------------------------------------------*/
	$options = get_option('lightning_theme_options');

	if ( isset($options['section_base']) && $options['section_base'] === 'use' ){
		$base = true;
	}

	/*  Base setting of specific page 
	/*-------------------------------------------*/
	if ( is_singular() ){
		global $post;
		$cf = $post->_lightning_design_setting;
		if ( isset( $cf['section_base'] ) ){
			if ( $cf['section_base'] == 'no' ){
				$base = false;
			} else if ( $cf['section_base'] == 'use' ){
				$base = true;
			}
		}
	}

	$base = apply_filters( 'lightning_is_base_active', $base );

	return $base;
}

add_filter( 'lightning_get_class_names', 'lightning_add_class_base_section',15 );
function lightning_add_class_base_section( $class_names ) {
	if ( lightning_is_base_active() ){
		$class_names['site-body'][] = 'site-body--base--on';
		$class_names['main-section'][] = 'main-section--base--on';
		$class_names['sub-section'][] = 'sub-section--base--on';
	} else {
		unset( $class_names['site-body']['site-body--base--on'] );
		unset( $class_names['main-section']['main-section--base--on'] );
		unset( $class_names['sub-section']['sub-section--base--on'] );
	}
	return $class_names;
}