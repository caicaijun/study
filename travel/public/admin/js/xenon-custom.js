var public_vars = public_vars || {};

;(function($, window, undefined){
	
	"use strict";
	
	$(document).ready(function()
	{	
		// Main Vars
		public_vars.$body                 = $("body");
		public_vars.$pageContainer        = public_vars.$body.find(".page-container");
		public_vars.$sidebarMenu          = public_vars.$pageContainer.find('.sidebar-menu');
		
		
		
		// Setup Sidebar Menu
		setup_sidebar_menu();
		
		
		
		
		// Perfect Scrollbar
		if($.isFunction($.fn.perfectScrollbar))
		{
			if(public_vars.$sidebarMenu.hasClass('fixed'))
				ps_init();
				
			$(".ps-scrollbar").each(function(i, el)
			{
				var $el = $(el);
				
				$el.perfectScrollbar({
					wheelPropagation: false
				});
			});
			
			// Scrollable
			$("div.scrollable").each(function(i, el)
			{
				var $this = $(el),
					max_height = parseInt(attrDefault($this, 'max-height', 200), 10);
				
				max_height = max_height < 0 ? 200 : max_height;
				
				$this.css({maxHeight: max_height}).perfectScrollbar({
					wheelPropagation: true
				});
			});
		}
		

		
	
		
	});



})(jQuery, window);











// Sideber Menu Setup function
var sm_duration = .2,
	sm_transition_delay = 150;

function setup_sidebar_menu()
{
	if(public_vars.$sidebarMenu.length)
	{
		var $items_with_subs = public_vars.$sidebarMenu.find('li:has(> ul)'),
			toggle_others = public_vars.$sidebarMenu.hasClass('toggle-others');
		
		$items_with_subs.filter('.active').addClass('expanded');
		
		$items_with_subs.each(function(i, el)
		{
			var $li = jQuery(el),
				$a = $li.children('a'),
				$sub = $li.children('ul');
			
			$li.addClass('has-sub');
			
			$a.on('click', function(ev)
			{
				ev.preventDefault();
				
				if(toggle_others)
				{
					sidebar_menu_close_items_siblings($li);
				}
				
				if($li.hasClass('expanded') || $li.hasClass('opened'))
					sidebar_menu_item_collapse($li, $sub);
				else
					sidebar_menu_item_expand($li, $sub);
			});
		});
	}
}

function sidebar_menu_item_expand($li, $sub)
{
	if($li.data('is-busy') || ($li.parent('.main-menu').length && public_vars.$sidebarMenu.hasClass('collapsed')))
		return;
		
	$li.addClass('expanded').data('is-busy', true);
	$sub.show();
	
	var $sub_items 	  = $sub.children(),
		sub_height	= $sub.outerHeight(),
		
		win_y			 = jQuery(window).height(),
		total_height	  = $li.outerHeight(),
		current_y		 = public_vars.$sidebarMenu.scrollTop(),
		item_max_y		= $li.position().top + current_y,
		fit_to_viewpport  = public_vars.$sidebarMenu.hasClass('fit-in-viewport');
		
	$sub_items.addClass('is-hidden');
	$sub.height(0);
	
	
	TweenMax.to($sub, sm_duration, {css: {height: sub_height}, onUpdate: ps_update, onComplete: function(){ 
		$sub.height(''); 
	}});
	
	var interval_1 = $li.data('sub_i_1'),
		interval_2 = $li.data('sub_i_2');
	
	window.clearTimeout(interval_1);
	
	interval_1 = setTimeout(function()
	{
		$sub_items.each(function(i, el)
		{
			var $sub_item = jQuery(el);
			
			$sub_item.addClass('is-shown');
		});
		
		var finish_on = sm_transition_delay * $sub_items.length,
			t_duration = parseFloat($sub_items.eq(0).css('transition-duration')),
			t_delay = parseFloat($sub_items.last().css('transition-delay'));
		
		if(t_duration && t_delay)
		{
			finish_on = (t_duration + t_delay) * 1000;
		}
		
		// In the end
		window.clearTimeout(interval_2);
	
		interval_2 = setTimeout(function()
		{
			$sub_items.removeClass('is-hidden is-shown');
			
		}, finish_on);
	
		
		$li.data('is-busy', false);
		
	}, 0);
	
	$li.data('sub_i_1', interval_1),
	$li.data('sub_i_2', interval_2);
}









function sidebar_menu_item_collapse($li, $sub)
{
	if($li.data('is-busy'))
		return;
	
	var $sub_items = $sub.children();
	
	$li.removeClass('expanded').data('is-busy', true);
	$sub_items.addClass('hidden-item');
	
	TweenMax.to($sub, sm_duration, {css: {height: 0}, onUpdate: ps_update, onComplete: function()
	{
		$li.data('is-busy', false).removeClass('opened');
		
		$sub.attr('style', '').hide();
		$sub_items.removeClass('hidden-item');
		
		$li.find('li.expanded ul').attr('style', '').hide().parent().removeClass('expanded');
		
		ps_update(true);
	}});
}













function sidebar_menu_close_items_siblings($li)
{
	$li.siblings().not($li).filter('.expanded, .opened').each(function(i, el)
	{
		var $_li = jQuery(el),
			$_sub = $_li.children('ul');
		
		sidebar_menu_item_collapse($_li, $_sub);
	});
}




// Perfect scroll bar functions by Arlind Nushi
function ps_update(destroy_init)
{
	if(isxs())
		return;
		
	if(jQuery.isFunction(jQuery.fn.perfectScrollbar))
	{
		if(public_vars.$sidebarMenu.hasClass('collapsed'))
		{
			return;
		}
		
		public_vars.$sidebarMenu.find('.sidebar-menu-inner').perfectScrollbar('update');
		
		if(destroy_init)
		{
			ps_destroy();
			ps_init();
		}
	}
}


function ps_init()
{
	if(isxs())
		return;
		
	if(jQuery.isFunction(jQuery.fn.perfectScrollbar))
	{
		if(public_vars.$sidebarMenu.hasClass('collapsed') || ! public_vars.$sidebarMenu.hasClass('fixed'))
		{
			return;
		}
		
		public_vars.$sidebarMenu.find('.sidebar-menu-inner').perfectScrollbar({
			wheelSpeed: 2,
			wheelPropagation: public_vars.wheelPropagation
		});
	}
}




