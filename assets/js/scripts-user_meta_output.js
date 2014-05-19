$jq = jQuery.noConflict();
function umdUnbindModalButtons(){
	$jq('.umd-modal-button-yes').unbind();
	$jq('.umd-modal-button-no').unbind();
}
function umdModalConfig(title, key, value, userid, yes_button, yes_callback, no_button, no_callback){
	$jq('.umd-modal-body').css('display', 'block');
	$jq('.umd-modal-title').html(title);
	$jq('.umd-modal-meta-key').html(key);
	$jq('.umd-modal-meta-value').html(value);
	$jq('.umd-modal-button-yes').html(yes_button).data('metakey', key).data('metavalue', value).data('userid', userid).click(yes_callback);
	$jq('.umd-modal-button-no').html(no_button).click(no_callback);
}
function umdModalShow(){
	$jq('.umd-control-container').css('display', 'block');
}
function umdModalHide(){
	$jq('.umd-control-container').css('display', 'none');
	umdUnbindModalButtons();
}
function umdModalFade(out){
	if(!out) {
		$jq('.umd-control-container').fadeIn("slow", "swing");
	} else {
		setTimeout(function(){
			$jq('.umd-control-container').fadeOut("slow", "swing");
		}, 1000);
		umdUnbindModalButtons();
	}

}
function umdUpdateModalStatus(content, is_error){
	var notice_class = 'umd-modal-notice-';
	if(is_error) notice_class = notice_class + 'error';
	if(!is_error) notice_class = notice_class + 'info';
	$jq('.umd-modal-body').attr('display', 'none');
	$jq('.umd-modal-title').html('<div class="' + notice_class + '">' + content + '</div>');
}
function umdHideManageButtons(metakey, hide){
	var metakey_class = '.umd-metakey-' + metakey;
	if(hide){
		$jq(metakey_class + ' .umd-remove-button').addClass('hidden');
		$jq(metakey_class + ' .umd-edit-button').addClass('hidden');
	} else {
		$jq(metakey_class + ' .umd-remove-button').removeClass('hidden');
		$jq(metakey_class + ' .umd-edit-button').removeClass('hidden');
	}
}
function umdHideRemoveButtons(metakey, hide){
//	var confirm_class = '.umd-confirm-' + metakey;
//	var cancel_class = '.umd-cancel-' + metakey;
//	if(hide){
//		umdHideManageButtons(metakey, false);
//		$jq(confirm_class).addClass('hidden');
//		$jq(cancel_class).addClass('hidden');
//	} else {
//		umdHideManageButtons(metakey, true);
//		$jq(confirm_class).removeClass('hidden');
//		$jq(cancel_class).removeClass('hidden');
//	}
}