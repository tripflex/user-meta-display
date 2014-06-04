<?php
/**
 * User Meta Display.
 *
 * @package   User_Meta_Display
 * @author    Myles McNamara <myles@hostt.net>
 * @license   GPL-2.0+
 * @link      http://smyl.es
 * @copyright 2014 Myles McNamara
 */

/**
 * Plugin class.
 * @package User_Meta_Display
 * @author  Myles McNamara <myles@hostt.net>
 */
class User_Meta_Display {

	/**
	 * @var     string
	 */
	const VERSION = '1.2.2';
	/**
	 * @var      string
	 */
	protected $plugin_slug = 'user-meta-display';
	/**
	 * @var      object
	 */
	protected static $instance = null;
	/**
	 * @var      array
	 */
	protected $element_instances = array();
	/**
	 * @var      array
	 */
	protected $element_css_once = array();
	/**
	 * @var      array
	 */
	protected $elements = array();
	/**
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;
	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_stylescripts' ) );
		
		add_action('wp_footer', array( $this, 'footer_scripts' ) );

		// Detect element before rendering the page so that we can enque scripts and styles needed
		if(!is_admin()){
			add_action( 'wp', array( $this, 'detect_elements' ) );
		}

		
	}

	/**
	 * Return an instance of this class.
	 *
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate( $network_wide ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			if ( $network_wide  ) {
				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					self::single_activate();
				}
				restore_current_blog();
			} else {
				self::single_activate();
			}
		} else {
			self::single_activate();
		}
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			if ( $network_wide ) {
				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					self::single_deactivate();
				}
				restore_current_blog();
			} else {
				self::single_deactivate();
			}
		} else {
			self::single_deactivate();
		}
	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 *
	 * @param	int	$blog_id ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {
		if ( 1 !== did_action( 'wpmu_new_blog' ) )
			return;

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();
	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 *
	 * @return	array|false	The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {
		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";
		return $wpdb->get_col( $sql );
	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 */
	private static function single_activate() {
		// TODO: Define activation functionality here if needed
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 */
	private static function single_deactivate() {
		// TODO: Define deactivation functionality here needed
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 */
	public function load_plugin_textdomain() {
		// TODO: Add translations as need in /languages
		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages' );
	}
	
	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 *
	 * @return    null
	 */
	public function enqueue_admin_stylescripts() {

		$screen = get_current_screen();

		
		if( false !== strpos( $screen->base, 'user_meta_display' ) ){

		}
		

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		if ( in_array( $screen->id, $this->plugin_screen_hook_suffix ) ) {
			$slug = array_search( $screen->id, $this->plugin_screen_hook_suffix );
			//$configfiles = glob( self::get_path( __FILE__ ) .'configs/'.$slug.'-*.php' );
			if(file_exists(self::get_path( __FILE__ ) .'configs/fieldgroups-'.$slug.'.php')){
				include self::get_path( __FILE__ ) .'configs/fieldgroups-'.$slug.'.php';
			}else{
				return;
			}

			if( !empty( $configfiles ) ) {
				// Always good to have.
				wp_enqueue_media();
				wp_enqueue_script('media-upload');

				foreach ($configfiles as $key=>$fieldfile) {
					include $fieldfile;
					if(!empty($group['scripts'])){
						foreach($group['scripts'] as $script){
							if( is_array( $script ) ){
								foreach($script as $remote=>$location){
									$infoot = false;
									if($location == 'footer'){
										$infoot = true;
									}
									if( false !== strpos($remote, '.')){
										wp_enqueue_script( $this->plugin_slug . '-' . strtok(basename($remote), '.'), $remote , array('jquery'), false, $infoot );
									}else{
										wp_enqueue_script( $remote, false , array(), false, $infoot );
									}
								}
							}else{
								if( false !== strpos($script, '.')){
									wp_enqueue_script( $this->plugin_slug . '-' . strtok($script, '.'), self::get_url( 'assets/js/'.$script , __FILE__ ) , array('jquery'), false, false );
								}else{
									wp_enqueue_script( $script );
								}
							}
						}
					}
					if(!empty($group['styles'])){
						foreach($group['styles'] as $style){
							if( is_array( $style ) ){
								foreach($style as $remote){
									wp_enqueue_style( $this->plugin_slug . '-' . strtok(basename($remote), '.'), $remote );
								}
							}else{
								wp_enqueue_style( $this->plugin_slug . '-' . strtok($style, '.'), self::get_url( 'assets/css/'.$style , __FILE__ ) );
							}
						}
					}
				}
			}	
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', self::get_url( 'assets/css/panel.css', __FILE__ ), array(), self::VERSION );
			wp_enqueue_script( $this->plugin_slug .'-admin-scripts', self::get_url( 'assets/js/panel.js', __FILE__ ), array(), self::VERSION );
		}

	}

	
	
	
	/**
	 * Process a field value
	 *
	 */
	public function process_value($type, $value){

		switch ($type){
			default:
				return $value;
				break;
			
		}

		return $value;	

	}


	/**
	 * setup meta boxes.
	 *
	 *
	 * @return    null
	 */
	public function get_post_meta($id, $key = null, $single = false){
		
		if(!empty($key)){

			//$configfiles = glob(self::get_path( __FILE__ ) .'configs/*.php');
			if(file_exists(self::get_path( __FILE__ ) .'configs/fieldgroups-user_meta_display.php')){
				include self::get_path( __FILE__ ) .'configs/fieldgroups-user_meta_display.php';		
			}else{
				return;
			}

			$field_type = 'text';
			foreach( $configfiles as $config=>$file ){
				include $file;
				if(isset($group['fields'][$key]['type'])){
					$field_type = $group['fields'][$key]['type'];
					break;
				}
			}
			$key = 'user_meta_display_' . $key;
		}
		if( false === $single){
			$metas = get_post_meta( $id, $key );
			foreach ($metas as $key => &$value) {
				$value = $this->process_value( $field_type, $value );
			}
			return $metas;
		}
		return $this->process_value( $field_type, get_post_meta( $id, $key, $single ) );

	}


	/**
	 * save metabox data
	 *
	 *
	 */
	function save_post_metaboxes($pid, $post){

		if(!isset($_POST['user_meta_display_metabox']) || !isset($_POST['user_meta_display_metabox_prefix'])){return;}


		if(!wp_verify_nonce($_POST['user_meta_display_metabox'], plugin_basename(__FILE__))){
			return $post->ID;
		}
		if(!current_user_can( 'edit_post', $post->ID)){
			return $post->ID;
		}
		if($post->post_type == 'revision' ){return;}
		
		foreach( $_POST['user_meta_display_metabox_prefix'] as $prefix ){
			if(!isset($_POST[$prefix])){continue;}

			
			delete_post_meta($post->ID, $prefix);
			add_post_meta($post->ID, $prefix, $_POST[$prefix]);
			//foreach($_POST['user_meta_display'] as $field=>$data){
				
				//if(is_array($data)){
				//	foreach($data as $item){
				//		add_post_meta($post->ID, 'user_meta_display_'.$field, $item);
				//	}
				//}else{
				
				//}
			//}
		}
	}	
	/**
	 * create and register an instance ID
	 *
	 */
	public function element_instance_id($id, $process){

		$this->element_instances[$id][$process][] = true;
		$count = count($this->element_instances[$id][$process]);
		if($count > 1){
			return $id.($count-1);
		}
		return $id;
	}

	/**
	 * Render the element
	 *
	 */
	public function render_element($atts, $content, $slug, $head = false) {
		
		$raw_atts = $atts;
		

		if(!empty($head)){
			$instanceID = $this->element_instance_id('user_meta_display'.$slug, 'header');
		}else{
			$instanceID = $this->element_instance_id('user_meta_display'.$slug, 'footer');
		}

		//$configfiles = glob(self::get_path( __FILE__ ) .'configs/'.$slug.'-*.php');
		if(file_exists(self::get_path( __FILE__ ) .'configs/fieldgroups-'.$slug.'.php')){
			include self::get_path( __FILE__ ) .'configs/fieldgroups-'.$slug.'.php';		
		
			$defaults = array();
			foreach($configfiles as $file){

				include $file;
				foreach($group['fields'] as $variable=>$conf){
					if(!empty($group['multiple'])){
						$value = array($this->process_value($conf['type'],$conf['default']));
					}else{
						$value = $this->process_value($conf['type'],$conf['default']);
					}
					if(!empty($group['multiple'])){
						if(isset($atts[$variable.'_1'])){
							$index = 1;
							$value=array();
							while(isset($atts[$variable.'_'.$index])){
								$value[] = $this->process_value($conf['type'],$atts[$variable.'_'.$index]);
								$index++;
							}
						}elseif (isset($atts[$variable])) {
							if(is_array($atts[$variable])){
								foreach($atts[$variable] as &$varval){
									$varval = $this->process_value($conf['type'],$varval);
								}
								$value = $atts[$variable];
							}else{
								$value[] = $this->process_value($conf['type'],$atts[$variable]);
							}
						}
					}else{
						if(isset($atts[$variable])){
							$value = $this->process_value($conf['type'],$atts[$variable]);
						}
					}
					
					if(!empty($group['multiple']) && !empty($value)){
						foreach($value as $key=>$val){
							//if(is_array($val)){
								$groups[$group['master']][$key][$variable] = $val;
							//}elseif(strlen($val) > 0){
							//	$groups[$group['master']][$key][$variable] = $val;
							//}
						}
					}
					$defaults[$variable] = $value;
					/*if(is_array($value)){
						foreach($value as $varkey=>&$varval){
							if(is_array($val)){
								if(!empty($val)){
									unset($value[$varkey]);
								}
							}elseif(strlen($varval) === 0){
								unset($value[$varkey]);
							}
						}
						if(!empty($value)){
							$defaults[$variable] = implode(', ', $value);
						}
					}else{
						if(strlen($value) > 0){
							$defaults[$variable] = $value;
						}
					}*/
				}
			}
			//dump($atts,0);
			//dump($defaults,0);
			$atts = $defaults;
		}
		// pull in the assets
		$assets = array();
		if(file_exists(self::get_path( __FILE__ ) . 'assets/assets-'.$slug.'.php')){
			include self::get_path( __FILE__ ) . 'assets/assets-'.$slug.'.php';
		}

		ob_start();
		if(file_exists(self::get_path( __FILE__ ) . 'includes/element-'.$slug.'.php')){
			include self::get_path( __FILE__ ) . 'includes/element-'.$slug.'.php';
		}else if( file_exists(self::get_path( __FILE__ ) . 'includes/element-'.$slug.'.html')){
			include self::get_path( __FILE__ ) . 'includes/element-'.$slug.'.html';
		}
		$out = ob_get_clean();


		if(!empty($head)){

			// process headers - CSS
			if(file_exists(self::get_path( __FILE__ ) . 'assets/css/styles-'.$slug.'.php')){
				ob_start();
				include self::get_path( __FILE__ ) . 'assets/css/styles-'.$slug.'.php';
				$this->element_header_styles[] = ob_get_clean();
				add_action('wp_head', array( $this, 'header_styles' ) );
			}else if( file_exists(self::get_path( __FILE__ ) . 'assets/css/styles-'.$slug.'.css')){
				wp_enqueue_style( $this->plugin_slug . '-'.$slug.'-styles', self::get_url( 'assets/css/styles-'.$slug.'.css', __FILE__ ), array(), self::VERSION );
			}
			// process headers - JS
			if(file_exists(self::get_path( __FILE__ ) . 'assets/js/scripts-'.$slug.'.php')){
				ob_start();
				include self::get_path( __FILE__ ) . 'assets/js/scripts-'.$slug.'.php';				
				$this->element_footer_scripts[] = ob_get_clean();
			}else if( file_exists(self::get_path( __FILE__ ) . 'assets/js/scripts-'.$slug.'.js')){
				wp_enqueue_script( $this->plugin_slug . '-'.$slug.'-script', self::get_url( 'assets/js/scripts-'.$slug.'.js', __FILE__ ), array( 'jquery' ), self::VERSION , true );
			}
			// get clean do shortcode for header checking
			ob_start();
			do_shortcode( $out );
			ob_get_clean();			
			return;
		}
		
		// CHECK FOR EMBEDED ELEMENTS
		/*foreach($elements as $subelement){
			if(empty($subelement['state']) || $subelement['shortcode'] == $element['_shortcode']){continue;}
			if(false !== strpos($out, '{{:'.$subelement['shortcode'].':}}')){
				$out = str_replace('{{:'.$subelement['shortcode'].':}}', caldera_doShortcode(array(), $out, $subelement['shortcode']), $out);
			}
		}*/


		/*if(!empty($element['_removelinebreaks'])){
			add_filter( 'the_content', 'wpautop' );
		}*/

		return do_shortcode($out);
	}

	/**
	 * Detect elements used on the page to allow us to enqueue needed styles and scripts
	 *
	 */
	public function detect_elements() {
		global $wp_query;

		

		$this->render_element(get_option( "_user_meta_display_options" ), false, 'user_meta_display', true);

	}

	/**
	 * Render any header styles
	 *
	 */
	public function header_styles() {
		if(!empty($this->element_header_styles)){
			echo "<style type=\"text/css\">\r\n";
			foreach($this->element_header_styles as $styles){
				echo $styles."\r\n";
			}			
			echo "</style>\r\n";
		}
	}
	
	/**
	 * Render any footer scripts
	 *
	 */
	public function footer_scripts() {

		if(!empty($this->element_footer_scripts)){
			echo "<script type=\"text/javascript\">\r\n";
				foreach($this->element_footer_scripts as $script){
					echo $script."\r\n";
				}
			echo "</script>\r\n";
		}
	}

	

	/***
	 * Get the current URL
	 *
	 */
	static function get_url($src = null, $path = null) {
		if(!empty($path)){
			return plugins_url( $src, $path);
		}
		return trailingslashit( plugins_url( $path , __FILE__ ) );
	}

	/***
	 * Get the current URL
	 *
	 */
	static function get_path($src = null) {
		return plugin_dir_path( $src );

	}
	
}
