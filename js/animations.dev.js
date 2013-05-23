var tbcityScripts;

(function($) {

tbcityScripts = {

	init : function( in_modules ) {

		var modules = in_modules.split(',');

		for (i in modules) {

			switch(modules[i]) {

				case 'collapseposts':
					tbcityScripts.collapse_posts();
					$('body').on('post-load', function(event){
						tbcityScripts.collapse_posts();
					});
					break;

				case 'quotethis':
					tbcityScripts.init_quote_this();
					break;

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

				default :
					//no default action
					break;

			}

		}

	},


	animate_menu : function() {

		return $('#mainmenu').children('.menu-item-parent').each(function() {

			$this = $(this);

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

		//meta animation
		$('#dropdown').removeClass('css').addClass('animated');
		$('#dropper').click(function() {$('#cssmenu').slideToggle();});
		var opened = false;
		$('#dropdown').find('.menuitem').each( function(){
			var element = $(this);
			var list = $('.ddmcontent',element);
			var trigger = $('.menuitem-trigger',element);
			trigger.click(
				function(){
					if ( element.hasClass('open') ) {
						element.removeClass('open');
						list.slideUp();
					} else {
						if ( opened ) {
							opened.removeClass('open');
							$('.ddmcontent',opened).slideUp( 400, function() {
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


	},


	collapse_posts : function() {

		$('#posts_content').find('.hentry.expanded').removeClass('expanded').each( function() {
			var element = $(this);
			var list = $('.storycontent',element);
			if (tbcity_l10n.posts_collapsed == '1') list.hide();
			var trigger = $('.collapse-post-trigger',element);
			trigger.click(
				function(){
					if ( element.hasClass('collapsed') ) {
						element.removeClass('collapsed');
						list.slideDown();
					} else {
						list.slideUp( 400, function() {
							element.addClass('collapsed');
						});
					}

				}
			);
		});

	},


	scroll_top_bottom : function() {

		top_but = $('#posts_content').find('.jump-to-top');

		// smooth scroll top/bottom
		top_but.click(function() {
			$("html, body").animate({
				scrollTop: 0
			}, {
				duration: 400
			});
			return false;
		});
	},


	init_quote_this : function() {
		if ( document.getElementById('reply-title') && document.getElementById("comment") ) {
			bz_qdiv = document.createElement('small');
			bz_qdiv.innerHTML = ' - <a id="bz-quotethis" href="#" onclick="tbcityScripts.quote_this(); return false" title="' + tbcity_l10n.quote_tip + '" >' + tbcity_l10n.quote + '</a>';
			bz_replink = document.getElementById('reply-title');
			bz_replink.appendChild(bz_qdiv);
		}
	},


	quote_this : function() {
		var posttext = '';
		if (window.getSelection){
			posttext = window.getSelection();
		}
		else if (document.getSelection){
			posttext = document.getSelection();
		}
		else if (document.selection){
			posttext = document.selection.createRange().text;
		}
		else {
			return true;
		}
		posttext = posttext.toString().replace(/&/g,"&amp;").replace(/"/g,"&quot;").replace(/</g,"&lt;").replace(/>/g,"&gt;");
		if ( posttext.length !== 0 ) {
			document.getElementById("comment").value = document.getElementById("comment").value + '<blockquote>' + posttext + '</blockquote>';
		} else {
			alert( tbcity_l10n.quote_alert );
		}
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

};

$(document).ready(function($){ tbcityScripts.init(tbcity_l10n.script_modules); });

})(jQuery);