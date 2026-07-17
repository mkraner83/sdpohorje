<?php
/**
 * Plugin Name: SD Pohorje Accounts
 * Description: Role-based account registration and branded auth forms for SD Pohorje.
 * Version: 0.1.34
 * Author: SD Pohorje
 * Text Domain: sd-pohorje-accounts
 */

if (!defined('ABSPATH')) {
    exit;
}

define('SDP_ACCOUNTS_VERSION', '0.1.34');
define('SDP_ACCOUNTS_FILE', __FILE__);
define('SDP_ACCOUNTS_DIR', plugin_dir_path(__FILE__));
define('SDP_ACCOUNTS_URL', plugin_dir_url(__FILE__));

require_once SDP_ACCOUNTS_DIR . 'includes/class-sdp-accounts-plugin.php';

register_activation_hook(SDP_ACCOUNTS_FILE, array('SDP_Accounts_Plugin', 'activate'));
register_deactivation_hook(SDP_ACCOUNTS_FILE, array('SDP_Accounts_Plugin', 'deactivate'));

SDP_Accounts_Plugin::instance();
