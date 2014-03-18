<?php
/**
*
* fieldconfig for user-meta-display/About
*
* @package User_Meta_Display
* @author Myles McNamara myles@hostt.net
* @license GPL-2.0+
* @link http://smyl.es
* @copyright 2014 Myles McNamara
*/


$group = array(
	'label' => __('About','user-meta-display'),
	'id' => '215351210',
	'master' => 'about',
	'fields' => array(
		'about'	=>	array(
			'label'		=> 	__('About','user-meta-display'),
          'caption'   =>  '',
			'type'		=>	'about_field_type',
			'default'	=> 	'EL53276C8DCFABD',
		),
	),
	'multiple'	=> false,
);

