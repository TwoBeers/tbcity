jQuery(document).ready(function($){
    $('#widget-list').append('<p class="clear description" style="border-bottom: 1px solid #ddd;">tbcity widgets</p>');
    bz_widgets = $('#widget-list .widget[id*=_bz-]');
    $('#widget-list').append(bz_widgets);
});