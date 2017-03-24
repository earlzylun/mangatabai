'use strict';

// IIFE - Immediately Invoked Function Expression
(function($, window, document) {
	// The $ is now locally scoped 
	// Listen for the jQuery ready event on the document
	$(function() {
		// The DOM is ready!

		// Invokes the accordion of the left menu
		$(document).ready(function() {
			$('#content').accordion({
				event			: 'click',
				collapsible		: true,
				heightStyle		: 'content',
				active			: active_sidebar
			});
		});


	});

	var viewport = window.innerHeight;
	var speed = 300;
	var tops = Array();
	var heights = Array();

	// Updates the viewport size in case of a window resize
	$(window).resize(function() {
		viewport = window.innerHeight;
	});

	// Keyboard controls
	$(window).load(function() {				
		$( 'img' ).each(function() {
			var pos = $(this).position();
			tops[tops.length] = pos.top;
			heights[heights.length] = pos.top+$(this).height();
		});
		
		$(document).on('keydown', function(e) {
			var code = e.keyCode || e.which;
			if(code == 39) { //Right Arrow
				var id = get_active_id();
				if($(window).scrollTop() < tops[id]) {
					$('html, body').animate({
						scrollTop: $('#'+(id+1)).offset().top
					}, speed);
				}
				else
					$('html, body').animate({
						scrollTop: $('#'+(id+1)).offset().top + ( $('#'+(id+1)).height() - viewport )
					}, speed);
			}
			else if(code == 37 ) { //Left Arrow
				var id = get_active_id();
				
				if(id > 0) {
					if($(window).scrollTop() == $('#'+(id)).offset().top)
						id--;
					$('html, body').animate({
						scrollTop: $('#'+(id)).offset().top
					}, speed);
				}
				else
					$('html, body').animate({
						scrollTop: 0
					}, speed);
			}
		});
	});

	function get_active_id() {
		var id = 0;
		for(var x=0; x<tops.length ;x++) {
			if(($(window).scrollTop()+viewport) < heights[x])
				break;
			id++;
		}
		
		return id;
	}
}(window.jQuery, window, document));