jQuery(function() {
	var tree = jQuery('#journal_tree');
	tree.find('li a').nextAll('ol').end().click(function() {
		return jQuery(this).nextAll('ol').toggle().length ? false : true;
	});
});