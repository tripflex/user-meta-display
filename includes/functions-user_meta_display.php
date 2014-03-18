<?php
add_action( 'wp_ajax_umd_return_raw_data', 'umd_return_raw_data' );
function umd_return_raw_data(){
	check_ajax_referer( 'umd_raw_ajax_security', 'security' );
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
?>