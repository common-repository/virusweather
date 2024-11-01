<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://infectionrank.org/
 * @since      2.0.3
 *
 * @package    Virus_Weather
 * @subpackage Virus_Weather/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      2.0.3
 * @package    Virus_Weather
 * @subpackage Virus_Weather/includes 
 * @author     Ifection Risk Organization Corp.
 */
class Virus_Weather_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    2.0.3
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'virus-weather',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
