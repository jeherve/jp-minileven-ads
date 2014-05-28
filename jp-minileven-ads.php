<?php
/*
 * Plugin Name: Mobile Theme Ads for Jetpack
 * Plugin URI: http://wordpress.org/plugins/jetpack-mobile-theme-ads/
 * Description: Adds Adsense ads before or after the content on post pages, in Jetpack Mobile theme
 * Author: Jeremy Herve
 * Version: 1.0
 * Author URI: http://jeremyherve.com
 * License: GPL2+
 * Text Domain: jp_mini_ads
 */

// Check if we are on mobile
function jp_mini_ads_is_mobile() {
	// Are Jetpack Mobile functions available?
	if ( ! function_exists( 'jetpack_is_mobile' ) ) {
		return false;
	}

	// Is Mobile theme showing?
	if ( isset( $_COOKIE['akm_mobile'] ) && $_COOKIE['akm_mobile'] == 'false' ) {
		return false;
	}

	return jetpack_is_mobile();
}

// On Mobile, and on a page selected in the Mobile Ads options? Show the ads
function jp_mini_ads_maybe_add_filter() {

	// Are we on Mobile
	if ( jp_mini_ads_is_mobile() ) :

	$options = get_option( 'jp_mini_ads_strings' );

	if ( isset( $options['show']['front'] ) && is_home() || is_front_page() ) {
		add_filter( 'the_content', 'jp_mini_ads_show_ads' );
	}

	if ( isset( $options['show']['post'] ) && is_single() ) {
		add_filter( 'the_content', 'jp_mini_ads_show_ads' );
	}

	if ( isset( $options['show']['page'] ) && is_page() ) {
		add_filter( 'the_content', 'jp_mini_ads_show_ads' );
	}

	endif; // End check if we're on mobile

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

	// Allow custom ads instead of Google Adsense
	$ads = apply_filters( 'jp_mini_ads_output', $ads );

	// Wrap the ads around a div, for styling
	$ads = '<div class="sharedaddy jp_mini_ads">' . $ads . '</div>';

	// Change ad placement if option is checked
	if ( isset( $options['before_content'] ) ) {
		return $ads . $content;
	} else {
		return $content . $ads;
	}
}

// Custom ad embed code
function jp_mini_ads_custom_code( $ads ) {
	$options = get_option( 'jp_mini_ads_strings' );

	if ( isset( $options['custom_ad_code'] ) ) {
		return $options['custom_ad_code'];
	} else {
		return $ads;
	}
}
add_filter( 'jp_mini_ads_output', 'jp_mini_ads_custom_code' );


/*
 * Options page
 */
// Init plugin options
function jp_mini_ads_init() {
	register_setting( 'jp_mini_ads_options', 'jp_mini_ads_strings', 'jp_mini_ads_validate' );
}
add_action( 'admin_init', 'jp_mini_ads_init' );

// Add menu page
function jp_mini_ads_add_page() {
	add_options_page( __( 'Mobile Ads', 'jp_mini_ads' ), __( 'Mobile Ads Settings', 'jp_mini_ads' ), 'manage_options', 'jp_mini_ads', 'jp_mini_ads_do_page' );
}
add_action( 'admin_menu', 'jp_mini_ads_add_page' );

// Draw the menu page itself
function jp_mini_ads_do_page() {
	?>
	<div class="wrap">
		<h2><?php _e( 'Mobile Ads Settings', 'jp_mini_ads' ); ?></h2>
		<form method="post" action="options.php">
			<?php

			settings_fields( 'jp_mini_ads_options' );
			$options = get_option( 'jp_mini_ads_strings' );

			// Default to show ads after the content
			if ( ! isset( $options['before_content'] ) ) {
				$options['before_content'] = 0;
			}

			// Default to show ads on singular pages
			if ( ! isset( $options['show'] ) ) {
				$options['show'] = array(
					'front' => 0,
					'post'  => 1,
					'page'  => 1,
				);
			}
			?>

			<h3><?php _e( 'Ad placement', 'jp_mini_ads' ); ?></h3>

			<table class="form-table">
				<tr valign="top"><th scope="row"><?php _e( 'Would you like ads to be displayed before the post content?', 'jp_mini_ads' ); ?></th>
					<td><input type="checkbox" name="jp_mini_ads_strings[before_content]" value="1" <?php checked( 1, $options['before_content'], true ); ?> /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'Show ads on:', 'jp_mini_ads' ); ?></th>
					<td>
						<label>
						<input type="checkbox" name="jp_mini_ads_strings[show]" value="1" <?php checked( 1, $options['show']['front'], true ); ?> />
						<?php _e( 'Front Page', 'jp_mini_ads' ); ?>
						</label>
						<br>
						<label>
						<input type="checkbox" name="jp_mini_ads_strings[show]" value="1" <?php checked( 1, $options['show']['post'], true ); ?> />
						<?php _e( 'Posts', 'jp_mini_ads' ); ?>
						</label>
						<br>
						<label>
						<input type="checkbox" name="jp_mini_ads_strings[show]" value="1" <?php checked( 1, $options['show']['page'], true ); ?> />
						<?php _e( 'Pages', 'jp_mini_ads' ); ?>
						</label>
					</td>
				</tr>
			</table>

			<h3><?php _e( 'Ad code', 'jp_mini_ads' ); ?></h3>

			<p><?php _e( 'If you want to add Google Adsense ads, fill in the fields below:', 'jp_mini_ads' ); ?></p>

			<table class="form-table">
				<tr valign="top"><th scope="row"><?php _e( 'Enter your google_ad_client ID here:', 'jp_mini_ads' ); ?></th>
					<td><input type="text" name="jp_mini_ads_strings[google_ad_client]" value="<?php echo $options['google_ad_client']; ?>" /></td>
				</tr>
				<tr valign="top"><th scope="row"><?php _e( 'Enter your google_ad_slot ID here:', 'jp_mini_ads' ); ?></th>
					<td><input type="text" name="jp_mini_ads_strings[google_ad_slot]" value="<?php echo $options['google_ad_slot']; ?>" /></td>
				</tr>
				<tr valign="top"><th scope="row"><?php _e( 'Enter the ad width here:', 'jp_mini_ads' ); ?></th>
					<td><input type="text" name="jp_mini_ads_strings[google_ad_width]" value="<?php echo $options['google_ad_width']; ?>" /></td>
				</tr>
				<tr valign="top"><th scope="row"><?php _e( 'Enter the ad height here:', 'jp_mini_ads' ); ?></th>
					<td><input type="text" name="jp_mini_ads_strings[google_ad_height]" value="<?php echo $options['google_ad_height']; ?>" /></td>
				</tr>
			</table>

			<h3><?php _e( 'Custom ads', 'jetpack' ); ?></h3>

			<p><?php _e( 'If you want to use another ad network, you can enter the ad embed code below:', 'jetpack' ); ?></p>

			<table class="form-table">
				<tr valign="top">
					<td><textarea class="widefat" id="jp_mini_ads_custom_code" name="jp_mini_ads_strings[custom_ad_code]" rows="8" cols="20"><?php
						if ( isset( $options['custom_ad_code'] ) ) {
							echo $options['custom_ad_code'];
						}
						?></textarea></td>
				</tr>
			</table>

			<p class="submit">
				<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save configuration', 'jetpack' ); ?>" />
			</p>
		</form>
	</div>
	<?php
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function jp_mini_ads_validate( $input ) {

	$allowed_html = array(
		'a' => array(
			'href' => array(),
		),
		'img' => array(
			'src' => array(),
		),
	);

	$input['google_ad_client']  = absint( $input['google_ad_client'] );
	$input['google_ad_slot']    = absint( $input['google_ad_slot'] );
	$input['google_ad_width']   = absint( $input['google_ad_width'] );
	$input['google_ad_height']  = absint( $input['google_ad_height'] );
	$input['custom_ad_code']    = wp_kses( $input['custom_ad_code'], $allowed_html );

	return $input;
}
