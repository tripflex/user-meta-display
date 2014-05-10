<br>
<br>
<?php
$umd_return_raw_data = wp_create_nonce( 'umd_return_raw_data' );
$umd_change_user_list_dropdown = wp_create_nonce( 'umd_change_user_list_dropdown' );
?>
<script>
	function umdUpdateUserData(user_id){
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
			}
		});
	}

	function umdUpdateDropdown(user_list){
		jQuery.ajax(ajaxurl, {
			type: 'POST',
			dataType: 'html',
			data: {
				action: 'umd_change_user_list_dropdown',
				userlist: user_list,
				security: '<?php echo $umd_change_user_list_dropdown; ?>'
			},
			beforeSend: function () {
				jQuery('#umd_user_list_dropdown').html('<img src="<?php echo admin_url("images/spinner.gif"); ?>">');
			},
			error: function(request, status, error) {
				jQuery('#umd_user_list_dropdown').html('Error! ' + error);
			},
			success: function(data) {
				jQuery('#umd_user_list_dropdown').html(data);
			}
		});
	}

	jQuery(document).ready(function($) {

		$('.user-meta-display-field-row').on('click', '#user', function(){
			umdUpdateUserData($(this).val());
		});
		$('#umd_refresh_meta_button').click(function(){
			umdUpdateUserData($('#user').val());
		});
		$('#umd_refresh_dropdown_button').click(function(){
			umdUpdateDropdown($('.user_meta_display-toggle-group-buttons .button').data('value'));
		});
		$('.user_meta_display-toggle-group-buttons .button').click(function() {
			umdUpdateDropdown($(this).data('value'));
		});


	});
</script>

<div id="umd_refresh_meta">
	<a id="umd_refresh_meta_button" class="button" href="#">Refresh User Meta</a>
</div>
<div id="user-meta-output-box">
	Please select a user from above.
</div>