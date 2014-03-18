jQuery(document).ready(function($){
	jQuery('#user').change(function(){
		jQuery('#user-meta-output-box').load('wp-admin/admin.php?page=user-meta-display&userid=' + jQuery(this).val());
	});
});