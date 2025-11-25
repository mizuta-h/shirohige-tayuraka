<?php

/*-------------------------------------------*/
/*  Load modules
/*-------------------------------------------*/

if ( ! class_exists( 'VK_Custom_Field_Builder' ) ) {

	require dirname( __FILE__ )  . '/package/custom-field-builder.php';

	global $custom_field_builder_url;

	$custom_field_builder_url = LTG3_PRO_DIRECTORY_URL . '/inc/custom-field-builder/package/';

}
