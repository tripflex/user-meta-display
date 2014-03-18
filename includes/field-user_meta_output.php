<br>
<br>
<?php $umd_nonce = wp_create_nonce( 'umd_raw_ajax_security' ); ?>
<script>
	jQuery(document).ready(function($) {
	    $('#user').change(function() {
	        var data = {
	            action: 'umd_return_raw_data',
	            userid: $(this).val(),
	            security: '<?php echo $umd_nonce; ?>'
	        };

	        $.post(ajaxurl, data, function(response) {
	            $('#user-meta-output-box').html(response);
	        });
	    });
	});
</script>
<div id="user-meta-output-box">
	Please select a user from above.
</div>