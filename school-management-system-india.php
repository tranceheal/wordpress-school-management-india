<?php
/**
 * Plugin Name: School Management System India
 * Plugin URI: https://github.com/tranceheal/wordpress-school-management-india
 * Description: A complete school management system for Indian institutions with student records, attendance, fees, reports, and parent/student access.
 * Version: 1.3.0
 * Author: School Systems Team
 * Text Domain: school-management-system-india
 * License: GPL-3.0-or-later
 */

if (!defined('ABSPATH')) {
    exit;
}

define('SMSI_PLUGIN_FILE', __FILE__);
define('SMSI_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SMSI_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SMSI_VERSION', '1.3.0');

require_once SMSI_PLUGIN_DIR . 'includes/class-smsi-post-types.php';
require_once SMSI_PLUGIN_DIR . 'includes/class-smsi-meta-boxes.php';
require_once SMSI_PLUGIN_DIR . 'includes/class-smsi-admin.php';
require_once SMSI_PLUGIN_DIR . 'includes/class-smsi-shortcodes.php';
require_once SMSI_PLUGIN_DIR . 'includes/class-smsi-attendance.php';
require_once SMSI_PLUGIN_DIR . 'includes/class-smsi-fees.php';
require_once SMSI_PLUGIN_DIR . 'includes/class-smsi-exams.php';
require_once SMSI_PLUGIN_DIR . 'includes/class-smsi-auth.php';
require_once SMSI_PLUGIN_DIR . 'includes/class-smsi-loader.php';

function smsi_plugin() {
    static $instance = null;

    if (null === $instance) {
        $instance = new SMSI_Loader();
    }

    return $instance;
}

register_activation_hook(__FILE__, array('SMSI_Loader', 'activate'));
register_deactivation_hook(__FILE__, array('SMSI_Loader', 'deactivate'));

smsi_plugin()->run();
