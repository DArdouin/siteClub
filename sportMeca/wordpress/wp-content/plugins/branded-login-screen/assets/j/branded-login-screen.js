jQuery(document).ready(function( $ ) {

	$('#backtoblog a').prop('title','Back to Home Page');

	$('form#loginform').prepend('<h2>' + $('#loginform_title_sml').val() + '</h2><br class="clear">');
	$('form#lostpasswordform').prepend('<h3>' + $('#lostpasswordform1_title_sml').val() + '</h3><br class="clear">');
        $('form#lostpasswordform').prepend('<h2>' + $('#lostpasswordform2_title_sml').val() + '</h2><br class="clear">');
	$('form#resetpassform').prepend('<h3>' + $('#reserpassform_title_sml').val() + '</h3><br class="clear">');

	$('form#registerform').prepend('<h2>' + $('#registrationform_title_sml').val() + '</h2><br class="clear">');
	//TODO: make the alert boxes look prettier. :)

	$("p.reset-pass:contains('Enter your new password below')").hide();

	$("p.reset-pass:contains('Your password has been reset')").show().addClass('backtologin').removeClass('message').removeClass('reset-pass');
});