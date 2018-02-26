(function(){
	var ratingBlocks = $('.merchant_rating_fixed');
	var totalBlocks = ratingBlocks.length;
	var ratings = $('.merchant_ratings');
	var rating = parseFloat(ratings.attr('data-value'));
	ratingBlocks.css('opacity', '0.5');
	for (var i = 0; i < totalBlocks; i++) {
		var everyEle = ratingBlocks.eq(i);
		if(parseFloat(everyEle.attr('data-value')) <= rating) {
			everyEle.css('opacity', 1);
		}
	}
	
	ratingBlocks = $('.write_review_rating');
	totalBlocks = ratingBlocks.length;
	ratings = $('.write_review_ratings');
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