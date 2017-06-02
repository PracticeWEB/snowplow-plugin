<?php

/*
Plugin Name: Snowplow Plugin
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: 1.0
Author: david robinson
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/

add_action('wp_enqueue_scripts', 'wp_snowplow_add_scripts');
function wp_snowplow_add_scripts() {
    $collector = get_option('sp_collector');
    $application_id = get_option('sp_appid');
    if ($collector && $application_id) {
		wp_enqueue_script( 'snowplow_loader', plugin_dir_url( __FILE__ ) . 'js/snowplowLoader.js' );
		// Use localization to parameterise the script.
	    $cookie_domain = wp_snowplow_get_cookie_domain();
		$parameters = array(
			'collector' => $collector,
			'application_id' => $application_id,
			'cookie_domain' => $cookie_domain,
		);
		wp_enqueue_script( 'snowplow_tracker', plugin_dir_url( __FILE__ ) . 'js/snowplowTracker.js' );
	    wp_localize_script('snowplow_tracker', 'sp_data', $parameters);
	}
}

// TODO add settings
/**
 * Collector
 * App ID
 *
 */
function wp_snowplow_settings_init() {
	add_settings_section( 'snowplow', 'Snowplow Settings', 'wp_snowplow_settings_section', 'general' );

	add_settings_field( 'sp_collector', 'Snowplow Collector', 'wp_snowplow_setting_collector', 'general', 'snowplow');
	register_setting('general', 'sp_collector');
	add_settings_field( 'sp_appid', 'Snowplow Application Id', 'wp_snowplow_setting_appid', 'general', 'snowplow');
	register_setting('general', 'sp_appid');
}
add_action( 'admin_init', 'wp_snowplow_settings_init' );

function snowplow_settings_section() {
	echo '<p>This controls snowplow tracking configuration</p>';
}

function wp_snowplow_setting_collector() {
	$collector = get_option('sp_collector');
	echo '<input name ="sp_collector" id="sp_collector" type="textfield" value="'. htmlspecialchars($collector). '">';
}

function wp_snowplow_setting_appid() {
	$application_id = get_option('sp_appid');
	echo '<input name ="sp_appid" id="sp_collector" type="textfield" value="'. htmlspecialchars($application_id). '">';
}


function wp_snowplow_get_cookie_domain() {
	$cookie_domain = COOKIE_DOMAIN;
	if (!$cookie_domain) {
		$site_url = get_site_url();
		$host = parse_url($site_url, PHP_URL_HOST);
		$cookie_domain = preg_replace('/^www\./', '', $host);
	}
	return $cookie_domain;
}