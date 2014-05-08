<?php
add_action( 'wp_ajax_umd_return_raw_data', 'umd_return_raw_data' );
add_action( 'wp_ajax_umd_change_user_list_dropdown', 'umd_change_user_list_dropdown' );

public function filter_user_row_actions( array $actions, WP_User $user ) {
	// TODO
	$link = "";
	$actions['user_meta'] = '<a href="' . $link . '">' . __( 'View Meta', 'user-meta-display' ) . '</a>';

	return $actions;
}

function umd_return_raw_data(){
	check_ajax_referer( 'umd_return_raw_data', 'security' );
	$user_id = intval($_POST['userid']);
	if ($user_id) {
		$found_user_meta = get_user_meta($user_id);
		if($found_user_meta){
			echo '<table>
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
				echo '<tr>
					<td class="key-column">' . esc_html( $key ) . '</td>
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
		if ($user_list_type == 1) {
			// ID
			wp_dropdown_users(array('show' => 'ID', 'show_option_none' => 'Select User ID', 'class' => 'user-meta-display-user-dropdown'));
		} elseif ($user_list_type == 2){
			// User Login
			wp_dropdown_users(array('show' => 'user_login', 'show_option_none' => 'Select User Login', 'class' => 'user-meta-display-user-dropdown'));
		} elseif ($user_list_type == 3){
			// Display Name
			wp_dropdown_users(array('show' => 'display_name', 'show_option_none' => 'Select User Display Name', 'class' => 'user-meta-display-user-dropdown'));
		}
		die;
	}
}
?>
