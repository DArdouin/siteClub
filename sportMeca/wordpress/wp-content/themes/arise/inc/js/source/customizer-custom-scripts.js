/******************* ARISE CUSTOMIZER CUSTOM SCRIPTS ******************************/
(function($) {
	$('.preview-notice').prepend('<span id="arise_upgrade"><a target="_blank" class="button btn-upgrade" href="' + arise_upgrade_links.upgrade_link + '">' + arise_upgrade_links.upgrade_text + '</a></span>');
	jQuery('#customize-info .btn-upgrade, .misc_links').click(function(event) {
		event.stopPropagation();
	});
})(jQuery);