<br>
<br>
<?php
$umd_return_raw_data = wp_create_nonce( 'umd_return_raw_data' );
$umd_change_user_list_dropdown = wp_create_nonce( 'umd_change_user_list_dropdown' );
?>
<script>
	function umdShowModalLoader(){
		$jq('.umd-modal-title').html('<img src="<?php echo plugins_url( '../assets/images/loader-inverted64.gif' , __FILE__ ); ?>">');
		$jq('.umd-modal-body').css('display', 'none');
	}

	function umdUpdateUserData(user_id, scroll_to_metakey){
		jQuery.ajax(ajaxurl, {
			type: 'POST',
			dataType: 'html',
			data: {
				action: 'umd_return_raw_data',
				userid: user_id,
				security: '<?php echo $umd_return_raw_data; ?>'
			},
			beforeSend: function () {
				jQuery('#user-meta-output-box').html('<img src="<?php echo admin_url("images/spinner.gif"); ?>">');
			},
			error: function(request, status, error) {
				jQuery('#user-meta-output-box').html('Error! ' + error);
			},
			success: function(data) {
				jQuery('#user-meta-output-box').html(data);
			},
			complete: function () {
				if (scroll_to_metakey) {
					var scroll_to_selector = $jq('.umd-metakey-' + scroll_to_metakey);
					var scroll_position = scroll_to_selector.offset().top;
					$jq('html,body').animate({
						scrollTop: scroll_position
					});
					scroll_to_selector.addClass('wp-ui-highlight').delay(200).fadeOut(100).fadeIn('slow').fadeOut(100).fadeIn('slow');
					setTimeout(function(){
						scroll_to_selector.removeClass('wp-ui-highlight');
					}, 2000);
				}
			}
		});
	}

	function umdUpdateDropdown(user_list, user_id){
		jQuery.ajax(ajaxurl, {
			type: 'POST',
			dataType: 'html',
			data: {
				action: 'umd_change_user_list_dropdown',
				userlist: user_list,
				userid: user_id,
				security: '<?php echo $umd_change_user_list_dropdown; ?>'
			},
			beforeSend: function () {
				jQuery('#umd-user-list-dropdown').html('<img src="<?php echo admin_url("images/spinner.gif"); ?>">');
			},
			error: function(request, status, error) {
				jQuery('#umd-user-list-dropdown').html('Error! ' + error);
			},
			success: function(data) {
				jQuery('#umd-user-list-dropdown').html(data);
			}
		});
	}


	jQuery(document).ready(function($) {

		$('#umd-add-user-meta').click(function(){
			var userid = $('#user').val();
			var modalTitle = 'Add new meta to user ' + userid;
			var modalKey = 'Key: <input type="text" id="umd-edit-meta-key">';
			var modalValue = '<div class="umd-edit-meta-value-title">Value:</div><textarea id="umd-edit-meta-value" cols="3"></textarea>';
			umdModalConfig(modalTitle, modalKey, modalValue, userid, 'Add Meta', umdAddNewUserMeta, 'Nope, go back', umdModalHide);
			umdModalFade(false);
		});

		$('.umd-modal-close').click(function(){
			umdModalHide();
		});
		$('.user-meta-display-field-row').on('change', '#user', function(){
			var user_id = $(this).val();
			if(user_id != -1){
				umdUpdateUserData(user_id);
			}
		});
		$('#umd-refresh-meta-button').click(function(){
			var user_id = $('#user').val();
			if(user_id && user_id != -1){
				umdUpdateUserData(user_id);
			}
		});
		$('#umd-refresh-dropdown-button').click(function(){
			var viewBy = $('.user_meta_display-toggle-group-buttons .button-primary').data('value');
			var user_id = $('#user').val();
			umdUpdateDropdown(viewBy, user_id);
		});
		$('.user_meta_display-toggle-group-buttons .button').click(function() {
			var viewBy = $(this).data('value');
			var user_id = $('#user').val();
			umdUpdateDropdown(viewBy, user_id);
		});

	});
</script>
<div class="umd-control-container">
	<div class="umd-backdrop">
		<div class="umd-modal">
			<div class="umd-modal-title">
				Default Title:
			</div>
			<div class="umd-modal-body">
				<div class="umd-modal-meta-key">default_meta_field</div>
				<div class="umd-modal-meta-value-wrap">
					<div class="umd-modal-meta-value">Default Meta Value</div>
				</div>
				<div class="umd-modal-buttons">
					<a class="button button-primary button-hero umd-modal-button-yes" href="#">Default Yes Button</a>
					<a class="button button-hero umd-modal-button-no" href="#">Default No Button</a>
				</div>
			</div>
		</div>
		<div class="umd-modal-close umd-modal-close-corner"><div class="dashicons dashicons-dismiss"></div></div>
	</div>
</div>
<div id="umd_refresh_meta">
	<a id="umd-refresh-meta-button" class="button" href="#">Refresh User Meta</a>
	<a id="umd-add-user-meta" class="button" href="#">Add User Meta</a>
</div>
<div id="user-meta-output-box">
	Please select a user from above.
</div>