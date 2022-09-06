<?php
namespace Plugin_Name;

/**
 * Standardised admin functions for plugin development.
 *
 * Defines a load of functions that will not be specific to any singular plugin
 * but reused across any plugin developed (such as admin setting field inputs,
 * or printing date/times according to WP preferences etc.).
 *
 * It is intended that the plugin admin class extends this one to inherit all
 * these properties into itself.
 *
 * @author     Phil Tanner <phil.tanner@gmail.com>
 */
class Phil_Tanner_Admin {

  /**
   * The version of this class.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version = "1.1.0";


  /**
   * Returns an escaped key value pair for HTML formfield elements for use
   * in the output of the below print_X_input functions. Used so that we
   * can pass in custom style, or step, or required, or placeholder text,
   * or whatever else we might want.
   *
   * @since    1.0.0
   */
  public function get_additional_args_for_inputs( $args ){
    // This is the name as set by register_setting()->option_name
    if( !isset( $args['option-name'] ) ){
      $error = new \WP_Error( 'admin', __('Missing required "option-name" argument required for saving info.', 'Phil_Tanner_Admin' ));
      throw new \Exception(__('Missing required "option-name" argument required for saving info.','Phil_Tanner_Admin'));
    }
    if( !isset( $args['name'] ) ){
      $error = new \WP_Error( 'admin', __('Missing required "name" argument required for saving info.', 'Phil_Tanner_Admin' ));
      throw new \Exception(__('Missing required "name" argument required for saving info.','Phil_Tanner_Admin'));
    }
    // We're always going to include the ID & name attributes
    $additionalargs = ' id="'.sanitize_key($args['name']).'"'; // Note this sanitisation needs to match that in print_radio_inputs()
    $is_multiple = false;
    foreach( $args as $name => $val ){
      switch( $name ){
        // Ignore these fields
        case "description": // Description is special & output as a <P> below.
        case "value": // We're going to correctly sanitise our values in the functions
        case "type": // We're going to correctly output the type in the functions
        case "name": // We've handled the name above
        case "id": // We've handled the ID above
        case 'option-name': // part of the name above
        case "sanitize_callback": // this is how we'll clean the data
        case "default": // we'll handle that on output
        case "options": // used for selects etc
          break;
        // These fields are boolean values, so don't need a X=Y setup
        case "required":
        case "autofocus":
        case "disabled":
        case "readonly":
        case "autofocus":
          $additionalargs .= ' '.esc_attr($name);
          break;
        case "multiple": // Multiple selects need a different argument name format...
          $additionalargs .= ' '.esc_attr($name);
          $is_multiple = true;
          break;
        // These are numerical arguments.
        case "step":
        //case "min": // These can also be dates.
        //case "max": // These can also be dates.
          $additionalargs .= ' '.esc_attr($name).'="'.(float)$val.'"';
          break;
        // These are integer arguments.
        case "size":
        case "maxlength":
          $additionalargs .= ' '.esc_attr($name).'="'.(int)$val.'"';
          break;
        // These are URLs, so they need a different value escape.
        case "src":
          $additionalargs .= ' '.esc_attr($name).'="'.esc_attr(esc_url($val)).'"';
          break;
        // Just treat it as a string
        default:
          $additionalargs .= ' '.esc_attr($name).'="'.esc_attr($val).'"';
      }

    }
    $additionalargs .= ' name="'.sanitize_key($args['option-name']).'['.sanitize_key($args['name']).']'.($is_multiple?'[]':'').'"';
    return $additionalargs;
  }

  /**
   * Outputs the description argument under form fields output.
   *
   * @since    1.1.0
   */
  public function print_input_description( $args ){
    if(
      array_key_exists( 'description', $args )
      && trim($args['description'])
    ){
      echo sprintf(
        '<p class="description" id="%s-description">%s</p>',
        sanitize_html_class($args['name']),
        $args['description']
      );
    }
  }


  /**
   * Outputs a standardised WP admin formfield for entry of telephone numbers.
   * Used in argument #3 of add_settings_field().
   *
   * @since    1.1.0
   */
  public function print_tel_input( $args ){
    echo sprintf(
      '<input type="tel" value="%s" %s />',
      (isset( $this->options[sanitize_key($args['name'])] ) ? esc_attr( $this->options[sanitize_key($args['name'])]) : ''),
      $this->get_additional_args_for_inputs( $args ),
    );
    $this->print_input_description( $args );
  }


  /**
   * Outputs a standardised WP admin formfield for hidden form field. Cannot imagine a use case, but included for completeness.
   * Used in argument #3 of add_settings_field().
   *
   * @since    1.1.0
   */
  public function print_hidden_input( $args ){
    echo sprintf(
      '<input type="hidden" value="%s" %s />',
      (isset( $this->options[sanitize_key($args['name'])] ) ? esc_attr( $this->options[sanitize_key($args['name'])]) : ''),
      $this->get_additional_args_for_inputs( $args ),
    );
    $this->print_input_description( $args );
  }


  /**
   * Outputs a standardised WP admin formfield for entry of boolean values.
   * Used in argument #3 of add_settings_field().
   *
   * @since    1.0.0
   */
  public function print_checkbox_input( $args ){
    echo sprintf(
      '<input type="checkbox" value="1" %s %s />',
      $this->get_additional_args_for_inputs( $args ),
      (isset( $this->options[sanitize_key($args['name'])] ) ? 'checked' : '')
    );
    $this->print_input_description( $args );
  }

  /**
   * Outputs a standardised WP admin formfield for entry of text strings.
   * Used in argument #3 of add_settings_field().
   *
   * @since    1.0.0
   */
  public function print_color_input( $args ){
    echo sprintf(
      '<input type="color" value="%s" %s />',
      (isset( $this->options[sanitize_key($args['name'])] ) ? esc_attr( $this->options[sanitize_key($args['name'])]) : ''),
      $this->get_additional_args_for_inputs( $args ),
    );
    $this->print_input_description( $args );
  }

  /**
   * Outputs a standardised WP admin formfield for entry of number values.
   * Used in argument #3 of add_settings_field().
   *
   * @since    1.0.0
   */
  public function print_number_input( $args ){
    echo sprintf(
      '<input type="number" value="%s" %s />',
      (isset( $this->options[sanitize_key($args['name'])] ) ? (float)$this->options[sanitize_key($args['name'])] : '0'),
      $this->get_additional_args_for_inputs( $args ),
    );
    $this->print_input_description( $args );
  }

  /**
   * Outputs a standardised WP admin formfield for entry of a slider value.
   * Used in argument #3 of add_settings_field().
   *
   * @since    1.1.0
   */
  public function print_range_input( $args ){
    echo sprintf(
      '<input type="range" value="%s" %s />',
      (isset( $this->options[sanitize_key($args['name'])] ) ? (float)$this->options[sanitize_key($args['name'])] : ''),
      $this->get_additional_args_for_inputs( $args ),
    );
    $this->print_input_description( $args );
  }

  /**
   * Outputs a standardised WP admin formfield for entry of URI/URL strings.
   * Used in argument #3 of add_settings_field().
   *
   * @since    1.0.0
   */
  public function print_url_input( $args ){
    echo sprintf(
      '<input type="url" value="%s" %s />',
      (isset( $this->options[sanitize_key($args['name'])] ) ? esc_attr( $this->options[sanitize_key($args['name'])]) : ''),
      $this->get_additional_args_for_inputs( $args ),
    );
    $this->print_input_description( $args );
  }

  /**
   * Outputs a standardised WP admin formfield for entry of email addresses.
   * Used in argument #3 of add_settings_field().
   *
   * @since    1.1.0
   */
  public function print_email_input( $args ){
    echo sprintf(
      '<input type="email" value="%s" %s />',
      (isset( $this->options[sanitize_key($args['name'])] ) ? esc_attr( $this->options[sanitize_key($args['name'])]) : ''),
      $this->get_additional_args_for_inputs( $args ),
    );
    $this->print_input_description( $args );
  }

  /**
   * Outputs a standardised WP admin formfield for entry of password fields.
   * Used in argument #3 of add_settings_field().
   *
   * @since    1.1.0
   */
  public function print_password_input( $args ){
    echo sprintf(
      '<input type="password" value="%s" %s />',
      (isset( $this->options[sanitize_key($args['name'])] ) ? esc_attr( $this->options[sanitize_key($args['name'])]) : ''),
      $this->get_additional_args_for_inputs( $args ),
    );
    $this->print_input_description( $args );
  }

  /**
   * Outputs a standardised WP admin formfield for entry of text strings.
   * Used in argument #3 of add_settings_field().
   *
   * @since    1.0.0
   */
  public function print_text_input( $args ){
    echo sprintf(
      '<input type="text" value="%s" %s />',
      (isset( $this->options[sanitize_key($args['name'])] ) ? esc_attr( $this->options[sanitize_key($args['name'])]) : ''),
      $this->get_additional_args_for_inputs( $args ),
    );
    $this->print_input_description( $args );
  }

  /**
   * Outputs a standardised WP admin formfield for entry of text strings.
   * Used in argument #3 of add_settings_field().
   *
   * @since    1.0.0
   */
  public function print_textarea_input( $args ){
    echo sprintf(
      '<textarea %s>%s</textarea>',
      $this->get_additional_args_for_inputs( $args ),
      (isset( $this->options[sanitize_key($args['name'])] ) ? esc_textarea( $this->options[sanitize_key($args['name'])]) : '')
    );
    $this->print_input_description( $args );
  }

  /**
   * Outputs a standardised WP admin formfield for entry of date values.
   * Used in argument #3 of add_settings_field().
   *
   * @since    1.1.0
   */
  public function print_date_input( $args ){
    echo sprintf(
      '<input type="date" value="%s" %s />',
      (isset( $this->options[sanitize_key($args['name'])] ) ? esc_attr( $this->options[sanitize_key($args['name'])]) : ''),
      $this->get_additional_args_for_inputs( $args ),
    );
    $this->print_input_description( $args );
  }

  /**
   * Outputs a standardised WP admin formfield for entry of date time entries (without timezones).
   * Used in argument #3 of add_settings_field().
   *
   * @since    1.1.0
   */
  public function print_datetime_input( $args ){
    echo sprintf(
      '<input type="datetime-local" value="%s" %s />',
      (isset( $this->options[sanitize_key($args['name'])] ) ? esc_attr( $this->options[sanitize_key($args['name'])]) : ''),
      $this->get_additional_args_for_inputs( $args ),
    );
    $this->print_input_description( $args );
  }

  /**
   * Outputs a standardised WP admin formfield for entry of month and year.
   * Used in argument #3 of add_settings_field().
   *
   * @since    1.1.0
   */
  public function print_month_and_year_input( $args ){
    echo sprintf(
      '<input type="month" value="%s" %s />',
      (isset( $this->options[sanitize_key($args['name'])] ) ? esc_attr( $this->options[sanitize_key($args['name'])]) : ''),
      $this->get_additional_args_for_inputs( $args ),
    );
    $this->print_input_description( $args );
  }

  /**
   * Outputs a standardised WP admin formfield for entry of a week and year.
   * Used in argument #3 of add_settings_field().
   *
   * @since    1.1.0
   */
  public function print_week_input( $args ){
    echo sprintf(
      '<input type="week" value="%s" %s />',
      (isset( $this->options[sanitize_key($args['name'])] ) ? esc_attr( $this->options[sanitize_key($args['name'])]) : ''),
      $this->get_additional_args_for_inputs( $args ),
    );
    $this->print_input_description( $args );
  }

  /**
   * Outputs a standardised WP admin formfield for entry using a select input.
   * Note: This requires an argument of "options" as an array of options to
   * output, each with a name and a value pair.
   * Used in argument #3 of add_settings_field().
   *
   * @since    1.0.0
   */
  public function print_select_input( $args ){
    echo sprintf(
      '<select %s>',
      $this->get_additional_args_for_inputs( $args ),
    );

    foreach( $args['options'] as $option ){
      echo sprintf(
        '<option value="%s" %s>%s</option>',
        esc_attr($option['value']),
        (
          isset( $this->options[sanitize_key($args['name'])] )
          && (
            (
              is_array( $this->options[sanitize_key($args['name'])] )
              && array_search( $option['value'], $this->options[sanitize_key($args['name'])] ) !== false
            )
            || $this->options[sanitize_key($args['name'])] == $option['value']
          ) ?
          'selected="selected"' :
          ''
        ),
        esc_html( $option['name'] )
      );
    }

    echo '</select>';
    $this->print_input_description( $args );
  }

  /**
   * Outputs a standardised WP admin formfield for entry using a set of radio
   * input buttons.
   * Note: This requires an argument of "options" as an array of options to
   * output, each with a name and a value pair.
   * Used in argument #3 of add_settings_field().
   *
   * @since    1.1.0
   */
  public function print_radio_inputs( $args ){

    $additionalargs = $this->get_additional_args_for_inputs( $args );
    // Each Radio button needs its own ID argument, and IDs need to be unique.
    // So we're going to strip it from the start of our arguments list so we can
    // add it back.
    $additionalargs = str_replace(' id="'.sanitize_key($args['name']).'"', '', $additionalargs);

    $n = 0; // throwaway counter to keep track of IDs
    foreach( $args['options'] as $option ){
      $n++;
      $id = sanitize_key($args['name']) . "-". $n; // This is the same way we're sanitising IDs in get_additional_args_for_inputs()
      echo sprintf(
        '<input type="radio" value="%s" %s id="'.$id.'" />',
        esc_attr($option['value']),
        (
          isset( $this->options[sanitize_key($args['name'])] )
          && $this->options[sanitize_key($args['name'])] == $option['value']
          ?
          'checked'.$additionalargs :
          $additionalargs
        )
      );
      echo sprintf(
        '<label for="'.$id.'">%s</label>',
        esc_html($option['name'])
      );
    }
    $this->print_input_description( $args );
  }


  /**
   * Register and add settings
   */
  public function register_admin_input_fields()
  {
    /*
    // These are the things you'll need to configure to register an admin panel
    // setting to output properly.
    // The various arguments are not well (or at least confusingly) documented
    // in official documentation.

    // This tells WP that we want to store the value of the entered settings into the DB
    register_setting( // https://developer.wordpress.org/reference/functions/register_setting/#parameters
      'plugin-name-options_group', // Needs to match settings_fields() used in page output
      'plugin-name-options', // Option name (saved as into in wp_options table)
      array(
        'type' => 'string',
        'description' => 'Settings for the Plugin Name plugin.',
      )
    );

    This allows us to group stuff together, but doesn't seem to do much functionally.
    add_settings_section(
      'plugin-name-options-section', // Section slug (ID)
      __('Plugin Name Options Settings',PLUGIN_NAME_TEXT_DOMAIN), // HTML page title
      array( $this, 'print_section_info' ), // Callback to output description
      'plugin-name-output-fields' // Slug used in do_settings_sections
    );

    // This registers a value for our users to enter their stuff into.
    add_settings_field(
      'custom_field_name_value', // field ID
      __('Pick a number',PLUGIN_NAME_TEXT_DOMAIN), // Field label
      array( $this, 'print_number_input' ), // Output function used to display output.
      'plugin-name-output-fields',// Slug used in do_settings_sections
      'plugin-name-output-fields-section', // ID
      array(
        'name'              => 'plugin-name-output-fields', // Should match field ID, saved as named array value in `plugin-name-options` value
        'description'       => __('Pick a number between 1 and 10 inclusive.', PLUGIN_NAME_TEXT_DOMAIN),
        'option-name'       => 'plugin-name-options', // Option name (saved as into in wp_options table)
        'sanitize_callback' => 'intval' // Sanitizing function to clean the input text,
        'min'               => 1,
        'max'               => 10,
      )
    );
    */

  }

  /**
   * As noted by https://developer.wordpress.org/reference/functions/wp_upload_dir/#comment-2576.
   * WP doesn't currently take note of the SSL part of upload URLs. Solve that
   *
   * @since     0.1.0
   */
  public function fix_upload_path_for_ssl( $param ){
    if ( is_ssl() ) {
      $param['url']     = str_replace( 'http://', 'https://', $param['url']     );
      $param['baseurl'] = str_replace( 'http://', 'https://', $param['baseurl'] );
    }
    return $param;
  }

  /**
   * Getter for version number
   *
   * @since     0.1.0
   */
  public function get_version() {
    return $this->version;
  }


  /**
   * Calculate which Git branch we're looking at.
   *
   * @since     1.0.1
   */
  // Taken from: https://stackoverflow.com/questions/7447472/how-could-i-display-the-current-git-branch-name-at-the-top-of-the-page-of-my-de
  public static function get_git_branch() {
    if( !file_exists( plugin_dir_path( __DIR__ ).DIRECTORY_SEPARATOR.'.git'.DIRECTORY_SEPARATOR.'HEAD' ) ) {
      return false;
    } else {
      return trim(substr(file_get_contents(plugin_dir_path( __DIR__ ).DIRECTORY_SEPARATOR.'.git'.DIRECTORY_SEPARATOR.'HEAD'), 16));
    }
  }

  /**
   * Calculate which Git repo we're looking at.
   *
   * @since     1.0.3
   */
  public static function get_git_repo_url() {
    if( !file_exists( plugin_dir_path( __DIR__ ).DIRECTORY_SEPARATOR.'.git'.DIRECTORY_SEPARATOR.'config' ) ) {
      return false;
    } else {
      preg_match_all('/url = (.*)/',file_get_contents(plugin_dir_path( __DIR__ ).DIRECTORY_SEPARATOR.'.git'.DIRECTORY_SEPARATOR.'config'),$m);
      if( count($m) < 2 ) {
        return false;
      }
      return substr( $m[1][0], 0, -4); // Trim the ".git" from the end.
    }
  }

  /**
   * Get the last commit date. Tweaked from https://stackoverflow.com/a/53205331
   *
   * @since     1.0.3
   */
  public static function get_git_commit_date() {
    $branch = self::get_git_branch();
    if( !file_exists( plugin_dir_path( __DIR__ ).DIRECTORY_SEPARATOR.'.git'.DIRECTORY_SEPARATOR.'refs'.DIRECTORY_SEPARATOR.'heads'.DIRECTORY_SEPARATOR.$branch ) ) {
      return false;
    } else {
      return date('c', filemtime(plugin_dir_path( __DIR__ ).DIRECTORY_SEPARATOR.'.git'.DIRECTORY_SEPARATOR.'refs'.DIRECTORY_SEPARATOR.'heads'.DIRECTORY_SEPARATOR.$branch));
    }
  }

  /**
   * Get the last commit hash. Tweaked from https://stackoverflow.com/a/53205331
   *
   * @since     1.0.3
   */
  public static function get_git_commit_hash() {
    $branch = self::get_git_branch();
    if( !file_exists( plugin_dir_path( __DIR__ ).DIRECTORY_SEPARATOR.'.git'.DIRECTORY_SEPARATOR.'refs'.DIRECTORY_SEPARATOR.'heads'.DIRECTORY_SEPARATOR.$branch ) ) {
      return false;
    } else {
      return file_get_contents(plugin_dir_path( __DIR__ ).DIRECTORY_SEPARATOR.'.git'.DIRECTORY_SEPARATOR.'refs'.DIRECTORY_SEPARATOR.'heads'.DIRECTORY_SEPARATOR.$branch);
    }
  }


  /**
   * Format a date/time into the site specific format - non-trivial to work out
   * how to achieve this, so extracted out into a specific function.
   *
   * @since    1.0.1
   */
  public static function print_wp_local_date_from( $datestr, $format=null ){

    // We're going to capture any errors to know there was a problem
    try{
      // Default the return format to datetime as set in the admin tool
      if( $format === null ){
        $format = get_option( 'date_format' ).' '.get_option('time_format');
      }
      // Create a date string from what we were given
      $date = new \DateTime( $datestr );
      if(!$date){ return $datestr; }
      // Convert the date into the timezone we're running this site in.
      $date->setTimezone( wp_timezone() );

      // Return a datetime string
      return date_i18n(
        $format, // Format we're returning it in
        $date->format('U') + $date->format('Z') // We want epoch time + offset in seconds.
      );

    } catch( Exception $e ){
      return $datestr;
    }

  }

}
