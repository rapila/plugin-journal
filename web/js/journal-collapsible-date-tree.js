jQuery(function() {
	var tree = jQuery('#journal_collapsible_page_tree');
	tree.find('li a').nextAll('ol').end().click(function() {
		return jQuery(this).nextAll('ol').toggle().length ? false : true;
	});
});