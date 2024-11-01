<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://infectionrank.org/
 * @since      2.0.3
 *
 * @package    Virus_Weather
 * @subpackage Virus_Weather/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Virus_Weather
 * @subpackage Virus_Weather/admin
 * @author     Ifection Risk Organization Corp.
 */
class Virus_Weather_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    2.0.3
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.0.3
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.0.3
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    2.0.3
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Virus_Weather_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Virus_Weather_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */


	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    2.0.3
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Virus_Weather_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Virus_Weather_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/virus-weather-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name . '-locations', plugin_dir_url( __FILE__ ) . 'js/locations.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/virus-weather-admin.css', $this->version, false );

	}

}
