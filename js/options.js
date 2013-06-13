var farbtastic;
var tbcityOptions;

(function($) {

tbcityOptions = {

	//initialize
	init : function() {

		var frame;

		tbcityOptions.switchTab('style');

		$('#to-defaults').click (function () {
			var answer = confirm(tbcity_options_l10n.confirm_to_defaults)
			if (!answer){
				return false;
			}
		});

		$( '.color_slider' ).each( function() {
			var self = $(this);
			var refers = $( '#' + self.attr('data-refers-to') );
			var init_value = refers.val();
			self.slider({
				orientation: "horizontal",
				range: "min",
				max: 360,
				value: 0,
				slide: function( event, ui ) {
					refers.val( ui.value );
				}
			});
			self.slider( "value", init_value );
		});

	},

	
	//show only a set of rows
	switchTab : function (thisset) {
		if ( thisset != 'info' ) {
			$('#theme-infos').css({ 'display' : 'none' });
			$('#theme-options').css({ 'display' : '' });
			thisclass = '.tabgroup-' + thisset;
			thissel = '#selgroup-' + thisset;
			$('.tab-opt').css({ 'display' : 'none' });
			$(thisclass).css({ 'display' : '' });
			$('#tabselector li').removeClass("sel-active");
			$(thissel).addClass("sel-active");
		} else {
			$('#theme-infos').css({ 'display' : '' });
			$('#theme-options').css({ 'display' : 'none' });
			$('#tabselector li').removeClass("sel-active");
			$('#selgroup-info').addClass("sel-active");
		}
	}

};

$(document).ready(function($){ tbcityOptions.init(); });

})(jQuery);