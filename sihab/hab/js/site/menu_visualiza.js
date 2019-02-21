var topPositionContainer = -(Number($('section#section_titular').offset().top - $('div#div_container').offset().top));
$('li.link_visualiza').livequery('click', function() {
		var element = $('section#' + $(this).attr('rel') + '');
		var elementTop = Number($(this).attr('reltop'));
		var sectionTop = Number(elementTop > 0 ? (elementTop + topPositionContainer) : elementTop);
		var sectionPositionTop = Number($('section#section_titular').offset().top);
		var pagePosition = Number(element.offset().top) - Number(sectionPositionTop + sectionTop);
		var menuPosition = '' + (elementTop > 0 ? topPositionContainer : 0) + 'px 0 0 0 ';
		$('html, body').animate({
				scrollTop : pagePosition
		}, 100);
		$('ul.menu-floating').animate({
				'margin' : menuPosition
		}, 100);
		return false;
});
$(window).scroll(function() {
		var windowPosition = Number($(window).scrollTop());
		var menuPosition = Number($('ul.menu-floating').offset().top);
		var sectionPositionTop = Number($('section#section_titular').offset().top);
		var divContainerPosition = Number($('div#div_container').offset().top);
		// console.log(windowPosition+ ' - '+menuPosition+ ' - '+sectionPositionTop+ ' - ' +divContainerPosition+' - '+(+topPositionContainer));
//		if ((windowPosition < (topPositionContainer * (-1))) || menuPosition < sectionPositionTop) {
				if (menuPosition < sectionPositionTop) {
				// console.log(windowPosition+ ' - '+menuPosition+ ' - '+sectionPositionTop+ ' - ' +divContainerPosition+' - '+(topPositionContainer));
				$('ul.menu-floating').animate({
						'margin' : '-' + (windowPosition) + 'px 0 0 0 '
				}, 0);
				return false;
		}
});

// $('li.link_visualiza').livequery('click', function() {
// var element = $('section#' + $(this).attr('rel') + '');
// var topPosition = element.offset().top - (Number($(this).attr('reltop')));
// $('html, body').animate({
// scrollTop : topPosition
// }, 800);
// $('ul.menu-floating').animate({
// 'margin' : '' + $(this).attr('reltopmenu') + 'px 0 0 0 '
// }, 800);
// return false;
// });
