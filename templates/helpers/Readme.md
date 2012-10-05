# Journal-Plugin gallery

You can simply upload images in your journal entry and they will be displayed as a image gallery at the bottom of the entry in the full view.

In order to present them like a slideshow you have to include the following files and code into your blog main template.
Let's use prettyPhoto (you can take any lightbox software) http://www.no-margin-for-errors.com/projects/prettyphoto-jquery-lightbox-clone/
• add prettyPhoto.js in your site/web/js dir (currently we use 3.1.2) and add the resource to the head of your template
• add prettyPhoto.css into your site/web/css dir and add the resource to the head of your template
• add prettyPhoto images dir to your site/web/images director
• add a js file into your site/web/js dir and include it into the head of your template


## Init Code

jQuery(document).ready(function() {
	jQuery("a[rel^='gallery']").each(function() {
		this.href = this.href.replace('&', '&amp;');
	}).prettyPhoto({
		social_tools: false,
		deeplinking: false,
		opacity: 0.60,
		showTitle: true,
		/* 'pp_default' / light_rounded / dark_rounded / light_square / dark_square / facebook */
		theme: 'pp_default',
		overlay_gallery: false,
		allow_resize: true
	});
});

## Use your own template and js

You can use your own templates by adding the following templates to a directory in your site/templates dir:
• gallery.tmpl (make sure that the identifier "{{items;templateFlag=NO_NEWLINE}}" is part of the new template)
• gallery_item.tmpl (make sure that the identifier "{{url}}, {{description}}" is part of the new template)

Follow the instructions in the prettyPhoto example to add the required resources for your custom made solution