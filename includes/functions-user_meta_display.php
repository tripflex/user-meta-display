<?php
add_action( 'wp_ajax_umd_return_raw_data', 'umd_return_raw_data' );
add_action( 'wp_ajax_umd_change_user_list_dropdown', 'umd_change_user_list_dropdown' );
add_action( 'wp_ajax_umd_remove_user_meta', 'umd_remove_user_meta' );
add_action( 'wp_ajax_umd_edit_user_meta', 'umd_edit_user_meta' );

function umd_remove_user_meta(){
	check_ajax_referer( 'umd_remove_user_meta', 'security' );
	$userid = $_POST['userid'];
	$metakey = $_POST['metakey'];
	$metavalue = $_POST['metavalue'];

	$results = delete_user_meta( $userid, $metakey, $metavalue );

	if($results){
		echo '1';
	} else {
		echo '0';
	}
	die;
}
function umd_edit_user_meta(){
	check_ajax_referer( 'umd_edit_user_meta', 'security' );
	$userid = $_POST['userid'];
	$metakey = $_POST['metakey'];
	$metavalue = $_POST['metavalue'];
	$metaprevalue = $_POST['metaprevalue'];

	if($metavalue === $metaprevalue){
		echo '2';
		die;
	}

	if($metaprevalue){
		$results = update_user_meta( $userid, $metakey, $metavalue, $metaprevalue );
	} else {
		$results = add_user_meta( $userid, $metakey, $metavalue );
	}

	if($results){
		echo '1';
	} else {
		echo '0';
	}
	die;
}
function umd_return_raw_data(){
	check_ajax_referer( 'umd_return_raw_data', 'security' );
	$user_id = intval($_POST['userid']);
	if ($user_id && $user_id != -1) {
		$found_user_meta = array_map( function( $a ){ return $a[0]; }, get_user_meta( $user_id ) );
		if($found_user_meta){
			$umd_remove_user_meta = wp_create_nonce( 'umd_remove_user_meta' );
			$umd_edit_user_meta = wp_create_nonce( 'umd_edit_user_meta' );
			?>
			<script>
				function umdEditUserMeta(meta_key, meta_value, user_id, meta_pre_value){
					// Need to decode HTML code
					var meta_value_unescaped = jQuery('<div/>').html(meta_value).text();
					if(meta_pre_value){
						addOrEdit = '<?php echo __("edit"); ?>';
					} else {
						addOrEdit = '<?php echo __("add"); ?>';
					}
					jQuery.ajax(ajaxurl, {
						type: 'POST',
						dataType: 'html',
						data: {
							action: 'umd_edit_user_meta',
							userid: user_id,
							metakey: meta_key,
							metavalue: meta_value_unescaped,
							metaprevalue: meta_pre_value,
							security: '<?php echo $umd_edit_user_meta; ?>'
						},
						beforeSend: function () {
							umdShowModalLoader();
						},
						error: function(request, status, error) {
							umdUpdateModal('<?php echo __("Ajax error ' + addOrEdit + 'ing Meta!"); ?><br>' + error);
						},
						success: function(data) {
							if(data == 1){
								umdUpdateUserData(user_id);
								umdUpdateModalStatus('<?php echo __("Meta ' + addOrEdit + 'ed successfully!"); ?>', false);
							} else if(data == 2){
								umdUpdateModalStatus('<?php echo __("Existing and new meta are the same."); ?>', false);
							} else if(data == 0){
								umdUpdateModalStatus('<?php echo __("Error ' + addOrEdit + 'ing meta!"); ?>', true);
							} else {
								umdUpdateModalStatus('<?php echo __("Unknown error ' + addOrEdit + 'ing!"); ?>', true);
							}
						},
						complete: function(){
							umdModalFade(true);
						}
					});
				}
				function umdRemoveUserMeta(meta_key, meta_value, user_id){
					// Need to decode HTML code
					var meta_value_unescaped = jQuery('<div/>').html(meta_value).text();
					jQuery.ajax(ajaxurl, {
						type: 'POST',
						dataType: 'html',
						data: {
							action: 'umd_remove_user_meta',
							userid: user_id,
							metakey: meta_key,
							metavalue: meta_value_unescaped,
							security: '<?php echo $umd_remove_user_meta; ?>'
						},
						beforeSend: function () {
							umdShowModalLoader();
						},
						error: function(request, status, error) {
							umdUpdateModal('<?php echo __("Ajax error Removing Meta!"); ?><br>' + error);
						},
						success: function(data) {
							if(data == 1){
								jQuery('.umd-metakey-' + meta_key).remove();
								umdUpdateModalStatus('<?php echo __("Meta successfully removed!"); ?>', false);
							} else if(data == 0){
								umdUpdateModalStatus('<?php echo __("Error removing meta!"); ?>', true);
							} else {
								umdUpdateModalStatus('<?php echo __("Unknown error removing!"); ?>', true);
							}
						},
						complete: function(){
							umdModalFade(true);
						}
					});
				}
			</script>
			<script src="<?php echo plugins_url( '../assets/js/scripts-user_meta_display.min.js' , __FILE__ ); ?>"></script>
			<?php
			echo '<table class="form-table">
				<thead>
					<tr>
						<th class="key-column">Key</th>
						<th class="value-column">Value</th>
					</tr>
				</thead>
				<tbody>';
			foreach( $found_user_meta as $key => $value ) :
					if ( apply_filters( 'umd_ignore_user_meta_key', false, $key ) )
						continue;
//						$value = var_export( $value, true );
				echo '<tr class="umd-meta-row umd-metakey-' . esc_html( $key ) . '" data-metakey="' . esc_html( $key ) . '" data-userid="' . intval( $user_id ) . '">
					<td class="key-column">
					<a href="#" data-userid="' . intval( $user_id ) . '" data-metakey="' . esc_html( $key ) . '" class="umd-remove-' . esc_html( $key ) . ' umd-remove-button button button-primary hidden">Remove</a>
					<a href="#" data-userid="' . intval( $user_id ) . '" data-metakey="' . esc_html( $key ) . '" class="umd-edit-' . esc_html( $key ) . ' umd-edit-button button hidden">Edit</a>' . esc_html( $key ) . '</td>
					<td class="value-column"><code>' . esc_html( $value ) . '</code></td>
				</tr>';
			endforeach;
				echo '</tbody>
			</table>';
			die;
		}
	}
}

function umd_change_user_list_dropdown() {
	check_ajax_referer( 'umd_change_user_list_dropdown', 'security' );
	
	$user_list_type = intval($_POST['userlist']);

	if ($user_list_type){

		$POST_userid = intval($_POST['userid']);
		$user_args = array();

		if ($user_list_type == 1) {
			// ID
			$user_args = array('show' => 'ID', 'show_option_none' => 'Select User ID', 'class' => 'user-meta-display-user-dropdown');
		} elseif ($user_list_type == 2){
			// User Login
			$user_args = array('show' => 'user_login', 'show_option_none' => 'Select User Login', 'class' => 'user-meta-display-user-dropdown');
		} elseif ($user_list_type == 3){
			// Display Name
			$user_args = array('show' => 'display_name', 'show_option_none' => 'Select User Display Name', 'class' => 'user-meta-display-user-dropdown');
		}

		if($POST_userid) $user_args['selected'] = $POST_userid;

		wp_dropdown_users($user_args);

		die;
	}
}

add_filter( 'user_row_actions', 'umd_filter_user_row_actions', 10, 2 );

function umd_filter_user_row_actions( array $actions, WP_User $user ) {
	$link = admin_url( 'users.php?page=user_meta_display&user_id=' . $user->ID );
	$actions['user_meta'] = '<a href="' . $link . '">' . __( 'Meta', 'user-meta-display' ) . '</a>';
	return $actions;
}

?>
