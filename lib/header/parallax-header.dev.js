/* tbcity - parallax-header.dev.js */
jQuery(document).ready(function($){

	var yourImage = $(".clouds");
	var x = 0;
	var y = 0;

	window.setInterval(function() {
		yourImage.css('backgroundPosition', x + 'px' + ' ' + y + 'px');
		x--;
	}, 100);

	var currentX = '';
	var movementConstant = .002;
	$(document).mousemove(function(e) {
	  if(currentX == '') currentX = e.pageX;
	  var xdiff = e.pageX - currentX;
	  currentX = e.pageX;
	  $('.parallax .mouse-move').each(function(i, el) {
		  var movement = (i + 1) * (xdiff * movementConstant);
		  var newX = $(el).position().left + movement;
		  $(el).css('left', newX + 'px');
	  });
	});

});