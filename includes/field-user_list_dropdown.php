<div id="umd-user-list-dropdown">
<?php
	add_action( 'admin_enqueue_scripts', 'umd_load_wp_dashicons' );
	function umd_load_wp_dashicons() {
		wp_enqueue_style( 'dashicons' );
	}

	$user_args = array(
		'show_option_none' => 'Select User Display Name',
		'class' => 'user-meta-display-user-dropdown'
	);

	$GET_user_id = intval($_GET['user_id']);

	// User ID specified through GET
	if($GET_user_id):
		$user_args['selected'] = $GET_user_id;
?>
		<script>
			jQuery(function($){
				umdUpdateUserData(<?php echo $GET_user_id; ?>);
			});
		</script>
<?php
	endif;

	wp_dropdown_users($user_args);
?>
</div>
<div id="umd-refresh-dropdown">
	<a id="umd-refresh-dropdown-button" class="button" href="#">Refresh</a>
</div>
<div style="clear: both;"></div>
