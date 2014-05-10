<div id="umd_user_list_dropdown">
<?php
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
				updateUserData(<?php echo $GET_user_id; ?>);
			});
		</script>
<?php
	endif;

	wp_dropdown_users($user_args);
?>
</div>
