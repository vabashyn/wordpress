(function ($) {
	$(document).ready(function () {
		if ( $('#wp-admin-bar-wm-cache').length && typeof cstmAjaxUrl != 'undefined' ) {
			$('#wp-admin-bar-wm-cache a').click(function(e) {
				e.preventDefault();

				var $this = $(this),
					nonceKey = $this.attr('rel');

				var fd = new FormData();
				fd.append('clearcache', 1);
				fd.append('action', 'clear-wm-cache');
				fd.append('nonce', nonceKey);
				var jqXHR = $.ajax({
					xhr: function() {
						var xhrobj = $.ajaxSettings.xhr();
						return xhrobj;
					},
					url: cstmAjaxUrl,
					type: "POST",
					contentType:false,
					processData: false,
					cache: false,
					data: fd,
					success: function(data){
						if ( data == 1 ) {
							alert('Кэш успешно удален.');
						} else {
							alert('При попытке очистить кэш возникла ошибка. Повторите попытку позже.');
						}
					},
					error: function(data) {
						alert('При попытке очистить кэш возникла ошибка. Повторите попытку позже.');
					},
				});
			});
		}
	});
}(jQuery));
