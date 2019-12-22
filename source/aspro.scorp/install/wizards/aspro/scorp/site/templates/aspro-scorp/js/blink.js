(function() {

	$.fn.blink		= function() {

		return this.each(function() {
			$(this).hover(function() {
				if ($(this).is(':animated'))
					return;

				$(this).animate({ opacity: 0.75 }, 150, function() {
					$(this).animate({ opacity: 1 }, 100, 'linear');
				});
			}, function() {

			});
		});

	};

	
})(jQuery);

