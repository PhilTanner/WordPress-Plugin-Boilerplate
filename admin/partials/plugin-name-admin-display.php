<?php
namespace Plugin_Name;

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin/partials
 */

  echo '<div class="wrap plugin_name">';
  echo '  <h1>' . $plugin_name_plugin_data['PluginName'] . '</h1>';
  echo '  <h2>';
  echo sprintf(
   __("Version %s", PLUGIN_NAME_TEXT_DOMAIN),
   get_option('plugin-name-activated-version', $plugin_name_plugin_data['Version'])
  );
  echo '</h2>';

  $git_branch = Phil_Tanner_Admin::get_git_branch();
  $git_repo   = Phil_Tanner_Admin::get_git_repo_url();
  $git_hash   = Phil_Tanner_Admin::get_git_commit_hash();
  $git_date   = Phil_Tanner_Admin::get_git_commit_date();

  echo ' <dl>';
  if(
   $git_branch
   && $git_repo
   && $git_hash
   && $git_date
  ){
   echo sprintf(
     __(
       '<dt>Git Branch:</dt>'.
       '<dd><a href="%s" target="_blank">%s</a></dd>'.
       '<dt>Commit:</dt>'.
       '<dd><a href="%s" target="_blank">#%s</a></dd>'.
       '<dt>Commit date:</dt>'.
       '<dd>%s</dd>',
       PLUGIN_NAME_TEXT_DOMAIN
     ),
     $git_repo.'/tree/'.$git_branch,
     $git_branch,
     $git_repo.'/commit/'.$git_hash,
     substr( $git_hash, 0, 8),
     Phil_Tanner_Admin::print_wp_local_date_from( $git_date )
   );
  }

  echo sprintf(
    __(
      '<dt>WordPress Environment</dt>'.
      '<dd>%s</dd>',
      PLUGIN_NAME_TEXT_DOMAIN
    ),
    (
      defined( "WP_ENVIRONMENT_TYPE" ) ?
      WP_ENVIRONMENT_TYPE :
      '<em>'.__(
        'Undefined',
        PLUGIN_NAME_TEXT_DOMAIN
      ).'</em>' 
    )
  );
  echo "</dl>";

  if( current_user_can( "manage_options" ) ) {
   echo '  <form method="post" action="options.php" id="plugin_name_admin_settings">';
   settings_fields( 'plugin-name-options_group' );
   do_settings_sections( 'plugin-name-options_fields' );
   submit_button();
   echo '  </form>';
  } else {
   echo '<p>'.__("You must be an administrator to edit these settings.", PLUGIN_NAME_TEXT_DOMAIN).'</p>';
  }

  echo '</div>';
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
