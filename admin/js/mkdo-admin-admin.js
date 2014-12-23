(function( $ ) {
	'use strict';

	/**
	 * All of the code for your Dashboard-specific JavaScript source
	 * should reside in this file.
	 *
	 * Note that this assume you're going to use jQuery, so it prepares
	 * the $ function reference to be used within the scope of this
	 * function.
	 *
	 * From here, you're able to define handlers for when the DOM is
	 * ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * Or when the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and so on.
	 *
	 * Remember that ideally, we should not attach any more than a single DOM-ready or window-load handler
	 * for any particular page. Though other scripts in WordPress core, other plugins, and other themes may
	 * be doing this, we should try to minimize doing that in our own work.
	 */
	
	 var resize_content_count = 0;
	 var resize_rows = 0;

	 function resize_content() {

			var cards = {};
		
			$('.toplevel_page_mkdo_content_menu #dashboard-widgets-wrap #dashboard-widgets .postbox-container .postbox, .toplevel_page_mkdo_dashboard #dashboard-widgets-wrap #dashboard-widgets .postbox-container .postbox').each(function(){

				resize_content_count++;

				var offset = $(this).offset();

				if( !(offset.top in cards) )
				{
					var array = [];
					array.push($(this).height());
					cards[offset.top] = array;
				}
				else
				{
					cards[offset.top].push($(this).height());
				}
			});

			for (var key in cards) {
				if( cards[key].length > 1 )
				{
					$('.toplevel_page_mkdo_content_menu #dashboard-widgets-wrap #dashboard-widgets .postbox-container .postbox, .toplevel_page_mkdo_dashboard #dashboard-widgets-wrap #dashboard-widgets .postbox-container .postbox').each(function(){

						var offset = $(this).offset();
						if( offset.top == parseFloat(key) || ( offset.top > parseFloat(key) - 100 && offset.top < parseFloat(key) + 100 ) )
						{
							$(this).height( Math.max.apply( Math, cards[key] ) );
						}
					});
				}
			}

			$('.toplevel_page_mkdo_content_menu #dashboard-widgets-wrap #dashboard-widgets .postbox-container .closed').removeAttr('style');
			$('.toplevel_page_mkdo_dashboard #dashboard-widgets-wrap #dashboard-widgets .postbox-container .closed').removeAttr('style');
		}

		function resize_inside() {

			var cards = {};
		
			$('.toplevel_page_mkdo_content_menu #dashboard-widgets-wrap #dashboard-widgets .postbox-container .postbox .inside, .toplevel_page_mkdo_dashboard #dashboard-widgets-wrap #dashboard-widgets .postbox-container .postbox .inside').each(function(){

				resize_content_count++;

				var offset = $(this).offset();

				if( !(offset.top in cards) )
				{
					var array = [];
					array.push($(this).height());
					cards[offset.top] = array;
				}
				else
				{
					cards[offset.top].push($(this).height());
				}
			});

			for (var key in cards) {
				if( cards[key].length > 1 )
				{
					$('.toplevel_page_mkdo_content_menu #dashboard-widgets-wrap #dashboard-widgets .postbox-container .postbox .inside, .toplevel_page_mkdo_dashboard #dashboard-widgets-wrap #dashboard-widgets .postbox-container .postbox .inside').each(function(){

						var offset = $(this).offset();
						
						if( offset.top == parseFloat(key) || ( offset.top > parseFloat(key) - 100 && offset.top < parseFloat(key) + 100 ) )
						{
							$(this).height( Math.max.apply( Math, cards[key] ) );
						}
					});
				}
			}

			$('.toplevel_page_mkdo_content_menu #dashboard-widgets-wrap #dashboard-widgets .postbox-container .closed .inside').removeAttr('style');
			$('.toplevel_page_mkdo_dashboard #dashboard-widgets-wrap #dashboard-widgets .postbox-container .closed .inside').removeAttr('style');
		}

		$('.toplevel_page_mkdo_content_menu #dashboard-widgets-wrap #dashboard-widgets .postbox-container .postbox .hndle, .toplevel_page_mkdo_content_menu #dashboard-widgets-wrap #dashboard-widgets .postbox-container .postbox .handlediv').click(function(){

			$('.toplevel_page_mkdo_content_menu #dashboard-widgets-wrap #dashboard-widgets .postbox-container .closed').removeAttr('style');
			$('.toplevel_page_mkdo_content_menu #dashboard-widgets-wrap #dashboard-widgets .postbox-container .closed .inside').removeAttr('style');
			$('.toplevel_page_mkdo_dashboard #dashboard-widgets-wrap #dashboard-widgets .postbox-container .closed').removeAttr('style');
			$('.toplevel_page_mkdo_dashboard #dashboard-widgets-wrap #dashboard-widgets .postbox-container .closed .inside').removeAttr('style');
			setTimeout(function() {
				
				$('.toplevel_page_mkdo_content_menu #dashboard-widgets-wrap #dashboard-widgets .postbox-container .postbox').removeAttr('style');
				$('.toplevel_page_mkdo_content_menu #dashboard-widgets-wrap #dashboard-widgets .postbox-container .postbox .inside').removeAttr('style');
				$('.toplevel_page_mkdo_dashboard #dashboard-widgets-wrap #dashboard-widgets .postbox-container .postbox').removeAttr('style');
				$('.toplevel_page_mkdo_dashboard #dashboard-widgets-wrap #dashboard-widgets .postbox-container .postbox .inside').removeAttr('style');

				//if($(this).closest('.postbox').hasClass('closed'))
				{	
					resize_content();
					resize_inside();

					resize_rows = Math.ceil(resize_content_count / 3);

				}
			}, 100);
			
		});

		resize_content();
		resize_inside();

		resize_rows = Math.ceil(resize_content_count / 3);

		$(window).resize(function(){
			$('.toplevel_page_mkdo_content_menu #dashboard-widgets-wrap #dashboard-widgets .postbox-container .postbox').removeAttr('style');
			$('.toplevel_page_mkdo_content_menu #dashboard-widgets-wrap #dashboard-widgets .postbox-container .postbox .inside').removeAttr('style');
			$('.toplevel_page_mkdo_dashboard #dashboard-widgets-wrap #dashboard-widgets .postbox-container .postbox').removeAttr('style');
			$('.toplevel_page_mkdo_dashboard #dashboard-widgets-wrap #dashboard-widgets .postbox-container .postbox .inside').removeAttr('style');

			resize_content();
			resize_inside();
			resize_rows = Math.ceil(resize_content_count / 3);
			for( var i = 1; i == resize_rows; i++);
			{
				resize_content();
				resize_inside();
			}
		});

})( jQuery );
