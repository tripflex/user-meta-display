<?php
/**
*
* fieldconfig for user-meta-display/User Meta
*
* @package User_Meta_Display
* @author Myles McNamara myles@hostt.net
* @license GPL-2.0+
* @link http://smyl.es
* @copyright 2014 Myles McNamara
*/


$group = array(
	'label' => __('User Meta','user-meta-display'),
	'id' => '102921112',
	'master' => 'user_to_display',
	'fields' => array(
		'user_to_display'	=>	array(
			'label'		=> 	__('User Meta to Display','user-meta-display'),
			'caption'	=>	__('User to display meta data for','user-meta-display'),
			'type'		=>	'user_list_dropdown',
			'default'	=> 	'EL53276BD276340',
		),
		'user_meta_output'	=>	array(
			'label'		=> 	__('User Meta Output','user-meta-display'),
          'caption'   =>  '',
			'type'		=>	'user_meta_output',
			'default'	=> 	'EL53277177D8B52',
		),
	),
	'scripts'	=> array(
		'scripts-user_meta_output.js',
	),
	'multiple'	=> false,
);

