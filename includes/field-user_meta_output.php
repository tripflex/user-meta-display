<br>
<br>
<?php
$umd_return_raw_data = wp_create_nonce( 'umd_return_raw_data' );
$umd_change_user_list_dropdown = wp_create_nonce( 'umd_change_user_list_dropdown' );
?>
<script>
	jQuery(document).ready(function($) {
		$('.user-meta-display-field-row').on('click', '#user', function() {
			$.ajax(ajaxurl, {
				type: 'POST',
				dataType: 'html',
				data: {
					action: 'umd_return_raw_data',
					userid: $(this).val(),
					security: '<?php echo $umd_return_raw_data; ?>'
				},
				beforeSend: function () {
					$('#user-meta-output-box').html('<img src="http://www3.travelhealthcare.com/wp-admin/images/spinner.gif">');
				},
				error: function(request, status, error) {
					$('#user-meta-output-box').html('Error! ' + error);
				},
				success: function(data) {
					$('#user-meta-output-box').html(data);
				}
			});
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
					$('#umd_user_list_dropdown').html('<img src="http://www3.travelhealthcare.com/wp-admin/images/spinner.gif">');
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
<div id="user-meta-output-box">
	Please select a user from above.
</div>