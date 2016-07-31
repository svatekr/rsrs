$(function () {

	$('#srch-term').focus(function () {
		$(this).animate({width: '+=140'}, 'slow');
	}).blur(function () {
		$(this).animate({width: '-=140'}, 'slow');
	});

});
