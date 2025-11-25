<?php
/*-------------------------------------------*/
/*  Load modules
/*-------------------------------------------*/
if ( ! class_exists( 'Lightning_Copyright_Custom' ) ) {
	require_once dirname( __FILE__ ) . '/package/class-copyright-customizer.php';
	global $vk_copyright_customizer_prefix;
	$vk_copyright_customizer_prefix = 'Lightning ';

	global $vk_copyright_customizer_priority;
	$vk_copyright_customizer_priority = 543;
}
