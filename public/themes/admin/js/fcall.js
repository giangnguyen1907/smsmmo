var fcall = {
	fckBaseUrl: filemanageUrl,
	fcChoiceImg: function (obj) {
		var type = "img";
		var action = "find_img";
		if ($('#' + obj.id).attr('txthide') != null) {
			txtHide = $('#' + obj.id).attr('txthide');
		}
		if ($('#' + obj.id).attr('type') != null) {
			type = $('#' + obj.id).attr('type');
		}
		if ($('#' + obj.id).attr('action') != null) {
			action = $('#' + obj.id).attr('action');
		}
		var fckBaseUrl = this.fckBaseUrl + "?choiceImg=1&id=" + obj.id + "&type=" + type + "&txtHide=" + txtHide + "&action=" + action;
		var w = 1000, h = 520;
		var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
		var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;
		width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
		height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

		var left = ((width / 2) - (w / 2)) + dualScreenLeft;
		var top = ((height / 2) - (h / 2)) + dualScreenTop;
		var newWindow = window.open(fckBaseUrl, "filemanage izzi", 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

		// Puts focus on the newWindow
		if (window.focus) {
			newWindow.focus();
		}

		return false;
	},

	fcGetUrlChoiceImg: function (obj) {
		var url = "";
		if (obj == null) { return; }
		if (obj.type == "img") {
			document.getElementById(obj.id).src = obj.imageURL;
		}
		if (obj.type == "text") {
			document.getElementById(obj.id).value = obj.imageURL;
		}
		if (obj.txtHide) {
			document.getElementById(obj.txtHide).value = obj.imageURL;
		}
	}

}//end fcall