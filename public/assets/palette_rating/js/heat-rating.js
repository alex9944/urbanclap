(function(){
	var ratingBlocks = $('.rating-block');
	var totalBlocks = ratingBlocks.length;
	var ratings = $('.ratings');
	ratingBlocks.mouseover(function(){
		var rating = parseFloat($(this).attr('data-value'));
		ratingBlocks.css('opacity', '0.5');
		ratings.html($(this).attr('data-value'));
		$('#ratings-input').val(rating);
		for (var i = 0; i < totalBlocks; i++) {
			var everyEle = ratingBlocks.eq(i);
			if(parseFloat(everyEle.attr('data-value')) <= rating) {
				everyEle.css('opacity', 1);
			}
		}
	});
})();