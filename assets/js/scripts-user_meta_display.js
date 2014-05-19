$jq = jQuery.noConflict();
function umdAddNewUserMeta(){
	var metakey = $jq('#umd-edit-meta-key').val();
	var userid = $jq(this).data('userid');
	var metavalue = $jq('#umd-edit-meta-value').val();
	// Need to encode any HTML to prevent it from being stripped
	metavalue = umdHTMLencode(metavalue);
	umdEditUserMeta(metakey, metavalue, userid);
}
function umdUpdateUserMeta(){
	var metakey = $jq('#umd-edit-meta-key').val();
	var userid = $jq(this).data('userid');
	var metavalue = $jq('#umd-edit-meta-value').val();
	var metaprevalue = $jq(this).data('metavalue');
	// Need to encode any HTML to prevent it from being stripped
	// Previous meta value is already encoded
	metavalue = umdHTMLencode(metavalue);
	umdEditUserMeta(metakey, metavalue, userid, metaprevalue);
}
$jq('.umd-meta-row').mouseenter(function(){
	umdHideManageButtons($jq(this).data('metakey'), false);
}).mouseleave(function(){
	umdHideManageButtons($jq(this).data('metakey'), true);
});
function umdRemoveMetaConfirmed(){
	var metakey = $jq(this).data('metakey');
	var metavalue = $jq(this).data('metavalue');
	var userid = $jq(this).data('userid');

	umdRemoveUserMeta(metakey, metavalue, userid);
}
jQuery(function($jq){
	// Display add user meta button as this JS is only loaded when a users meta is being shown
	$jq('#umd-add-user-meta').css('display', 'inline-block');
	$jq('.umd-remove-button').click(function(){
		var metakey = $jq(this).data('metakey');
		var userid = $jq(this).data('userid');
		var metavalue = $jq('.umd-metakey-' + metakey + ' .value-column code').html();

		umdModalConfig("Are you sure you want to remove the user meta below?", metakey, metavalue, userid, "Yes!", umdRemoveMetaConfirmed, "Nope, go back.", umdModalHide);
		umdModalShow();
	});
	$jq('.umd-edit-button').click(function(){
		var metakey = $jq(this).data('metakey');
		var userid = $jq(this).data('userid');
		var metavalue = $jq('.umd-metakey-' + metakey + ' .value-column code').html();
		var modalKey = 'Key: <input type="text" id="umd-edit-meta-key" value="' + metakey + '" disabled>';
		var modalValue = '<div class="umd-edit-meta-value-title">Value:</div><textarea id="umd-edit-meta-value" cols="3">' + metavalue + '</textarea>';
		umdModalConfig('Edit meta below:', metakey, metavalue, userid, 'Update Meta', umdUpdateUserMeta, 'Cancel', umdModalHide, modalKey, modalValue);
		umdModalFade(false);
	});

});