<?php
/*
 * Plugin Name: Ask The Expert Block
 * Description: Encourage conversation in your blog comments with the easy-to-use block.
 * Version: 1.1
 * Requires at least: 5.3
 * Requires PHP: 5.6
 * Tested up to: 5.7
 * Author: WPVitamins
 * Author URI: https://wpvitamins.com
 * Contributors: wpvitamins
 * License: GPLv2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ask-expert-block
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) || exit;

/**
 * Global settings. 
 */

global $ask_expert_block_default_field_values;

$ask_expert_block_default_field_values = array(
  'user_id'                    => '',
  'text'                       => '',
  'text_badge'                 => 'Ask me',
  'text_button'                => 'Ask a Question',
  'text_size'                  => '14',
  'color_background'           => '#E4EAF6',
  'color_text'                 => '#000000',
  'user_image_size'            => '75',
  'user_text_size_name'        => '16',
  'user_text_size_description' => '14',
  'user_color_name'            => '#000000',
  'user_color_description'     => '#7C6E6E',
  'badge_text_size'            => '12',
  'badge_color_background'     => '#FF3164',
  'badge_color_text'           => '#ffffff',
  'button_text_size'           => '16',
  'button_color_background'    => '#86B802',
  'button_color_text'          => '#ffffff',
);

/**
 * Load all translations for our plugin from the MO file.
 */

function ask_expert_block_load_textdomain() {
	load_plugin_textdomain( 'ask-expert-block', false, basename( __DIR__ ) . '/languages' );
}

/**
 * Init plugin.
 */

add_action( 'init', 'ask_expert_block_load_textdomain' );

function ask_expert_block_init_plugin () {

  /*** Registers Gutenberg block ***/
  
  // Check Gutenberg is enabled

	if ( !function_exists( 'register_block_type' ) ) {
		return;
  }
  
  // Register block

  wp_register_style(
		'ask-expert-block-editor',
		plugins_url( 'css/editor.css', __FILE__ ),
		array( 'wp-edit-blocks' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'css/editor.css' )
	);

	wp_register_style(
		'ask-expert-block',
		plugins_url( 'css/style.css', __FILE__ ),
		array( ),
		filemtime( plugin_dir_path( __FILE__ ) . 'css/style.css' )
  );
  
  wp_enqueue_style( 'ask-expert-block' );

	wp_register_script(
		'ask-expert-block',
		plugins_url( 'js/block.js', __FILE__ ),
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'wp-data', 'wp-components' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'js/block.js' )
	);

  register_block_type( 'ask-expert-block/ask-expert-block', array(
    'editor_script'   => 'ask-expert-block',
    'editor_style'    => 'ask-expert-block-editor',
    'render_callback' => 'ask_expert_block_render_callback'
	) );

  // Passes translations to JavaScript

  if ( function_exists( 'wp_set_script_translations' ) ) {
    wp_set_script_translations( 'ask-expert-block', 'ask-expert-block' );
  }
}

add_action( 'init', 'ask_expert_block_init_plugin' );

/**
 * Init assets.
 */

function ask_expert_block_init_assets() {

  wp_enqueue_script( 'ask-expert-block-script', plugins_url('js/script.js', __FILE__), array( 'jquery' ), null );
}

add_action( 'wp_enqueue_scripts', 'ask_expert_block_init_assets' );

function ask_expert_block_init_assets_admin() {

  wp_enqueue_style( 'wp-color-picker' );

  wp_enqueue_script( 'ask-expert-block-script', plugins_url('js/script-admin.js', __FILE__), array( 'jquery', 'wp-color-picker' ), null );
}

add_action( 'admin_enqueue_scripts', 'ask_expert_block_init_assets_admin' );

/**
 * Create shortcode.
 */

add_shortcode( 'ask_expert_block', 'ask_expert_block_render_callback' );

/**
 * Render block for users.
 */

function ask_expert_block_render_callback ( $attributes ) {
  
  global $ask_expert_block_default_field_values;

  foreach($ask_expert_block_default_field_values as $field_name => $default_field_value) {
    if(!isset($attributes[$field_name]) || !$attributes[$field_name]) $attributes[$field_name] = $default_field_value;
  }

  if(!isset($attributes['user_id']) || !$attributes['user_id']) return '<p>' . __('Ask The Expert Block error: User is not defined.', 'ask-expert-block') . '</p>';

  $user = get_userdata( $attributes['user_id'] );

  if ( $user === false ) return '<p>' . __('Ask The Expert Block error: User with id "' . $attributes['user_id']. '" not found.') . '</p>';

  extract( $attributes );

  return '
    <div class="ask-expert-block" style="background-color: ' . $color_background . ';">
      <div class="ask-expert-block__header">
        <div class="ask-expert-block__user">
          <div class="ask-expert-block__user-image-wrapper" style="width: ' . $user_image_size . 'px; height: ' . $user_image_size . 'px;">
            ' . get_avatar($user_id, $user_image_size) . '
          </div>
          <div class="ask-expert-block__user-info">
            <div class="ask-expert-block__user-badge" style="background-color: ' . $badge_color_background . '; color: ' . $badge_color_text . '; font-size: ' . $badge_text_size . 'px;">' . $text_badge . '</div>
            <div class="ask-expert-block__user-name" style="color: ' . $user_color_name . '; font-size: ' . $user_text_size_name . 'px;">' . get_the_author_meta('display_name', $user_id) . '</div>
            <div class="ask-expert-block__user-description" style="color: ' . $user_color_description . '; font-size: ' . $user_text_size_description . 'px;">' . get_the_author_meta('description', $user_id) . '</div>
          </div>
        </div>
        <div class="ask-expert-block__button-wrapper">
          <a class="ask-expert-block__button js_ask-expert-block_go-to-comments" style="background-color: ' . $button_color_background . '; color: ' . $button_color_text . '; font-size: ' . $button_text_size . 'px;" href="#">' . $text_button . '</a>
        </div>
      </div>
      <div class="ask-expert-block_content" style="color: ' . $color_text . '; font-size: ' . $text_size . 'px;">
        ' . $text . '
      </div>
    </div>
  ';
}

/**
 * Add settings page.
 */

function ask_expert_block_options() {
	add_options_page( __('Ask The Expert', 'ask-expert-block'), __('Ask The Expert', 'ask-expert-block'), 'manage_options', 'ask-expert-block.php', 'ask_expert_block_options_callback');
}

add_action('admin_menu', 'ask_expert_block_options');

/**
 * Render settings page.
 */

function ask_expert_block_options_callback() {

?>

<div class="wrap">
  <h2><?php _e('Ask The Expert Block Settings', 'ask-expert-block'); ?></h2>
  <form method="post" enctype="multipart/form-data" action="options.php">
    <?php
      settings_fields('ask_expert_block_options');
      do_settings_sections('ask-expert-block.php');
    ?>
    <p class="submit">  
      <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />  
    </p>
  </form>
</div>

<?php

}

/**
 * Register settings.
 */

function ask_expert_block_register_settings() {

  register_setting( 'ask_expert_block_options', 'ask_expert_block_options' );

  // Base options

  add_settings_section( 'section_base', __('Base Settings'), '', 'ask-expert-block.php' );

	add_settings_field(
    'text_badge', 
    __('Badge text'),
    'ask_expert_block_render_field',
    'ask-expert-block.php',
    'section_base', array(
      'type'      => 'text',
      'id'        => 'text_badge',
  ) );

  add_settings_field(
    'text_button', 
    __('Buttont text'),
    'ask_expert_block_render_field',
    'ask-expert-block.php',
    'section_base', array(
      'type'      => 'text',
      'id'        => 'text_button',
  ) );

	add_settings_field(
    'text_size', 
    __('Text size (px)'),
    'ask_expert_block_render_field',
    'ask-expert-block.php',
    'section_base', array(
      'type'      => 'number',
      'id'        => 'text_size',
  ) );

  add_settings_field(
    'color_background', 
    __('Background color'),
    'ask_expert_block_render_field',
    'ask-expert-block.php',
    'section_base', array(
      'type'      => 'text',
      'id'        => 'color_background',
  ) ); 

  add_settings_field(
    'color_text', 
    __('Text color'),
    'ask_expert_block_render_field',
    'ask-expert-block.php',
    'section_base', array(
      'type'      => 'text',
      'id'        => 'color_text',
  ) );

  add_settings_section( 'section_user', __('User Settings'), '', 'ask-expert-block.php' );

	add_settings_field(
    'user_image_size', 
    __('User image size (px)'),
    'ask_expert_block_render_field',
    'ask-expert-block.php',
    'section_user', array(
      'type'      => 'number',
      'id'        => 'user_image_size',
  ) );

	add_settings_field(
    'user_text_size_name', 
    __('User name text size (px)'),
    'ask_expert_block_render_field',
    'ask-expert-block.php',
    'section_user', array(
      'type'      => 'number',
      'id'        => 'user_text_size_name',
  ) );

  add_settings_field(
    'user_text_size_description', 
    __('User description text size (px)'),
    'ask_expert_block_render_field',
    'ask-expert-block.php',
    'section_user', array(
      'type'      => 'number',
      'id'        => 'user_text_size_description',
  ) );

  add_settings_field(
    'user_color_name', 
    __('User name color'),
    'ask_expert_block_render_field',
    'ask-expert-block.php',
    'section_user', array(
      'type'      => 'text',
      'id'        => 'user_color_name',
  ) );

  add_settings_field(
    'user_color_description', 
    __('User description color'),
    'ask_expert_block_render_field',
    'ask-expert-block.php',
    'section_user', array(
      'type'      => 'text',
      'id'        => 'user_color_description',
  ) );

  add_settings_section( 'section_badge', __('Badge Settings'), '', 'ask-expert-block.php' );

  add_settings_field(
    'badge_text_size', 
    __('Badge text size'),
    'ask_expert_block_render_field',
    'ask-expert-block.php',
    'section_badge', array(
      'type'      => 'number',
      'id'        => 'badge_text_size',
  ) );

  add_settings_field(
    'badge_color_background', 
    __('Badge background color'),
    'ask_expert_block_render_field',
    'ask-expert-block.php',
    'section_badge', array(
      'type'      => 'text',
      'id'        => 'badge_color_background',
  ) );

  add_settings_field(
    'badge_color_text', 
    __('Badge text color'),
    'ask_expert_block_render_field',
    'ask-expert-block.php',
    'section_badge', array(
      'type'      => 'text',
      'id'        => 'badge_color_text',
  ) );

  add_settings_section( 'section_button', __('Button Settings'), '', 'ask-expert-block.php' );

  add_settings_field(
    'button_text_size', 
    __('Button text size'),
    'ask_expert_block_render_field',
    'ask-expert-block.php',
    'section_button', array(
      'type'      => 'number',
      'id'        => 'button_text_size',
  ) );

  add_settings_field(
    'button_color_background', 
    __('Button background color'),
    'ask_expert_block_render_field',
    'ask-expert-block.php',
    'section_button', array(
      'type'      => 'text',
      'id'        => 'button_color_background',
  ) );

  add_settings_field(
    'button_color_text', 
    __('Button text color'),
    'ask_expert_block_render_field',
    'ask-expert-block.php',
    'section_button', array(
      'type'      => 'text',
      'id'        => 'button_color_text',
  ) );
}

add_action( 'admin_init', 'ask_expert_block_register_settings' );



/**
 * Render option field.
 */

function ask_expert_block_render_field ( $args ) {

  global $ask_expert_block_default_field_values;

  extract( $args );

  $options = get_option( 'ask_expert_block_options' );

  (!isset($options[$id]) || $options[$id] == '') ? $options[$id] = $ask_expert_block_default_field_values[$id] : $options[$id];

  print '<input name="ask_expert_block_options[' .  $id . ']" id="' .  $id . '" type="' .  $type . '" value="' . $options[$id] . '" />';
}