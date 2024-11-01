<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              infectionrank.org
 * @since             2.0.3
 * @package           Infection_rank
 *
 * @wordpress-plugin
 * Plugin Name:       VirusWeather COVID-19 Coronavirus
 * Plugin URI:        https://infectionrank.org/coronavirus/widget/
 * Description:       Dynamic PNG image shows local COVID-19 coronavirus statistics and calculated relative area risk rank for 4500+ local areas world-wide based on website visitor IP geolocation. Integrated chart clearly shows the rate of growth, flattening, and deceleration. Your web page visitors will see personalized stats that include local threat level around them as well as country. 
 * Version:           2.0.3
 * Author:            Infection Risk Organization Corp.
 * Author URI:        https://infectionrank.org/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       infection-rank
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 2.0.3 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'VIRUS_WEATHER_VERSION', '2.0.3' );
define( 'INFECTION_RANK_LINK', 'https://infectionrank.org/coronavirus/widget/' );
define( 'STATIC_LINK', 'https://infectionrank.org/coronavirus/' );
define( 'DYNAMIC_LINK', 'https://infectionrank.org/' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-virus-weather-activator.php
 */
function activate_virus_weather() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-virus-weather-activator.php';
	Virus_Weather_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-virus-weather-deactivator.php
 */
function deactivate_virus_weather() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-virus-weather-deactivator.php';
	Virus_Weather_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_virus_weather' );
register_deactivation_hook( __FILE__, 'deactivate_virus_weather' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-virus-weather.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.3
 */
function run_virus_weather() {

	$plugin = new Virus_Weather();
	$plugin->run();

}

run_virus_weather();
