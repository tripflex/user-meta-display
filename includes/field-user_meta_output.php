<br>
<br>
<?php
$umd_return_raw_data = wp_create_nonce( 'umd_return_raw_data' );
$umd_change_user_list_dropdown = wp_create_nonce( 'umd_change_user_list_dropdown' );
?>
<script>
	function updateUserData(user_id){
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
	jQuery(document).ready(function($) {

		$('.user-meta-display-field-row').on('click', '#user', function(){
			updateUserData($(this).val());
		});
		$('#umd_refresh_meta_button').click(function(){
			updateUserData($('#user').val());
		});

		$('.user_meta_display-toggle-group-buttons .button').click(function() {
			$.ajax(ajaxurl, {
				type: 'POST',
				dataType: 'html',
				data: {
					action: 'umd_change_user_list_dropdown',
					userlist: $(this).data('value'),
					security: '<?php echo $umd_change_user_list_dropdown; ?>'
				},
				beforeSend: function () {
					$('#umd_user_list_dropdown').html('<img src="<?php echo admin_url("images/spinner.gif"); ?>">');
				},
				error: function(request, status, error) {
					$('#umd_user_list_dropdown').html('Error! ' + error);
				},
				success: function(data) {
					$('#umd_user_list_dropdown').html(data);
				}
			});

		});
	});
</script>

<div id="umd_refresh_meta">
	<a id="umd_refresh_meta_button" class="button" href="#">Refresh User Meta</a>
</div>
<div id="user-meta-output-box">
	Please select a user from above.
</div>