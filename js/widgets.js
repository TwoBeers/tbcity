jQuery(document).ready(function($){
	$('#widget-list').append('<p class="clear description" style="border-bottom: 1px solid #ddd;">The Black City widgets</p>');
	bz_widgets = $('#widget-list .widget[id*=_tb-]');
	$('#widget-list').append(bz_widgets);
});