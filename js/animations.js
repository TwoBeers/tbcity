var tbcityScripts;

(function($) {

tbcityScripts = {

	init : function( in_modules ) {

		var modules = in_modules.split(',');

		for (i in modules) {

			switch(modules[i]) {

				case 'animatemenu':
					tbcityScripts.animate_menu();
					break;

				case 'extrainfo':
					tbcityScripts.extra_info();
					$('body').on('post-load', function(event){
						tbcityScripts.extra_info();
					});
					break;

				case 'quickbar':
					tbcityScripts.quickbar();
					break;

				case 'scrolltopbottom':
					tbcityScripts.scroll_top_bottom();
					$('body').on('post-load', function(event){
						tbcityScripts.scroll_top_bottom();
					});
					break;

				case 'resizevideo':
					tbcityScripts.resize_video();
					$('body').on('post-load', function(event){
						tbcityScripts.resize_video();
					});
					break;

				case 'lastcomments':
					tbcityScripts.last_comments();
					$('body').on('post-load', function(event){
						tbcityScripts.last_comments();
					});
					break;

				default :
					//no default action
					break;

			}

		}

	},


	animate_menu : function() {

		return $('#mainmenu').children('.menu-item-parent').each(function() {

			var $this = $(this);
			var d = $this.children('ul'); //for each main item, get the sub list

			d.css( {'opacity' : 0 } );

			$this.hoverIntent(
				function(){ //when mouse enters, slide down the sub list
					d.css( {'display' : 'block' } ).animate( { 'opacity' : 0.95 } );
				},
				function(){ //when mouse leaves, hide the sub list
					d.stop().animate( { 'opacity' : 0 }, 200, 'swing', function(){ d.css( {'display' : '' } ); } );
				}
			);

		});

	},


	extra_info : function() {

		//meta animation
		$('#posts_content').find('.extra-info').removeClass('extra-info').addClass('extra-info-js').each( function(){  //get every metafield item
			var $this = $(this);
			var lists = $('.metafield_content',$this);
			var opened = false;
			$('.alternate',$this).each( function(){
				var element = $(this);
				var list = $('.metafield_content',element);
				var trigger = $('.meta-trigger',element);
				trigger.click(
					function(){
						if ( element.hasClass('open') ) {
							element.removeClass('open');
							list.slideUp();
						} else {
							if ( opened ) {
								opened.removeClass('open');
								$('.metafield_content',opened).slideUp( 400, function() {
									element.addClass('open');
									list.slideDown();
								});
							} else {
								element.addClass('open');
								list.slideDown();
							}
							opened  = element;
						}
					}
				);
			});
		});

	},


	quickbar : function() {

		//quickbar animation
		var dropdown = $('#dropdown');
		var dropper = $('#dropper');
		var menuitems = $('.menuitem',dropdown);
		var opened = false;

		dropdown.removeClass('css').addClass('animated');

		dropper.click( function(){
			if ( opened ) {
				$('#cssmenu').slideUp( 400, function() {
					menuitems.removeClass('open');
					opened = false;
					$('.ddmcontent',dropdown).hide();
				});
			} else {
				$('#cssmenu').slideToggle();
			}
		});

		menuitems.each( function(){
			var element = $(this);
			var list = $('.ddmcontent',element);
			var trigger = $('.menuitem-trigger',element);
			trigger.click( function(){
				if ( element.hasClass('open') ) {
					element.removeClass('open');
					list.slideUp();
				} else {
					if ( opened ) {
						opened.removeClass('open');
						element.addClass('open');
						$('.ddmcontent',opened).slideUp( 400, function() {
							list.slideDown();
						});
					} else {
						element.addClass('open');
						list.slideDown();
					}
					opened  = element;
				}
			});
		});


	},


	scroll_top_bottom : function() {

		// smooth scroll top/bottom
		top_but = $('#posts_content').find('.jump-to-top').unbind().click(function() {
			$("html, body").animate({
				scrollTop: 0
			}, {
				duration: 400
			});
			return false;
		});
		top_but = $('#posts_content').find('.jump-to-bottom').unbind().click(function() {
			$("html, body").animate({
				scrollTop: $('#footer').offset().top
			}, {
				duration: 400
			});
			return false;
		});
	},


	resize_video : function() {
		// https://github.com/chriscoyier/Fluid-Width-Video
		var $fluidEl = $("#posts_content").find(".storycontent");
		var $allVideos = $("iframe[src^='http://player.vimeo.com'], iframe[src^='http://www.youtube.com'], object, embed",$fluidEl);

		$allVideos.each(function() {
			$(this)
				// jQuery .data does not work on object/embed elements
				.attr('data-aspectRatio', this.height / this.width)
				.removeAttr('height')
				.removeAttr('width');
		});

		$(window).resize(function() {
			var newWidth = $fluidEl.width();
			$allVideos.each(function() {
				var $el = $(this);
				$el
					.width(newWidth)
					.height(newWidth * $el.attr('data-aspectRatio'));
			});
		}).resize();
	},


	last_comments : function() {
		$('#posts_content').find('.last-comments.css').removeClass('css').find('.tooltip').each( function(){  //get every last-comments element

			var p = $(this).parent();
			var self = $(this);

			self.hide();

			p.hoverIntent(

				function(){

					self.stop().css({opacity: 0, display: 'block'}).animate({opacity: 0.9});

				},

				function(){

					self.fadeOut();

				}
			);

		});
	}

};

$(document).ready(function($){ tbcityScripts.init(tbcity_l10n.script_modules); });

})(jQuery);