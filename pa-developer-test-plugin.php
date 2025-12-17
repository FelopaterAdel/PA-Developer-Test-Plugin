<?php
/**
 * Plugin Name: PA Developer Test Plugin
 * Description: Demonstrates plugin architecture, database usage, and REST API integration.
 * Version: 1.0.0
 * Author: Felopater Adel
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'PA_TEST_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'PA_TEST_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once PA_TEST_PLUGIN_PATH . 'includes/class-activator.php';
require_once PA_TEST_PLUGIN_PATH . 'includes/class-admin-page.php';
require_once PA_TEST_PLUGIN_PATH . 'includes/class-rest-api.php';

// Activation hook
register_activation_hook( __FILE__, [ 'PA_Test_Activator', 'activate' ] );

// Initialize plugin
add_action( 'plugins_loaded', function () {
    new PA_Test_Admin_Page();
    new PA_Test_REST_API();
});
