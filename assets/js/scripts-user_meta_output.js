$jq = jQuery.noConflict();
function umdHTMLencode(value){
	return $jq('<div/>').text(value).html();
}
function umdHTMLdecode(value){
	return $jq('<div/>').html(value).text();
}
function umdUnbindModalButtons(){
	$jq('.umd-modal-button-yes').unbind();
	$jq('.umd-modal-button-no').unbind();
}
function umdModalConfig(title, key, value, userid, yes_button, yes_callback, no_button, no_callback, custom_key, custom_value){
	var modalKeyHTML;
	var modalValueHTML;
	$jq('.umd-modal-body').css('display', 'block');
	$jq('.umd-modal-title').html(title);
	if(custom_key && custom_value){
		modalKeyHTML = custom_key;
		modalValueHTML = custom_value;
	} else {
		modalKeyHTML = key;
		modalValueHTML = value;
	}
	$jq('.umd-modal-meta-key').html(modalKeyHTML);
	$jq('.umd-modal-meta-value').html(modalValueHTML);
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
	var metakey_class;
	if (metakey) metakey_class = '.umd-metakey-' + metakey + ' ';
	if(hide){
		$jq(metakey_class + '.umd-remove-button').delay(500).fadeOut('slow');
		$jq(metakey_class + '.umd-edit-button').delay(500).fadeOut('slow');
	} else {
		$jq(metakey_class + '.umd-remove-button').fadeIn();
		$jq(metakey_class + '.umd-edit-button').fadeIn();
	}
}