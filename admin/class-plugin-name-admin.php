<?php
namespace PluginName;
require_once 'class-phil-tanner-admin.php';

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 * @author     Your Name <email@example.com>
 */
class Plugin_Name_Admin extends Phil_Tanner_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
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
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/plugin-name-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		 // Took the advice from https://stackoverflow.com/a/7317311 to not reinvent the wheel.
 		wp_enqueue_script(
 			'jquery-dirty',
 			plugin_dir_url( __FILE__ ) . 'js/jquery.dirty.js',
 			array(
 				'jquery'
 			),
 			"0.8.3",
 			false
 		);

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/plugin-name-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Display any error messages we've registered in our plugin on any admin page.
	 *
	 * See https://codex.wordpress.org/Plugin_API/Action_Reference/admin_notices
 	 * and https://developer.wordpress.org/reference/hooks/admin_notices/
   *
   * @since    2.0.0
	 */
	public function admin_notices(){
		global $plugin_name_plugin_data;

		// These notices will be seen by website admins.
		if( current_user_can( "manage_options" ) ) {
			// WordFence security plugin is excellent. We need ot make sure we've
			// got it installed and activated - and if not, we'll throw a message to
			// everyone alerting them to this fact.

			// But we don't really care about t on the development aspects as they're localhost.
			if( WP_ENVIRONMENT_TYPE != "local" ){
				if ( // Multiple ways of checking
					get_option('wordfenceActivated', 0) != 1  // This is how WF checks i it's activated
					&& !defined('WORDFENCE_CENTRAL_PUBLIC_KEY') // This is a constant set by their plugin
					&& !is_plugin_active( 'wordfence/wordfence.php' ) // final check - because we might've not installed it here
				) {
					add_settings_error(
						PLUGIN_NAME_TEXT_DOMAIN, // Slug of the setting which we're generating the error for
						'wordfence_not_detected', // slug name of the code of this particular error
						$plugin_name_plugin_data['PluginName'] .": ". sprintf(
							__('The WordFence plugin does not appear to be active! This is a significant security risk.<br />Please <a href="%s" target="_blank">download</a> it (or <a href="'.esc_url(admin_url('plugins.php')).'">activate</a> it) immediately.',PLUGIN_NAME_TEXT_DOMAIN),
							'https://en-nz.wordpress.org/plugins/wordfence/'
						),
						//json_encode( $_POST ),
						'error' // error, success, warning, or info. Default error.
					);
				}
			}
		}

		// Display any error messages we've registered trying to update admin settings.
		settings_errors( PLUGIN_NAME_TEXT_DOMAIN );
	}

	/**
	 * Display descriptive content at the top of each settings section.
   *
   * @since    2.0.0
	 */
	static function print_section_info( $args ){
		switch( $args['id'] ){
			case 'plugin-name-options-section':
        echo '<p>'. __('General settings for the Plugin Name plugin.', PLUGIN_NAME_TEXT_DOMAIN ).'</p>';
        break;
    }
	}

}
