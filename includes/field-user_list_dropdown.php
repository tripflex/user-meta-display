<div id="umd_user_list_dropdown">
<?php
	$user_args = array(
		'show_option_none' => 'Select User Display Name',
		'class' => 'user-meta-display-user-dropdown'
	);

	// User ID specified through GET
	if(intval($_GET['user_id'])):
		$user_args['selected'] = intval($_GET['user_id']);
?>
		<script>
			jQuery(function($){
				$('#user').click();
			});
		</script>
<?php
	endif;

	wp_dropdown_users($user_args);
?>
</div>
