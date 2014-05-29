=== Mobile Theme Ads for Jetpack ===
Contributors: jeherve
Tags: WordPress.com, Jetpack, mobile, minileven, adsense, ads
Requires at least: 3.8
Tested up to: 3.9.1
Stable tag: 1.1

Adds ads before or after your posts and pages, in Jetpack's Mobile theme

== Description ==

Add ads before or after your posts and pages, in Jetpack's Mobile theme

Important: for this plugin to work, you must activate [Jetpack](http://wordpress.org/plugins/jetpack/) first, and activate the Mobile Theme module.

This plugin is a work in progress. You can report issues [here](http://wordpress.org/plugins/jetpack-mobile-theme-ads/), or submit a pull request [on GitHub](https://github.com/jeherve/jp-minileven-ads).


== Installation ==

1. Install the Jetpack plugin, connect the plugin to WordPress.com
2. Activate the Mobile Theme module
3. Install the Mobile Theme Ads plugin via the WordPress.org plugin repository, or via your dashboard
4. Activate the plugin
5. Go to Settings > Mobile Ads Settings in your dashboard, and enter your Adsense settings
6. Enjoy :)

== FAQ ==

= How do I switch back to Google Adsense after having used the Custom Ads option? =

You can remove the custom code you placed in the Custom Ads field, save your changes, and the plugin will start using Google Adsense again.

== Changelog ==

= 1.1 =
* Improve the Mobile Theme detection to avoid showing ads when not using the Mobile Theme
* Add an option to place the ads before the content
* Add an option to display the ads on the home page
* Make sure the ads are centered and stay within the Mobile Theme's content width
* Add a new filter, `jp_mini_ads_output`, allowing you to customize the output of the ads
* Add an option to use custom ads instead of Google Adsense
* Switch to Adsense's asynchronous code to avoid slowness on slow networks
* The plugin is now translation-ready, and has been translated to French

= 1.0 =
* Initial Release
