<?php
/*
 * Plugin Name: Jetpack Mobile Theme Ads
 * Plugin URI: http://wordpress.org/extend/plugins/jetpack-mobile-theme-ads/
 * Description: Adds Adsense ads after the content on post pages, in Jetpack Mobile theme
 * Author: Jeremy Herve
 * Version: 1.0
 * Author URI: http://jeremyherve.com
 * License: GPL2+
 * Text Domain: jetpack
 */
 
// Check if we are on mobile
// Props @saracannon http://ran.ge/2012/12/05/parallax-and-mobile/
function jp_mini_ads_is_mobile_or_tablet() {
    if ( ! class_exists( 'Jetpack_User_Agent_Info' ) )
    	return false;

    $ua_info = new Jetpack_User_Agent_Info();
    return ( jetpack_is_mobile() || $ua_info->is_tablet() );
}

// On Mobile, and on a single page? Let's add the ads
function jp_mini_ads_maybe_add_filter() {
	if ( jp_mini_ads_is_mobile_or_tablet() && is_singular() )
		add_filter( 'the_content', 'jp_mini_ads_show_ads' );
}
add_action( 'wp_head', 'jp_mini_ads_maybe_add_filter' );

// Show Ads
function jp_mini_ads_show_ads( $content ) {
	$options = get_option( 'jp_mini_ads_strings' );
	$ads = '
	<script type="text/javascript">
	<!--
	google_ad_client = "'. $options['google_ad_client'] .'";
	google_ad_slot = "'. $options['google_ad_slot'] .'";
	google_ad_width = '. $options['google_ad_width'] .';
	google_ad_height = '. $options['google_ad_height'] .';
	//-->
	</script>
	<script type="text/javascript" src="//pagead2.googlesyndication.com/pagead/show_ads.js"></script>
	';

	return $content . $ads;
}

/*
 * Options page
 */

add_action( 'admin_init', 'jp_mini_ads_init' );
add_action( 'admin_menu', 'jp_mini_ads_add_page' );

// Init plugin options
function jp_mini_ads_init() {
	register_setting( 'jp_mini_ads_options', 'jp_mini_ads_strings', 'jp_mini_ads_validate' );
}

// Add menu page
function jp_mini_ads_add_page() {
	add_options_page( 'Mobile Ads', 'Mobile Ads Settings', 'manage_options', 'jp_mini_ads', 'jp_mini_ads_do_page' );
}

// Draw the menu page itself
function jp_mini_ads_do_page() {
	?>
	<div class="wrap">
		<h2>Mobile Ads Settings</h2>
		<form method="post" action="options.php">
			<?php settings_fields( 'jp_mini_ads_options' ); ?>
			<?php $options = get_option( 'jp_mini_ads_strings' ); ?>
			<table class="form-table">
				<tr valign="top"><th scope="row">Enter your google_ad_client ID here:</th>
					<td><input type="text" name="jp_mini_ads_strings[google_ad_client]" value="<?php echo $options['google_ad_client']; ?>" /></td>
				</tr>
				<tr valign="top"><th scope="row">Enter your google_ad_slot ID here:</th>
					<td><input type="text" name="jp_mini_ads_strings[google_ad_slot]" value="<?php echo $options['google_ad_slot']; ?>" /></td>
				</tr>
				<tr valign="top"><th scope="row">Enter the ad width here:</th>
					<td><input type="text" name="jp_mini_ads_strings[google_ad_width]" value="<?php echo $options['google_ad_width']; ?>" /></td>
				</tr>
				<tr valign="top"><th scope="row">Enter the ad height here:</th>
					<td><input type="text" name="jp_mini_ads_strings[google_ad_height]" value="<?php echo $options['google_ad_height']; ?>" /></td>
				</tr>
			</table>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'jetpack' ) ?>" />
			</p>
		</form>
	</div>
	<?php	
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function jp_mini_ads_validate($input) {

	$input['google_ad_client'] =  wp_filter_nohtml_kses($input['google_ad_client']);
	$input['google_ad_slot'] =  wp_filter_nohtml_kses($input['google_ad_slot']);
	$input['google_ad_width'] =  wp_filter_nohtml_kses($input['google_ad_width']);
	$input['google_ad_height'] =  wp_filter_nohtml_kses($input['google_ad_height']);
	
	return $input;
}
