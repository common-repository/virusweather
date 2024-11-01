<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://infectionrank.org/
 * @since      2.0.3
 *
 * @package    Virus_Weather
 * @subpackage Virus_Weather/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      2.0.3
 * @package    Virus_Weather
 * @subpackage Virus_Weather/includes
 * @author     Ifection Risk Organization Corp.
 */
class Virus_Weather {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    2.0.3
     * @access   protected
     * @var      Virus_Weather_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    2.0.3
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    2.0.3
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Image link
     *
     * @since    2.0.3
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $image_link;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    2.0.3
     */
    public function __construct() {
        if (defined('VIRUS_WEATHER_VERSION')) {
            $this->version = VIRUS_WEATHER_VERSION;
        } else {
            $this->version = '2.0.3';
        }
        $this->plugin_name = 'virus-weather';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Virus_Weather_Loader. Orchestrates the hooks of the plugin.
     * - Virus_Weather_i18n. Defines internationalization functionality.
     * - Virus_Weather_Admin. Defines all hooks for the admin area.
     * - Virus_Weather_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    2.0.3
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-virus-weather-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-virus-weather-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-virus-weather-admin.php';

        $this->loader = new Virus_Weather_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Virus_Weather_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    2.0.3
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new Virus_Weather_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    2.0.3
     * @access   private
     */
    private function define_admin_hooks() {

        $plugin_admin = new Virus_Weather_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        add_action('admin_menu', 'virus_weather_menu');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    2.0.3
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     2.0.3
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     2.0.3
     * @return    Virus_Weather_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     2.0.3
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

}

class Virus_Weather_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
                'virus_weather_widget', __('Virus Weather Widget', 'text_domain'), array(
            'customize_selective_refresh' => true,
                )
        );
    }

    public function form($instance) {

        $defaults = array(
            'title' => 'VirusWeather Covid-19 Local Threat Level Widget',
            'layout' => 'square',
            'theme' => 'light',
            'size' => 250
        );

        $size_labels = [
            'square' => __('Square Widget Side Size (Height and Width) in Pixels:', 'text_domain'),
            'horizontal' => __('Height of your horizontal widget side in pixels:', 'text_domain'),
            'casesapp' => __('Height of your vertical widget side in pixels:', 'text_domain'),
            'casesapp-advanced' => __('Height of your vertical widget side in pixels:', 'text_domain')
        ];

        $size_ranges = [
            'square' => [ 250, 500],
            'casesapp' => [ 300, 600],
            'casesapp-advanced' => [ 300, 600],
            'horizontal' => [ 90, 90],
        ];

        extract(wp_parse_args((array) $instance, $defaults));
        ?>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Widget Title', 'text_domain'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p class='virus-weather-theme'>
            <label for="<?php echo $this->get_field_id('theme'); ?>"><?php _e('Theme:', 'text_domain'); ?>&nbsp;</label>
            <?php
            $options = array(
                'light' => __('Light', 'text_domain', 'text_domain'),
                'dark' => __('Dark', 'text_domain'),
            );

            foreach ($options as $key => $name) {
                $item_id = $this->get_field_id('theme') . '-' . esc_attr($key);
                echo '<label for="' . $item_id . '"><input type="radio" ' .
                ' name="' . $this->get_field_name('theme') . '" id="' . $item_id .
                '" value="' . esc_attr($key) . '" ' . checked($theme, $key, false) . '>' .
                $name . '&nbsp; </label>';
            }
            ?>
        </p>


        <p class='virus-weather-layout'>
            <label for="<?php echo $this->get_field_id('layout'); ?>"><?php _e('Layout:', 'text_domain'); ?>&nbsp;</label>
            <?php
            $options = array(
                'casesapp' => __('Basic', 'text_domain'),
                'casesapp-advanced' => __('Advanced', 'text_domain'),
                'square' => __('Square', 'text_domain', 'text_domain'),
                'horizontal' => __('Horizontal Banner', 'text_domain'),
            );

            foreach ($options as $key => $name) {
                $item_id = $this->get_field_id('layout') . '-' . esc_attr($key);
                echo '<label for="' . $item_id . '"><input type="radio" ' .
                ' name="' . $this->get_field_name('layout') . '" id="' . $item_id .
                '" value="' . esc_attr($key) . '" ' . checked($layout, $key, false) . '>' .
                $name . '&nbsp; </label>';
            }
            ?>
        </p>

        <p class='virus-weather-mode'>
            <label for="<?php echo $this->get_field_id('layout'); ?>"><?php _e('Location:', 'text_domain'); ?>&nbsp;</label>
            <?php
            $options = array(
                'dynamic' => __('Dynamic by IP', 'text_domain', 'text_domain'),
                'static' => __('Static (Fixed)', 'text_domain'),
            );

            foreach ($options as $key => $name) {
                $item_id = $this->get_field_id('type') . '-' . esc_attr($key);
                echo '<label for="' . $item_id . '"><input type="radio" ' .
                ' name="' . $this->get_field_name('type') . '" id="' . $item_id .
                '" value="' . esc_attr($key) . '" ' .
                (( $country && $key == 'static' ) || (!$country && $key == 'dynamic' ) ? 'checked' : '' ) . '>' .
                $name . '&nbsp; </label>';
            }
            ?>
        </p>

        <p class='virus-weather-size' style='display: <?php echo $layout == 'horizontal' ? 'none' : 'block' ?>'>
            <label for="<?php echo $this->get_field_id('size'); ?>"
                   <?php foreach ($size_labels as $size_id => $size_label) { ?>data-text-<?php echo $size_id ?>="<?php echo $size_label ?>" <?php } ?>><?php echo $size_labels[$layout]; ?>&nbsp;</label>
            <input type="number" id="<?php echo esc_attr($this->get_field_id('size')); ?>" name="<?php echo esc_attr($this->get_field_name('size')) ?>" 
                   min="<?php echo $size_ranges[$layout][0] ?>" max="<?php echo $size_ranges[$layout][1] ?>"
                   <?php foreach ($size_ranges as $size_id => $size_dims) { ?>
                       data-min-<?php echo $size_id . '="' . $size_dims[0] . '"' ?> data-max-<?php echo $size_id . '="' . $size_dims[1] . '"' ?>
                   <?php } ?>
                   maxlength="3" step="1" value="<?php echo esc_attr($size); ?>">
            <span class='virusweather-size-range'>(<?php echo $size_ranges[$layout][0] ?>&ndash;<?php echo $size_ranges[$layout][1] ?> pixels)</span>
        </p>

        <p class='virus-weather-country<?php echo $country ? '' : ' hidden' ?>'>
            <label for="<?php echo esc_attr($this->get_field_id('country')); ?>"><?php _e('Country', 'text_domain'); ?></label>
            <input class='virus-weather-country' autocomplete="off" id="<?php echo esc_attr($this->get_field_id('country')); ?>" name="<?php echo esc_attr($this->get_field_name('country')); ?>" type="text" value="<?php echo esc_attr($country); ?>" />
        </p>

        <p class='virus-weather-state' style='display: <?php echo $state ? "block" : "none" ?>'>
            <label for="<?php echo esc_attr($this->get_field_id('state')); ?>"><?php _e(esc_attr($country) == 'United States' ? 'State' : 'Area', 'text_domain'); ?></label>
            <select id="<?php echo esc_attr($this->get_field_id('state')); ?>" name="<?php echo esc_attr($this->get_field_name('state')); ?>"><?php echo $state ? "<option selected value='{$state}'>{$state}</option>" : '' ?></select>
        </p>

        <p class='virus-weather-county' style='display: <?php echo $county ? "block" : "none" ?>'>
            <label for="<?php echo esc_attr($this->get_field_id('county')); ?>"><?php _e(esc_attr($country) == 'United States' ? 'County' : 'District', 'text_domain'); ?></label>
            <select id="<?php echo esc_attr($this->get_field_id('county')); ?>" name="<?php echo esc_attr($this->get_field_name('county')); ?>"><?php echo $county ? "<option selected value='{$county}'>{$county}</option>" : '' ?></select>
        </p>

        <p class='virus-weather-image'>
            <?php
            $img_options = [
                'theme' => $theme ? $theme : 'light',
                'size' => $size,
                'layout' => $layout ? $layout : 'square',
                'country' => $country ? $country : '',
                'state' => $state ? $state : '',
                'county' => $county ? $county : ''
            ];
            echo virus_weather_get_widget_image($img_options);
            ?>
        </p>

        <p class='virus-weather-shortcode'>
            <label for="">Shortcode (click to select):</label>
            <input type="text" class='virus-weather-shortcode-value' onclick="this.select();
                            return true;" value='<?php echo virus_weather_get_widget_shortcode($img_options) ?>'>
        </p>

        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = isset($new_instance['title']) ? wp_strip_all_tags($new_instance['title']) : '';
        $instance['theme'] = isset($new_instance['theme']) ? wp_strip_all_tags($new_instance['theme']) : '';
        $instance['layout'] = isset($new_instance['layout']) ? wp_strip_all_tags($new_instance['layout']) : '';
        $instance['size'] = isset($new_instance['size']) ? wp_strip_all_tags($new_instance['size']) : '';
        $instance['country'] = isset($new_instance['country']) ? wp_strip_all_tags($new_instance['country']) : '';
        $instance['state'] = isset($new_instance['state']) ? wp_strip_all_tags($new_instance['state']) : '';
        $instance['county'] = isset($new_instance['county']) ? wp_strip_all_tags($new_instance['county']) : '';
        return $instance;
    }

    public function widget($args, $instance) {
        extract($args);

        $title = isset($instance['title']) ? apply_filters('widget_title', $instance['title']) : '';
        $theme = isset($instance['theme']) ? $instance['theme'] : '';
        $layout = isset($instance['layout']) ? $instance['layout'] : '';
        $size = isset($instance['size']) ? $instance['size'] : '';
        $country = isset($instance['country']) ? $instance['country'] : '';
        $state = isset($instance['state']) ? $instance['state'] : $instance['area'];
        $county = isset($instance['county']) ? $instance['county'] : $instance['district'];

        echo $before_widget;

        $img_options = [
            'theme' => $theme, 'size' => $size, 'layout' => $layout,
            'country' => $country, 'state' => $state, 'county' => $county,
        ];

        if (!$country){
            $linkurl = DYNAMIC_LINK;
        } else{
            $linkurl = STATIC_LINK.
            ( $country ? strtolower(str_replace(' ', '-', $country)) : '' ) .
            ( $state ? '/' . strtolower(str_replace(' ', '-', $state)) : '' ) .
            ( $county ? '/' . strtolower(str_replace(' ', '-', $county)) : '' ).
            '/latest-stats/';
        }


        echo '<a href="' . $linkurl . '" target="_blank" title="' . $title . '">' . virus_weather_get_widget_image($img_options) . '</a>';

        echo $after_widget;
    }

}

function virus_weather_menu() {
    add_menu_page(
            __('Virus Weather'), __('Virus Weather'), 'manage_options', 'virus_weather_settings', 'virus_weather_settings_page', plugins_url('virusweather/admin/images/W_logo_19.png'), 90
    );
}

function virus_weather_settings_page() {

    $fp_settings = get_option("fp_settings");

    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    $nChannelsLimit = get_option('pagegen_channels');
    ?>

    <style>
        label.pgen_lbl {
            display: block; float: left; min-width: 15em; line-height: 2.2;
        }
    </style>

    <?php /*
      <form method="post" action="options.php" enctype="multipart/form-data">
     */ ?>
    <?php //settings_fields( 'pagegen-settings-group' );  ?>
    <?php //do_settings_sections( 'pagegen-settings-group' );  ?>

    <section class='virus-weather-generator'>
        <div class="content float-left">
        <div style="text-align: center;"><img src="<?php echo plugins_url('virusweather/admin/images/W_logo_90.png') ?>"></div>

        <h1><?php echo __('Generate Virus Weather Widget Code'); ?></h1>
        <div id="selectLocation" class="vw-option-wrap widget-content widget ">
            <h2 class="selectHeader2 geolocation"><?php echo __('Fixed Geolocation'); echo '<br>'; ?></h2>
            <div class="vw-option-select widget-content widget">
                <div class="vw-option-select dynamic_box">  
                    <label class='vw-label' for="vw-fixed-location">
                        <input type="radio" name="static-widget-type" value="static" class="radio-check-static" id="vw-fixed-location" />
                        <img class="radioImage" src="<?php echo plugin_dir_url(__FILE__) ?>../admin/images/ico_static_2x_g.png" title="Static <?php echo __('Infection Rank Widgets'); ?>">
                        <p><?php echo __('This mode allows permanent display of Coronavirus stats for the location selected when generating the widget.') ?></p>
                    </label>
                </div>      
            </div>
            <div class="vw-option-select dynamic_box_text"> 
                <div class="seloc">Select Location:</div>
                <p class="virus-weather-country virus-weather-static-selector">
                    <label for="widget-virus_weather_widget-country"><?php echo __('Country'); ?></label>
                    <input class='virus-weather-country' id='widget-virus_weather_widget-country' placeholder="Search" autocomplete="off" type="text" value="" />
                </p>

                <p class='virus-weather-state virus-weather-static-selector' style='display: none'>
                    <label for="widget-virus_weather_widget-state"><?php _e('Area', 'text_domain'); ?></label>
                    <select id='widget-virus_weather_widget-state'></select>
                </p>

                <p class='virus-weather-county virus-weather-static-selector' style='display: none'>
                    <label for="widget-virus_weather_widget-county"><?php _e('District', 'text_domain'); ?></label>
                    <select id='widget-virus_weather_widget-county'></select>
                </p>

                <p class="virus-weather-selected-location"></p>   
            </div>
            <div class='vw-option-middle-or'><?php echo __('OR'); ?></div>
            <h2 class="selectHeader22 geolocation"><?php echo __('Auto Geolocation'); echo '<br>'; ?></h2>	
            <div class="vw-option-select widget-content widget dynamic_box">
                <label class='vw-label' for="vw-auto-location">
                    <input type="radio" name="static-widget-type" value="dynamic" checked class="radio-check-dynamic" id="vw-auto-location"/>
                    <img class="radioImage" src="<?php echo plugin_dir_url(__FILE__) ?>../admin/images/ico_dynamic_2x_g.png" title="Static <?php echo __('Infection Rank Widgets'); ?>">  
                </label>  
            </div>            
            <div class="vw-option-select dynamic_box_text">                        		
                <label class='vw-label' for="vw-auto-location">
                    <p><?php echo __('The dynamic widget identifies a website visitor’s IP address, and displays the nearest available regional information. As an example, if a website visitor is from Italy, the closest Italian regional statistics will be displayed.') ?></p>
                </label>                
            </div>
        </div>

        <div class="vw-option-wrap">

            <h2 class="selectHeader"><?php echo __('Background'); ?></h2>

            <div class="vw-option-select">                
                <label class='vw-label' for="vw-light-background">
                    <div class='vw-label-fullwidth'>
                        <input type="radio" name="static-widget-color" value="light" checked class="radio-check-light" id="vw-light-background" />                    
                    </div>
                    <div class='vw-label-fullwidth'>
                        <h2 class="selectHeader2 titleSelect"><?php echo __('Light'); ?></h2>
                    </div>
                    <div class='vw-label-fullwidth'>
                        <img class="radioImage" src="<?php echo plugin_dir_url(__FILE__) ?>../admin/images/ico_light_2x.png" title="<?php echo __('Infection Rank Widgets'); ?>">
                    </div>
                </label>
            </div>

            <div class="vw-option-select">                	        		
                <label class='vw-label' for="vw-dark-background">
                    <div class='vw-label-fullwidth'>
                        <input type="radio" name="static-widget-color" value="dark" class="radio-check-dark" id="vw-dark-background" />                    		                
                    </div>
                    <div class='vw-label-fullwidth'>
                        <h2 class="selectHeader2 titleSelect"><?php echo __('Dark'); ?></h2>
                    </div>
                    <div class='vw-label-fullwidth'>
                        <img class="radioImage" src="<?php echo plugin_dir_url(__FILE__) ?>../admin/images/ico_dark_2x.png" title="<?php echo __('Infection Rank Widgets'); ?>">
                    </div>
                </label>
            </div>
        </div>


        <div class="vw-option-wrap vw-option-type">

            <h2 class="selectHeader"><?php echo __('Type'); ?></h2>

            <div class="vw-option-select">
                <input type="radio" name="static-widget-size" value="basic1004x1350" checked class="radio-check-static-size350" id="vw-rcs300"/>
                <label class='vw-label' for="vw-rcs300">                    
                    <h2 class="selectHeader2"><?php echo __('BASIC');?></h2>
                    <img class="radioImage" id="vw-image-w300" data-basic-src="<?php echo plugin_dir_url(__FILE__) ?>../admin/images/" src="<?php echo plugin_dir_url(__FILE__) ?>../admin/images/ico_size_300x400_g.png" title="<?php echo __('Phone Widget'); ?>" >
                </label>
            </div>
            <div class="vw-option-select">
                <input type="radio" name="static-widget-size" value="advanced1004x1350"  class="radio-check-static-size350" id="vw-rcs300a"/> 
                <label class='vw-label' for="vw-rcs300a">                    
                    <h2 class="selectHeader2"><?php echo __('ADVANCED');?></h2>
                    <img class="radioImage" id="vw-image-w300a" data-basic-src="<?php echo plugin_dir_url(__FILE__) ?>../admin/images/" src="<?php echo plugin_dir_url(__FILE__) ?>../admin/images/ico_size_300x400_g.png" title="<?php echo __('Phone Widget'); ?>" >
                </label>
            </div>
            <div class="vw-option-select">
                <input type="radio" name="static-widget-size" value="1000"  class="radio-check-static-size250" id="vw-rcs250"/>
                <label class='vw-label' for="vw-rcs250">
                    <h2 class="selectHeader2"> <?php echo __('SQUARE');?></h2>
                    <img class="radioImage" id="vw-image-w250" data-basic-src="<?php echo plugin_dir_url(__FILE__) ?>../admin/images/" src="<?php echo plugin_dir_url(__FILE__) ?>../admin/images/ico_size_250x250_g.png" title="<?php echo __('Phone Widget'); ?>" >
                </label>
            </div>
            <div class="vw-option-select">
                <input type="radio" name="static-widget-size" value="1465x180"  class="radio-check-static-size728" id="vw-rcs728"/>
                <label class='vw-label' for="vw-rcs728">
                    <h2 class="selectHeader2"> <?php echo __('HORIZONTAL BANNER'); ?></h2>
                    <img class="radioImage horizontalBanner" id="vw-image-w728" data-basic-src="<?php echo plugin_dir_url(__FILE__) ?>../admin/images/" src="<?php echo plugin_dir_url(__FILE__) ?>../admin/images/ico_size_728x90_g.png" title="<?php echo __('Phone Widget'); ?>" >
                </label>
            </div>	        		        	

        </div>

        <div class="vw-option-wrap vw-option-code-type">

            <h2 class="selectHeader"><?php echo __('Select HTML or Wordpress Shortcode Code'); ?></h2>
            <br>
            <div class="vw-option-select">
                <label class='vw-label' for="vw-html-code">
                    <input type="radio" name="static-widget-html-wp-code" value="htmlType" checked class="radio-check-light" id="vw-html-code" />                    
                    <div class='vw-label-fullwidth'>
                        <img class="radioImage" src="<?php echo plugin_dir_url(__FILE__) ?>../admin/images/ico_html.png" title="<?php echo __('Infection Rank Widgets'); ?>">
                        <h2 class="selectHeader2"><?php echo __('HTML'); ?></h2>
                    </div>
                </label>
            </div>

            <div class="vw-option-select">

                <label class='vw-label' for="vw-wordpress-code">
                    <input type="radio" name="static-widget-html-wp-code" value="wordpressType" class="radio-check-dark" id="vw-wordpress-code" />                    	        		
                    <div class='vw-label-fullwidth'>
                        <img class="radioImage" src="<?php echo plugin_dir_url(__FILE__) ?>../admin/images/ico_wp.png" title="<?php echo __('Infection Rank Widgets'); ?>">		                
                        <h2 class="selectHeader2"><?php echo __('WordPress'); ?></h2>
                    </div>
                </label>
            </div>

            <div id="codeTypeInfo"></div>
        </div>

        <div class="vw-option-wrap vw-option-buttons generateCode">

            <input type="button" onclick="return virus_weather_generate_widget_code()" name="generate-code" id="submit" class="generate_button button button-primary" value="Live Preview & Get Code">

            <div class="vw-generated-code dynamic_static_widget_get_code">

                <div id="previewImg"></div>
                <br>
                <label class="text-bold vw-description-note"><?php echo __('Copy and paste following code into the HTML of your website'); ?></label>
                <textarea readonly id="dynamic_static_cource_code" class="source-code"><a href="https://infectionrank.org/" title="Infection Rank"><img alt="<?php echo __('Public health ratings app and widgets'); ?>" width="250" height="250" src="https://infectionrank.org/widgets/en/vw-app-light-1004x1350.png" /></a></textarea>
        	                
        	                <div class="text-center">
        	                    <div class="tooltip">
        	                        <button class="copy_button generate_button button button-default" type="button"  onclick="virus_weather_copy_widget_source('dynamic_static_cource_code')" >
        	                            <span class="tooltiptext" id="virus-weather-tooltip"><?php echo __('Copy to clipboard'); ?></span>
                                            <?php echo __('Copy HTML Code'); ?>
        	                        </button>
        	                    </div>
        	                </div>
        	    </div>
        </div>      
        </div>      
        
        <div id="ads" class="float-left">
            <div style="background-color: white;padding-bottom: 15px;width: inherit;position: fixed;" class="ads-hide2 text-center">
                <h3 style="text-transform: uppercase; display: inline-block;margin-bottom: 10px; width: 100%;margin-top: 21px;text-align: center;" class="text-center">PREVIEW</h3>
                <div id="viewBlock" style="padding: 10px;"><img class="iphoneImage" src="https://infectionrank.org/widgets/en/vw-app-light-1004x1350.png"></div>
            </div>
            <br>
        </div>
        
        
        
    </section> 

    <?php
}

function virus_weather_register_widget() {
    register_widget('Virus_Weather_Widget');
}

function virus_weather_register_shortcode() {
    add_shortcode('virusweather', 'virus_weather_shortcode');
}

function virus_weather_shortcode($atts) {
    $defaults = [
        'title' => 'VirusWeather Covid-19 Local Threat Level Widget',
        'size' => 250,
        'theme' => 'light',
        'layout' => 'square',
        'country' => '',
        'state' => '',
        'county' => '',
        'area' => '',
        'district' => '',
    ];

    $params = shortcode_atts($defaults, $atts);
    $state = $state ? $state : $area;
    $county = $county ? $county : $district;

    $countryData = $params['country'];
    $stateData = $params['state'] ? $params['state'] : $params['area'];
    $countyData = $params['county'] ? $params['county'] : $params['district'];

        if (!$countryData){
            $linkurl = DYNAMIC_LINK;
        } else{
            $linkurl = STATIC_LINK.
            ( $countryData ? strtolower(str_replace(' ', '-', $countryData)) : '' ) .
            ( $stateData ? '/' . strtolower(str_replace(' ', '-', $stateData)) : '' ) .
            ( $countyData ? '/' . strtolower(str_replace(' ', '-', $countyData)) : '' ).
            '/latest-stats/';
        }

    return '<a href="' . $linkurl . '" target="_blank" title="' . $title . '">' . virus_weather_get_widget_image($params) . '</a>';
}

function virus_weather_get_layout_sizes($layout = '') {
    $sizes = [
        'square' => [ 1000, 1000],
        'horizontal' => [ 1465, 180],
        'casesapp' => [ 1004, 1350],
        'casesapp-advanced' => [ 1004, 1350],
    ];

    return $layout && isset($sizes[$layout]) ? $sizes[$layout] : $sizes;
}

function virus_weather_get_widget_image($settings) {
    $defaults = [
        'size' => 250,
        'theme' => 'light',
        'layout' => 'square'
    ];

    extract(wp_parse_args((array) $settings, $defaults));
    $sizes = virus_weather_get_layout_sizes($layout);
    $widgetWidth = /* $sizes[0] == 1465 ? 1456 : */ $sizes[0];
    $dimensions = $sizes[0] == $sizes[1] ? $sizes[0] : implode('x', $sizes);
    $height = $size;
    $width = floor($height * $widgetWidth / $sizes[1]);

    $state = $state ? $state : $area;
    $county = $county ? $county : $district;

    $bCases = $layout == 'casesapp' || $layout == 'casesapp-advanced';

    return '<img alt="covid-19 widget" style="max-width: 100%; height: auto" width="' . (int) $width . '" height="' . (int) $height . '" src="' .
            ( $bCases ? 'https://infectionrank.org/' : 'https://www.markosweb.com/' ) . ( $country ? 'coronavirus' : 'widgets' ) . '/en' .
            ( $country ? '/' . strtolower(str_replace(' ', '-', $country)) : '' ) .
            ( $state ? '/' . strtolower(str_replace(' ', '-', $state)) : '' ) .
            ( $county ? '/' . strtolower(str_replace(' ', '-', $county)) : '' ) .
            ( $bCases ? '/vw-app-' : '/vw-covid-19-' ) . $theme . '-' . $dimensions . '.png" />';
}

function virus_weather_get_widget_shortcode($settings) {
    $defaults = [
        'size' => 250,
        'theme' => 'light',
        'layout' => 'square'
    ];

    extract(wp_parse_args((array) $settings, $defaults));
    $stateParam = 'area';
    $countyParam = 'district';
    if ($country == 'United States') {
        $stateParam = 'state';
        $countyParam = 'county';
    }
    $state = $state ? $state : $area;
    $county = $county ? $county : $district;
    return '[virusweather' .
            ( $theme == 'light' ? '' : ' theme="' . $theme . '"' ) .
            ( $layout == 'square' ? '' : ' layout="' . $layout . '"' ) .
            ( $country ? ' country="' . $country . '"' : '' ) .
            ( $country && $state ? ' ' . $stateParam . '="' . $state . '"' : '' ) .
            ( $country && $state && $county ? ' ' . $countyParam . '="' . $county . '"' : '' ) .
            ( ( $layout == 'square' && $size == 250 ) || ( $layout == 'horizontal' && $size == 90 ) ? '' : ' size="' . $size . '"' ) .
            ']';
}

add_action('widgets_init', 'virus_weather_register_widget');
add_action('widgets_init', 'virus_weather_register_shortcode');
