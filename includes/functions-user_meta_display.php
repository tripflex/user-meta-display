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

	if(!$results){

	} else {

	}
}

function umd_return_raw_data(){
	check_ajax_referer( 'umd_return_raw_data', 'security' );
	$user_id = intval($_POST['userid']);
	if ($user_id && $user_id != -1) {
		$found_user_meta = get_user_meta($user_id);
		if($found_user_meta){
			$umd_remove_user_meta = wp_create_nonce( 'umd_remove_user_meta' );
			?>
			<script>
				jQuery(function($){

					function umdRemoveUserMeta(meta_key, meta_value, user_id){

						jQuery.ajax(ajaxurl, {
							type: 'POST',
							dataType: 'html',
							data: {
								action: 'umd_remove_user_meta',
								userid: user_id,
								metakey: meta_key,
								metavalue: meta_value,
								security: '<?php echo $umd_remove_user_meta; ?>'
							},
							beforeSend: function () {
<!--								jQuery('#user-meta-output-box').html('<img src="--><?php //echo admin_url("images/spinner.gif"); ?><!--">');-->
							},
							error: function(request, status, error) {
//								jQuery('#user-meta-output-box').html('Error! ' + error);
							},
							success: function(data) {
//								jQuery('.umd-metakey-' + $metakey).remove();
								if(data == 1){
									console.log('Removed!');
								} else if(data == 0){
									console.log('Error Removing');
								} else {
									console.log('Unknown Error Removing');
								}
							}
						});
					}

					function umdModalConfig(title, key, value, yes_button, yes_callback, no_button, no_callback){
						$('.umd-modal-title').html(title);
						$('.umd-modal-meta-key').html(key);
						$('.umd-modal-meta-value').html(value);
						$('.umd-modal-button-yes').html(yes_button).data('metakey', key).data('metavalue', value).click(yes_callback);
						$('.umd-modal-button-no').html(no_button).click(no_callback);
					}
					function umdModalShow(){
						$('.umd-control-container').css('display', 'block');
					}
					function umdModalHide(){
						$('.umd-control-container').css('display', 'none');
					}
					function umdRemoveMetaConfirmed(key){

					}
					function umdHideManageButtons(metakey, hide){
						var metakey_class = '.umd-metakey-' + metakey;
						if(hide){
							$(metakey_class + ' .umd-remove-button').addClass('hidden');
							$(metakey_class + ' .umd-edit-button').addClass('hidden');
						} else {
							$(metakey_class + ' .umd-remove-button').removeClass('hidden');
							$(metakey_class + ' .umd-edit-button').removeClass('hidden');
						}
					}

					function umdHideRemoveButtons(metakey, hide){
						var confirm_class = '.umd-confirm-' + metakey;
						var cancel_class = '.umd-cancel-' + metakey;
						if(hide){
							umdHideManageButtons(metakey, false);
							$(confirm_class).addClass('hidden');
							$(cancel_class).addClass('hidden');
						} else {
							umdHideManageButtons(metakey, true);
							$(confirm_class).removeClass('hidden');
							$(cancel_class).removeClass('hidden');
						}
					}

					$('.umd-meta-row').mouseenter(function(){
						umdHideManageButtons($(this).data('metakey'), false);
					}).mouseleave(function(){
						umdHideManageButtons($(this).data('metakey'), true);
					});

					$('.umd-remove-button').click(function(){
						var metakey = $(this).data('metakey');
						var metavalue = $('.umd-metakey-' + metakey + ' .value-column code').html();
						umdHideRemoveButtons($(this).data('metakey'), false);
						umdModalConfig('Are you sure you want to remove the user meta below?', metakey, metavalue, 'Yes, remove!', umdRemoveMetaConfirmed, 'No, cancel', umdModalHide);
						umdModalShow();
					});

					$('.umd-confirm-button').click(function(){

					});

					$('.umd-cancel-button').click(function(){

					});

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
					<td class="key-column">
					<a href="#" data-metakey="' . esc_html( $key ) . '" class="umd-remove-' . esc_html( $key ) . ' umd-remove-button button button-primary hidden">Remove</a>
					<a href="#" data-metakey="' . esc_html( $key ) . '" class="umd-edit-' . esc_html( $key ) . ' umd-edit-button button hidden">Edit</a>' . esc_html( $key ) . '</td>
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

add_filter( 'user_row_actions', 'umd_filter_user_row_actions', 10, 2 );

function umd_filter_user_row_actions( array $actions, WP_User $user ) {
	$link = admin_url( 'users.php?page=user_meta_display&user_id=' . $user->ID );
	$actions['user_meta'] = '<a href="' . $link . '">' . __( 'Meta', 'user-meta-display' ) . '</a>';
	return $actions;
}

?>
