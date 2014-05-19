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
	'id' => '13774015',
	'master' => 'user_list',
	'fields' => array(
		'user_list'	=>	array(
			'label'		=> 	__('User List','user-meta-display'),
			'caption'	=>	__('How should the dropdown list the users?','user-meta-display'),
			'type'		=>	'onoff',
			'default'	=> 	'1||ID,2||User Login,*3||Display Name',
			'inline'	=> 	true,
		),
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
	'styles'	=> array(
		'toggles.css',
		'styles-user_meta_output.css',
	),
	'scripts'	=> array(
		'toggles.min.js',
		'scripts-user_meta_output.min.js',
	),
	'multiple'	=> false,
);

