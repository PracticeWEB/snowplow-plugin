<?php

/*
Plugin Name: Snowplow Plugin
Plugin URI: http://www.practiceweb.co.uk
Description: Snowplow tracking.
Version: 1.0
Author: David Robinson
Author URI: http://www.practiceweb.co.uk
License: Proprietary
*/

function snowplow_add_scripts()
{
    $collector_url = get_option('snowplow-collector-url');
    $application_id = get_option('snowplow-application-id');
    $snowplow_tracker_url = get_option('snowplow-tracker-url', '//d1fc8wv8zag5ca.cloudfront.net/2.8.2/sp.js');
    if ($collector_url && $application_id && $snowplow_tracker_url) {
        // Loader script ensures that the snowplow library is loaded.
        wp_enqueue_script('snowplow-loader', plugin_dir_url(__FILE__) . 'js/snowplowLoader.js');
        $loader_data = array(
            'tracker_url' => $snowplow_tracker_url,
        );
        wp_localize_script('snowplow-loader', 'loader_data', $loader_data);

        // Tracker script integrates the actual track events.
        $cookie_domain = snowplow_get_cookie_domain();
        $parameters = array(
            'collector' => $collector_url,
            'application_id' => $application_id,
            'cookie_domain' => $cookie_domain,
        );
        wp_enqueue_script('snowplow_tracker', plugin_dir_url(__FILE__) . 'js/snowplowTracker.js');
        wp_localize_script('snowplow_tracker', 'tracker_data', $parameters);
    }
}
add_action('wp_enqueue_scripts', 'snowplow_add_scripts');


/**
 * Add Snow plow settings.
 */
function snowplow_settings_init()
{
    // Add a snowplow section to general.
    add_settings_section('snowplow', 'Snowplow Settings', 'snowplow_settings_section', 'general');
    // Tracker url
    add_settings_field('snowplow-tracker-url', 'Snowplow Tracker url', 'snowplow_setting_tracker', 'general', 'snowplow');
    register_setting('general', 'snowplow-tracker-url');
    // Collector url.
    add_settings_field('snowplow-collector-url', 'Snowplow Collector', 'snowplow_setting_collector', 'general', 'snowplow');
    register_setting('general', 'snowplow-collector-url');
    // Application Id
    add_settings_field('snowplow-application-id', 'Snowplow Application Id', 'snowplow_setting_appid', 'general', 'snowplow');
    register_setting('general', 'snowplow-application-id');
}
add_action('admin_init', 'snowplow_settings_init');

/**
 * Render the settings section.
 */
function snowplow_settings_section()
{
    echo '<p>This controls snowplow tracking configuration</p>';
}

/**
 * Field for tracker url.
 */
function snowplow_setting_tracker()
{
    $tracker = get_option('snowplow-tracker-url', '//d1fc8wv8zag5ca.cloudfront.net/2.8.2/sp.js');
    echo '<input name="snowplow-tracker-url" id="snowplow-tracker-url" type="textfield" value="' . htmlspecialchars($tracker) . '">';
}

/**
 * Field for collector url.
 */
function snowplow_setting_collector()
{
    $collector = get_option('snowplow-collector-url');
    echo '<input name ="snowplow-collector-url" id="snowplow-collector-url" type="textfield" value="' . htmlspecialchars($collector) . '">';
}

/**
 * Field for application id.
 */
function snowplow_setting_appid()
{
    $application_id = get_option('snowplow-application-id');
    echo '<input name ="snowplow-application-id" id="snowplow-application-id" type="textfield" value="' . htmlspecialchars($application_id) . '">';
}

/**
 * Helper to get the cookie domain for snowplow.
 *
 * @return string
 *   The cookie domain.
 */
function snowplow_get_cookie_domain()
{
    $cookie_domain = COOKIE_DOMAIN;
    if (!$cookie_domain) {
        $site_url = get_site_url();
        $host = parse_url($site_url, PHP_URL_HOST);
        $cookie_domain = preg_replace('/^www\./', '', $host);
    }
    return $cookie_domain;
}
