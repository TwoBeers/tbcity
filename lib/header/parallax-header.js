/* tbcity - parallax-header.js */
jQuery(document).ready(function($){

	var clouds = $(".clouds");
	var x = 0;
	var y = 0;

	window.setInterval(function() {
		clouds.css('backgroundPosition', x + 'px' + ' ' + y + 'px');
		x--;
		if ( x == -500 ) { x = 0 } ;
	}, 100);

	var currentX = '';
	var movementConstant = .005;
	$(document).mousemove(function(e) {
		if(currentX == '') currentX = e.pageX;
		var xdiff = e.pageX - currentX;
		$('.parallax .mouse-move').each(function(i, el) {
			var newX = (i + 1) * (xdiff * movementConstant);
			$(el).css('left', newX + 'px');
		});
	});

});