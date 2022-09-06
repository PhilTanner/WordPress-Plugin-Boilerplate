<?php
namespace Plugin_Name;
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
   * The user entered options of this plugin.
   *
   * @since    2.0.3
   * @access   protected
   * @var      array    $options    A named array of options & values entered in the admin page.
   */
  protected $options;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   */
  public function __construct( ) {

    $this->options   = array_merge(
      // Repeat this next line for all option values you might use
      (array)get_site_option( 'plugin-name-options', array() ),
      array(),
    );

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

    wp_enqueue_style( 'plugin-name-admin', plugin_dir_url( __FILE__ ) . 'css/plugin-name-admin.css', array(), PLUGIN_NAME_VERSION, 'all' );

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

    wp_enqueue_script( 'plugin-name-admin', plugin_dir_url( __FILE__ ) . 'js/plugin-name-admin.js', array( 'jquery' ), PLUGIN_NAME_VERSION, false );

  }

  /**
   * Display any error messages we've registered in our plugin on any admin page.
   *
   * See https://codex.wordpress.org/Plugin_API/Action_Reference/admin_notices
   * and https://developer.wordpress.org/reference/hooks/admin_notices/
   *
   * @since    2.0.0
   */
  public function admin_notices() {
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
      // Display any error messages we've registered trying to update admin settings.
      settings_errors( PLUGIN_NAME_TEXT_DOMAIN );
    }

  }

  /**
   * Display descriptive content at the top of each settings section.
   *
   * @since    2.0.0
   */
  static function print_section_info( $args ) {
    global $plugin_name_plugin_data;

    switch( $args['id'] ){
      case 'plugin-name-options_section':
        echo '<p>'. sprintf( __('General settings for the %s plugin.', PLUGIN_NAME_TEXT_DOMAIN ), $plugin_name_plugin_data['PluginName'] ).'</p>';
        break;
    }
  }

  /**
   * Creates the dashboard menu item links.
   *
   * @since    2.0.2
   */
  public function add_menu_links() {
    // This will be our own Admin menu item
    $hook = add_menu_page(
      __("Plugin Settings", PLUGIN_NAME_TEXT_DOMAIN), // Tab title text
      __("Plugin Settings", PLUGIN_NAME_TEXT_DOMAIN),  // Menu item text
      "manage_options", // Capability required to see link/access page (Admin: https://wordpress.org/support/article/roles-and-capabilities/#administrator)
      'plugin-name-menu_admin', // Menu slug (unique name)
      array( $this, "admin_page_display" ), // Function to be called when displaying content
      'dashicons-admin-settings', // https://developer.wordpress.org/resource/dashicons/#admin-settings
      72 // https://developer.wordpress.org/reference/functions/add_menu_page/#default-bottom-of-menu-structure
    );

    /*
    // Add Submenu pages
    if( $hook ){
      add_submenu_page(
        'plugin-name-menu_admin', // Parent menu slug (above)
        __('Other settings', PLUGIN_NAME_TEXT_DOMAIN // Tab title
        __('Other settings', PLUGIN_NAME_TEXT_DOMAIN), // Menu title
        'manage_categories', // Capability needed to access (Editor)
        'plugin-name-sub-settings-menu-1', // Menu slug
        array( $this, 'admin_page_display_other' ), // Callback for output of the page
        null // Optional position in menu - default is to output in the order they're defined
      );
    }
    */
  }

  /**
   * Settings page display callback for main options, called by menu item.
   *
   * @since    2.0.2
   */
  public function admin_page_display() {
    global $plugin_name_plugin_data;

    require_once 'partials/plugin-name-admin-display.php';
  }

  /**
   * Create the WordPress options settings for our plugin.
   *
   * Values will be saved into the wp_options table with the $wp_options_name
   * key.
   * This function needs to have been registered with an admin_init action.
   *
   * @since    2.0.2
   */
  public function register_options_input_fields() {
    global $plugin_name_plugin_data;

    $wp_options_name = 'plugin-name-options';

    /**
     * We're creating a setting group to syntactically tie them all together.
     */
    register_setting(
      $wp_options_name.'_group',
      $wp_options_name,
      array(
        'type'        => 'string',
        'description' => sprintf( __( 'Settings for the %s plugin.', PLUGIN_NAME_TEXT_DOMAIN ), $plugin_name_plugin_data['PluginName'] ),
      )
    );

    add_settings_section(
      $wp_options_name.'_section',
      sprintf( __('Administrator settings for the %s plugin',PLUGIN_NAME_TEXT_DOMAIN), $plugin_name_plugin_data['PluginName'] ),
      array( $this, 'print_section_info' ), // Print out a description at the top of the page, under the h1.
      $wp_options_name.'_fields'
    );

    // Example fields:
    add_settings_field(
      'number_value', // Field name that we'll use to access this value.
      __('Enter a number between 1 and 10 (inclusive)',PLUGIN_NAME_TEXT_DOMAIN),
      array( $this, 'print_number_input' ), // What function will output the input field. These are stored in the Phil_Tanner_Admin() class, and include print_checkbox_input(), print_text_input(), print_textarea_input(), print_color_input(), print_number_input(), print_url_input(), print_select_input(), print_radio_inputs(), print_email_input()
      $wp_options_name.'_fields', // Which bit to output into when you call do_settings_sections() function.
      $wp_options_name.'_section', // THe section above we want to display in.
      array(
        'name'              => 'number_value', // Should match the first arg of this function.
        'label_for'         => 'number_value', // Should also match the first arg of this function - including this makes it output a label.
        'description'       => __('An optional argument containing a bit more information to display under the input box.', PLUGIN_NAME_TEXT_DOMAIN),
        'option-name'       => $wp_options_name, // Where we're going to store the data.
        'sanitize_callback' => 'intval', // How do we escape user data entered here? Other options include floatval and those at https://developer.wordpress.org/plugins/security/securing-input/
        'min'               => 1,
        'max'               => 10,
        'step'              => 0.0000001,
        // Any other arguments you want to pass to the input field HTML go here.
      )
    );
    add_settings_field(
      'url_value',
      __('Favourite website?',PLUGIN_NAME_TEXT_DOMAIN),
      array( $this, 'print_url_input' ),
      $wp_options_name.'_fields',
      $wp_options_name.'_section',
      array(
        'name'              => 'url_value',
        'label_for'         => 'url_value',
        'option-name'       => $wp_options_name,
        'sanitize_callback' => 'sanitize_url',
        'required'          => 'required',
        'placeholder'       => __('https://google.com/',PLUGIN_NAME_TEXT_DOMAIN),
      )
    );
    add_settings_field(
      'select_field',
      __('Pick a direction',PLUGIN_NAME_TEXT_DOMAIN),
      array( $this, 'print_select_input' ),
      $wp_options_name.'_fields',
      $wp_options_name.'_section',
      array(
        'name'              => 'select_field',
        'label_for'         => 'select_field',
        'option-name'       => $wp_options_name,
        'sanitize_callback' => 'sanitize_text_field',
        'options'           => array(
                                array( 'value' => 'north', 'name' => __('North', PLUGIN_NAME_TEXT_DOMAIN) ),
                                array( 'value' => 'east',  'name' => __('East',  PLUGIN_NAME_TEXT_DOMAIN) ),
                                array( 'value' => 'south', 'name' => __('South', PLUGIN_NAME_TEXT_DOMAIN) ),
                                array( 'value' => 'west',  'name' => __('West',  PLUGIN_NAME_TEXT_DOMAIN) ),
                              ),
        'multiple'          => 'multiple', // include this to pick more than one select item.
      )
    );

    add_settings_field(
      'radio_field',
      __('Pick a direction',PLUGIN_NAME_TEXT_DOMAIN),
      array( $this, 'print_radio_inputs' ), // Note pluralisation
      $wp_options_name.'_fields',
      $wp_options_name.'_section',
      array(
        'name'              => 'radio_field',
        //'label_for'         => 'radio_field',
        'option-name'       => $wp_options_name,
        'sanitize_callback' => 'sanitize_text_field',
        'options'           => array(
                                array( 'value' => 'north', 'name' => __('North', PLUGIN_NAME_TEXT_DOMAIN) ),
                                array( 'value' => 'east',  'name' => __('East',  PLUGIN_NAME_TEXT_DOMAIN) ),
                                array( 'value' => 'south', 'name' => __('South', PLUGIN_NAME_TEXT_DOMAIN) ),
                                array( 'value' => 'west',  'name' => __('West',  PLUGIN_NAME_TEXT_DOMAIN) ),
                              ),
        'required'          => 'required',
      )
    );
  }


  /**
   * Add some additional links underneath our plugin description,
   * linking back to the editor page
   *
   * @since   2.0.0
   * @access  public
   * @param   array       $links_array            An array of the plugin's metadata
   * @param   string      $plugin_file_name       Path to the plugin file
   * @param   array       $plugin_data            An array of plugin data
   * @param   string      $status                 Status of the plugin
   * @return  array       $links_array
   */
  public function plugin_name_links( $links_array, $plugin_file_name, $plugin_data, $status ){

    if( PLUGIN_NAME_TEXT_DOMAIN.".php" === basename($plugin_file_name) ){
      // Tack our Github branch onto the Version number (only works when plugin is activated, obviously)
      $git_branch = Phil_Tanner_Admin::get_git_branch();
      $git_repo   = Phil_Tanner_Admin::get_git_repo_url();
      $git_hash   = Phil_Tanner_Admin::get_git_commit_hash();
      $git_date   = Phil_Tanner_Admin::get_git_commit_date();

      if(
        $git_branch
        && $git_repo
        && $git_hash
        && $git_date
      ){
        $links_array[0] .= ' ('.sprintf(
          __(
            'Git Branch: '.
            '<a href="%s" target="_blank">%s</a> '.
            '(Commit: <a href="%s" target="_blank">#%s, %s</a>)',
            PLUGIN_NAME_TEXT_DOMAIN
          ),
          $git_repo.'/tree/'.$git_branch,
          $git_branch,
          $git_repo.'/commit/'.$git_hash,
          substr( $git_hash, 0, 8),
          Phil_Tanner_Admin::print_wp_local_date_from( $git_date, get_option( 'date_format' ) )
        ) .')';
      }
    }

    return $links_array;
  }


  /**
   * Add a Settings link to the "plugin deactivate" area.
   *
   * @since   2.0.0
   * @access  public
   * @param   array       $links_array            An array of the plugin's metadata
   * @param   string      $plugin_file_name       Path to the plugin file
   * @param   array       $plugin_data            An array of plugin data
   * @param   string      $status                 Status of the plugin
   * @return  array       $links_array
  */
  function plugin_name_action_links( $links_array, $plugin_file_name, $plugin_data, $status ){
    if( PLUGIN_NAME_TEXT_DOMAIN.".php" === basename($plugin_file_name) ){
      $settings_url = esc_url(
        add_query_arg(
          'page',
          'plugin-name-menu_admin', // Note - this needs to match the Menu Slug in admin/class-plugin-name-admin.php->add_menu_links()
          admin_url( 'admin.php' )
        )
      );
      // Using array_unshift to put it first in the array (before Deactivate) (unlike plugin_name_links above )
      array_unshift( $links_array, '<a href="'.$settings_url.'">'.__("Settings", PLUGIN_NAME_TEXT_DOMAIN).'</a>' );
    }
    return $links_array;
  }

}
