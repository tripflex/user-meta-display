<?php
/**
 * @package   User_Meta_Display
 * @author    Myles McNamara <myles@hostt.net>
 * @license   GPL-2.0+
 * @link      http://smyl.es
 * @copyright 2014 Myles McNamara
 *
 * @wordpress-plugin
 * Plugin Name: User Meta Display
 * Plugin URI:  http://github.com/tripflex/user-meta-display
 * Description: Output/display user meta selected from a dropdown list of current users
 * Version:     1.2.2
 * Author:      Myles McNamara
 * Author URI:  http://smyl.es
 * Text Domain: user-meta-display
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'class-user-meta-display.php' );
require_once( plugin_dir_path( __FILE__ ) . '/includes/functions-user_meta_display.php' );

require_once( plugin_dir_path( __FILE__ ) . '/includes/settings.php' );

// Register hooks that are fired when the plugin is activated or deactivated.
// When the plugin is deleted, the uninstall.php file is loaded.
register_activation_hook( __FILE__, array( 'User_Meta_Display', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'User_Meta_Display', 'deactivate' ) );

// Load instance
add_action( 'plugins_loaded', array( 'User_Meta_Display', 'get_instance' ) );
//User_Meta_Display::get_instance();
?>