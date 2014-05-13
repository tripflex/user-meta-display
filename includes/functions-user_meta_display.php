<?php
add_action( 'wp_ajax_umd_return_raw_data', 'umd_return_raw_data' );
add_action( 'wp_ajax_umd_change_user_list_dropdown', 'umd_change_user_list_dropdown' );
add_filter( 'user_row_actions', 'umd_filter_user_row_actions', 10, 2 );

function umd_filter_user_row_actions( array $actions, WP_User $user ) {
	$link = admin_url( 'users.php?page=user_meta_display&user_id=' . $user->ID );
	$actions['user_meta'] = '<a href="' . $link . '">' . __( 'Meta', 'user-meta-display' ) . '</a>';
	return $actions;
}

function umd_return_raw_data(){
	check_ajax_referer( 'umd_return_raw_data', 'security' );
	$user_id = intval($_POST['userid']);
	if ($user_id && $user_id != -1) {
		$found_user_meta = get_user_meta($user_id);
		if($found_user_meta){
			?>
			<script>
				jQuery(function($){
					$('.umd-meta-row').mouseenter(function(){
						var metakey = $(this).data('metakey');
						var metakey_class = '.umd-metakey-' + metakey;
						$(metakey_class + ' .umd-remove-button').removeClass('hidden');
						$(metakey_class + ' .umd-edit-button').removeClass('hidden');
					}).mouseleave(function(){
						var metakey = $(this).data('metakey');
						var metakey_class = '.umd-metakey-' + metakey;
						$(metakey_class + ' .umd-remove-button').addClass('hidden');
						$(metakey_class + ' .umd-edit-button').addClass('hidden');
					});
					$('.umd-remove-button').click(function(){

					})
				});
			</script>
			<?php
			echo '<table class="form-table">
				<thead>
					<tr>
						<th class="key-column">Key</th>
						<th class="value-column">Value</th>
					</tr>
				</thead>
				<tbody>';
			foreach( $found_user_meta as $key => $values ) :
					if ( apply_filters( 'umd_ignore_user_meta_key', false, $key ) )
						continue;
				foreach( $values as $value ) :
						$value = var_export( $value, true );
				echo '<tr class="umd-meta-row umd-metakey-' . esc_html( $key ) . '" data-metakey="' . esc_html( $key ) . '">
					<td class="key-column"><a href="#" class="umd-remove-button button button-primary hidden">Remove</a> <a href="#" class="umd-edit-button button hidden">Edit</a>' . esc_html( $key ) . '</td>
					<td class="value-column"><code>' . esc_html( $value ) . '</code></td>
				</tr>';
				endforeach;
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
?>
