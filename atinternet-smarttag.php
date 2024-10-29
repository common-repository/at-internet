<?php
/*
 * Plugin Name: AT Internet SmartTag
 * Version: 0.2
 * Plugin URI: http://www.atinternet.com/
 * Description: AT Internet official extension, allowing website tracking.
 * Author: AT Internet
 * Author URI: http://www.atinternet.com/
 * Requires at least: 4.0
 * Tested up to: 5.2.2
 *
 * Text Domain: atinternet-smarttag
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author AT Internet
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Load plugin class files
require_once( 'includes/class-atinternet-smarttag.php' );
require_once( 'includes/class-atinternet-smarttag-settings.php' );
require_once( 'includes/class-atinternet-smarttag-metabox.php' );

// Load plugin libraries
require_once( 'includes/lib/class-atinternet-smarttag-admin-api.php' );
require_once( 'includes/lib/class-atinternet-smarttag-tracking.php' );
require_once( 'includes/lib/class-atinternet-smarttag-tracking-tree-structure.php' );
require_once( 'includes/lib/class-atinternet-smarttag-tracking-internal-search.php' );
require_once( 'includes/lib/class-atinternet-smarttag-tracking-configuration.php' );
require_once( 'includes/lib/class-atinternet-smarttag-tracking-js-builder.php' );

/**
 * Returns the main instance of ATInternet_SmartTag to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object ATInternet_SmartTag
 */
function ATInternet_SmartTag () {
	$instance = ATInternet_SmartTag::instance( __FILE__, '1.0.0' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = ATInternet_SmartTag_Settings::instance( $instance );
	}

	return $instance;
}

ATInternet_SmartTag();
