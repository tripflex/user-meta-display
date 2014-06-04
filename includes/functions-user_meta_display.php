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
		echo '<div id="umd-return-status" data-status="success">' . __( "Meta successfully removed!" ) . '</div>';
	} else {
		echo '<div id="umd-return-status" data-status="error">' . __( "Error removing meta!" ) . '</div>';
	}
	die;
}
function umd_edit_user_meta(){
	check_ajax_referer( 'umd_edit_user_meta', 'security' );
	$userid = $_POST['userid'];
	$metakey = $_POST['metakey'];
	$metavalue = stripslashes($_POST['metavalue']);
	$metaprevalue = stripslashes($_POST['metaprevalue']);

	if($metavalue === $metaprevalue){
		echo '<div id="umd-return-status" data-status="error">' . __( "Existing and new meta are the same." ) .'</div>';
		die;
	}

	$addOrEdit = 'add';

	if($metaprevalue) $addOrEdit = 'edit';

	$results = update_user_meta( $userid, $metakey, $metavalue, $metaprevalue );

	if($results){
		echo '<div id="umd-return-status" data-status="success">' . sprintf(__( 'Meta %1$sed successfully!' ), $addOrEdit) . '</div>';
	} else {
		echo '<div id="umd-return-status" data-status="error">' . sprintf( __( 'Error %1$sed meta!' ), $addOrEdit ) . '</div>';
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
				$jq = jQuery.noConflict();
				function umdEditUserMeta(meta_key, meta_value, user_id, meta_pre_value) {
					var meta_pre_value_unescaped;
					// Need to decode HTML code
					var meta_value_unescaped = umdHTMLdecode(meta_value);
					if (meta_pre_value) meta_pre_value_unescaped = umdHTMLdecode(meta_pre_value);
					$jq.ajax(ajaxurl, {
						type: 'POST',
						dataType: 'html',
						data: {
							action: 'umd_edit_user_meta',
							userid: user_id,
							metakey: meta_key,
							metavalue: meta_value_unescaped,
							metaprevalue: meta_pre_value_unescaped,
							security: '<?php echo $umd_edit_user_meta; ?>'
						},
						beforeSend: function () {
							umdShowModalLoader();
						},
						error: function (request, status, error) {
							umdUpdateModal('<?php echo __("Ajax error!"); ?><br>' + error);
						},
						success: function (data) {

							results = $jq(data).filter('#umd-return-status');
							var status_result = $jq(results).data('status');
							var status_description = $jq(results).text();

							if (status_result == 'success') {
								umdUpdateUserData(user_id, meta_key);
								umdUpdateModalStatus(status_description, false);
							} else {
								umdUpdateModalStatus(status_description, true);
							}
						},
						complete: function () {
							umdModalFade(true);
						}
					});
				}

				function umdRemoveUserMeta(meta_key, meta_value, user_id) {
					// Need to decode HTML code
					var meta_value_unescaped = $jq('<div/>').html(meta_value).text();
					$jq.ajax(ajaxurl, {
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
						error: function (request, status, error) {
							umdUpdateModal('<?php echo __("Ajax error Removing Meta!"); ?><br>' + error);
						},
						success: function (data) {

							results = $jq(data).filter('#umd-return-status');
							var status_result = $jq(results).data('status');
							var status_description = $jq(results).text();

							if (status_result == 'success') {
								$jq('.umd-metakey-' + meta_key).remove();
								umdUpdateModalStatus(status_description, false);
							} else {
								umdUpdateModalStatus(status_description, true);
							}
						},
						complete: function () {
							umdModalFade(true);
						}
					});
				}
				function umdAddNewUserMeta() {
					var metakey = $jq('#umd-edit-meta-key').val();
					var userid = $jq(this).data('userid');
					var metavalue = $jq('#umd-edit-meta-value').val();
					// Need to encode any HTML to prevent it from being stripped
					metavalue = umdHTMLencode(metavalue);
					umdEditUserMeta(metakey, metavalue, userid);
				}
				function umdUpdateUserMeta() {
					var metakey = $jq('#umd-edit-meta-key').val();
					var userid = $jq(this).data('userid');
					var metavalue = $jq('#umd-edit-meta-value').val();
					var metaprevalue = $jq(this).data('metavalue');
					// Need to encode any HTML to prevent it from being stripped
					// Previous meta value is already encoded
					metavalue = umdHTMLencode(metavalue);
					umdEditUserMeta(metakey, metavalue, userid, metaprevalue);
				}
				function umdRemoveMetaConfirmed() {
					var metakey = $jq(this).data('metakey');
					var metavalue = $jq(this).data('metavalue');
					var userid = $jq(this).data('userid');

					umdRemoveUserMeta(metakey, metavalue, userid);
				}
				jQuery(function ($jq) {
//					$jq('.umd-meta-row, .umd-metakey-buttons').mouseenter(function () {
////					umdHideManageButtons(null, true);
//						umdHideManageButtons($jq(this).data('metakey'), false);
//					}).mouseleave(function () {
//						umdHideManageButtons($jq(this).data('metakey'), true);
//					});

					// Set row colors as normal CSS nth-child not supported in IE''
					$jq(".umd-meta-table-body > tr:odd").css("background-color", "#F7F7F7").hover(
						function(){
							$jq(this).css('background-color', '#CCCCCC');
						},
						function(){
							$jq(this).css('background-color', '#F7F7F7');
						}
					);

					$jq('.umd-meta-row').hover(
						function () {
							$jq('.umd-metakey-buttons', $jq(this)).fadeTo("fast", 1);
						},
						function () {
							$jq('.umd-metakey-buttons', $jq(this)).fadeTo("fast", 0);
						}
					);

					$jq('.umd-metakey-buttons').hover(
						function () {
							$jq(this).stop(true).fadeTo("fast", 1);
						},
						function () {
							$jq(this).fadeTo("fast", 0);
						}
					);

					// Display add user meta button as this JS is only loaded when a users meta is being shown
					$jq('#umd-add-user-meta').css('display', 'inline-block');
					$jq('.umd-remove-button').click(function () {
						var metakey = $jq(this).data('metakey');
						var userid = $jq(this).data('userid');
						var metavalue = $jq('.umd-metakey-' + metakey + ' .value-column code').html();

						umdModalConfig("Are you sure you want to remove the user meta below?", metakey, metavalue, userid, "Yes!", umdRemoveMetaConfirmed, "Nope, go back.", umdModalHide);
						umdModalShow();
					});
					$jq('.umd-edit-button').click(function () {
						var metakey = $jq(this).data('metakey');
						var userid = $jq(this).data('userid');
						var metavalue = $jq('.umd-metakey-' + metakey + ' .value-column code').html();
						var modalKey = 'Key: <input type="text" id="umd-edit-meta-key" value="' + metakey + '" disabled>';
						var modalValue = '<div class="umd-edit-meta-value-title">Value:</div><textarea id="umd-edit-meta-value" cols="3">' + metavalue + '</textarea>';
						umdModalConfig('Edit meta below:', metakey, metavalue, userid, 'Update Meta', umdUpdateUserMeta, 'Cancel', umdModalHide, modalKey, modalValue);
						umdModalFade(false);
					});

				});
			</script>
			<?php
			echo '<table class="form-table umd-meta-table">
				<thead>
					<tr>
						<th class="key-column">Key</th>
						<th class="value-column">Value</th>
					</tr>
				</thead>
				<tbody class="umd-meta-table-body">';
			foreach( $found_user_meta as $key => $value ) :
					if ( apply_filters( 'umd_ignore_user_meta_key', false, $key ) )
						continue;
//						$value = var_export( $value, true );
				echo '<tr class="umd-meta-row umd-metakey-' . esc_html( $key ) . '" data-metakey="' . esc_html( $key ) . '" data-userid="' . intval( $user_id ) . '">
					<td class="key-column">
					<div class="umd-metakey-buttons">
						<a href="#" data-userid="' . intval( $user_id ) . '" data-metakey="' . esc_html( $key ) . '" class="umd-remove-' . esc_html( $key ) . ' umd-remove-button button button-primary">Remove</a>
						<a href="#" data-userid="' . intval( $user_id ) . '" data-metakey="' . esc_html( $key ) . '" class="umd-edit-' . esc_html( $key ) . ' umd-edit-button button">Edit</a></div><div class="umd-metakey-value">' . esc_html( $key ) . '</div></td>
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
