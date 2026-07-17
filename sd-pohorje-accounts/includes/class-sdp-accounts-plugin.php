<?php

if (!defined('ABSPATH')) {
    exit;
}

class SDP_Accounts_Plugin
{
    const ATHLETE_ROLE = 'sdp_athlete';
    const PARENT_ROLE = 'sdp_parent';
    const STAFF_ROLE = 'sdp_staff';

    const META_STATUS = 'sdp_registration_status';
    const META_ROLE_TYPE = 'sdp_role_type';
    const META_CHILD_INFO = 'sdp_child_info';
    const META_PARENT_IDS = 'sdp_parent_ids';
    const META_ATHLETE_IDS = 'sdp_athlete_ids';
    const META_ITEM_PRICE = 'sdp_item_price';
    const META_ITEM_CONDITION = 'sdp_item_condition';
    const META_ITEM_CATEGORY = 'sdp_item_category';
    const META_ITEM_SIZE = 'sdp_item_size';
    const META_ITEM_STATE = 'sdp_item_state';
    const META_CLUB_PRODUCT_PRICE = 'sdp_club_product_price';
    const META_CLUB_PRODUCT_ACTIVE = 'sdp_club_product_active';
    const META_CLUB_PRODUCT_SIZES = 'sdp_club_product_sizes';
    const META_CLUB_ORDER_STATUS = 'sdp_club_order_status';
    const META_CLUB_ORDER_ITEMS = 'sdp_club_order_items';
    const META_CLUB_ORDER_CHILD_NAME = 'sdp_club_order_child_name';
    const META_CLUB_ORDER_PRODUCT_ID = 'sdp_club_order_product_id';
    const META_CLUB_ORDER_PRODUCT_NAME = 'sdp_club_order_product_name';
    const META_CLUB_ORDER_PRODUCT_PRICE = 'sdp_club_order_product_price';
    const META_CLUB_ORDER_QUANTITY = 'sdp_club_order_quantity';
    const META_CLUB_ORDER_SIZE = 'sdp_club_order_size';
    const META_CLUB_ORDER_NOTE = 'sdp_club_order_note';
    const META_CLUB_ORDER_CUSTOMER_NAME = 'sdp_club_order_customer_name';
    const META_CLUB_ORDER_CUSTOMER_EMAIL = 'sdp_club_order_customer_email';
    const META_CLUB_ORDER_CUSTOMER_PHONE = 'sdp_club_order_customer_phone';
    const META_CLUB_ORDER_ADMIN_NOTE = 'sdp_club_order_admin_note';
    const META_TRAINING_DOC_ATHLETE_ID = 'sdp_training_doc_athlete_id';
    const META_TRAINING_DOC_FILE_URL = 'sdp_training_doc_file_url';
    const META_TRAINING_DOC_DESCRIPTION = 'sdp_training_doc_description';
    const OPTION_ADMIN_NOTIFICATION_EMAIL = 'sdp_admin_notification_email';
    const MARKETPLACE_POST_TYPE = 'sdp_market_item';
    const CLUB_PRODUCT_POST_TYPE = 'sdp_club_product';
    const CLUB_ORDER_POST_TYPE = 'sdp_club_order';
    const TRAINING_DOC_POST_TYPE = 'sdp_training_doc';

    private static $instance = null;

    public static function instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function activate()
    {
        self::register_roles();
        self::register_marketplace_post_type();
        self::register_club_shop_post_types();
        self::register_training_doc_post_type();
        flush_rewrite_rules();
    }

    public static function deactivate()
    {
        flush_rewrite_rules();
    }

    private static function register_roles()
    {
        add_role(
            self::ATHLETE_ROLE,
            'Athlete',
            array(
                'read' => true,
            )
        );

        add_role(
            self::PARENT_ROLE,
            'Parent',
            array(
                'read' => true,
            )
        );

        add_role(
            self::STAFF_ROLE,
            'Staff',
            array(
                'read' => true,
                'edit_posts' => true,
                'upload_files' => true,
            )
        );
    }

    private static function register_marketplace_post_type()
    {
        register_post_type(
            self::MARKETPLACE_POST_TYPE,
            array(
                'labels' => array(
                    'name' => 'Marketplace Items',
                    'singular_name' => 'Marketplace Item',
                    'menu_name' => 'Marketplace Items',
                    'add_new_item' => 'Add New Item',
                    'edit_item' => 'Edit Item',
                ),
                'public' => false,
                'show_ui' => true,
                'show_in_menu' => 'sdp-portal-users',
                'supports' => array('title', 'editor', 'author', 'thumbnail'),
                'capability_type' => 'post',
                'map_meta_cap' => true,
            )
        );
    }

    private static function register_club_shop_post_types()
    {
        register_post_type(
            self::CLUB_PRODUCT_POST_TYPE,
            array(
                'labels' => array(
                    'name' => 'Club Shop Products',
                    'singular_name' => 'Club Shop Product',
                    'menu_name' => 'Club Shop Products',
                    'add_new_item' => 'Add New Product',
                    'edit_item' => 'Edit Product',
                ),
                'public' => false,
                'show_ui' => true,
                'show_in_menu' => 'sdp-portal-users',
                'supports' => array('title', 'editor', 'thumbnail', 'page-attributes'),
                'capability_type' => 'post',
                'map_meta_cap' => true,
            )
        );

        register_post_type(
            self::CLUB_ORDER_POST_TYPE,
            array(
                'labels' => array(
                    'name' => 'Club Shop Orders',
                    'singular_name' => 'Club Shop Order',
                    'menu_name' => 'Club Shop Orders',
                    'add_new_item' => 'Add New Order',
                    'edit_item' => 'View Order',
                ),
                'public' => false,
                'show_ui' => true,
                'show_in_menu' => 'sdp-portal-users',
                'supports' => array('title', 'author'),
                'capability_type' => 'post',
                'map_meta_cap' => true,
            )
        );
    }

    private static function register_training_doc_post_type()
    {
        register_post_type(
            self::TRAINING_DOC_POST_TYPE,
            array(
                'labels' => array(
                    'name' => 'Training Plans',
                    'singular_name' => 'Training Document',
                    'menu_name' => 'Training Plans',
                    'add_new_item' => 'Add New Training Document',
                    'edit_item' => 'Edit Training Document',
                ),
                'public' => false,
                'show_ui' => true,
                'show_in_menu' => 'sdp-portal-users',
                'supports' => array('title'),
                'capability_type' => 'post',
                'map_meta_cap' => true,
            )
        );
    }

    private function __construct()
    {
        add_action('init', array($this, 'register_roles_runtime'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('init', array($this, 'handle_form_requests'));

        add_shortcode('sdp_register_athlete', array($this, 'render_athlete_registration'));
        add_shortcode('sdp_register_parent', array($this, 'render_parent_registration'));
        add_shortcode('sdp_login', array($this, 'render_login_form'));
        add_shortcode('sdp_forgot_password', array($this, 'render_forgot_password_form'));
        add_shortcode('sdp_reset_password', array($this, 'render_reset_password_form'));
        add_shortcode('sdp_dashboard', array($this, 'render_dashboard'));
        add_shortcode('sdp_marketplace_sell', array($this, 'render_marketplace_sell_form'));
        add_shortcode('sdp_marketplace', array($this, 'render_marketplace_listings'));
        add_shortcode('sdp_club_shop', array($this, 'render_club_shop'));
        add_shortcode('sdp_club_orders', array($this, 'render_club_orders'));

        add_filter('authenticate', array($this, 'block_pending_users'), 30, 3);
        add_filter('login_redirect', array($this, 'force_portal_user_login_redirect'), 10, 3);
        add_action('admin_init', array($this, 'restrict_portal_users_from_wp_admin'));
        add_filter('show_admin_bar', array($this, 'hide_admin_bar_for_portal_users'));

        add_action('admin_menu', array($this, 'register_admin_pages'));
        add_action('admin_post_sdp_portal_save_settings', array($this, 'handle_admin_settings_save'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('pre_get_users', array($this, 'filter_users_admin_lists'));
        add_action('add_meta_boxes', array($this, 'register_club_shop_meta_boxes'));
        add_action('save_post_' . self::CLUB_PRODUCT_POST_TYPE, array($this, 'save_club_product_meta'));
        add_action('save_post_' . self::CLUB_ORDER_POST_TYPE, array($this, 'save_club_order_meta'));
        add_action('save_post_' . self::TRAINING_DOC_POST_TYPE, array($this, 'save_training_doc_meta'));
        add_filter('manage_' . self::CLUB_PRODUCT_POST_TYPE . '_posts_columns', array($this, 'set_club_product_columns'));
        add_action('manage_' . self::CLUB_PRODUCT_POST_TYPE . '_posts_custom_column', array($this, 'render_club_product_column'), 10, 2);
        add_filter('manage_' . self::CLUB_ORDER_POST_TYPE . '_posts_columns', array($this, 'set_club_order_columns'));
        add_action('manage_' . self::CLUB_ORDER_POST_TYPE . '_posts_custom_column', array($this, 'render_club_order_column'), 10, 2);
        add_filter('manage_' . self::TRAINING_DOC_POST_TYPE . '_posts_columns', array($this, 'set_training_doc_columns'));
        add_action('manage_' . self::TRAINING_DOC_POST_TYPE . '_posts_custom_column', array($this, 'render_training_doc_column'), 10, 2);
    }

    public function register_roles_runtime()
    {
        self::register_roles();
        self::register_marketplace_post_type();
        self::register_club_shop_post_types();
        self::register_training_doc_post_type();
    }

    private function is_portal_user($user = null)
    {
        if (!$user instanceof WP_User) {
            $user = wp_get_current_user();
        }

        if (!$user instanceof WP_User || empty($user->roles)) {
            return false;
        }

        return in_array(self::ATHLETE_ROLE, $user->roles, true) || in_array(self::PARENT_ROLE, $user->roles, true);
    }

    public function restrict_portal_users_from_wp_admin()
    {
        if (!is_admin() || !is_user_logged_in()) {
            return;
        }

        if (wp_doing_ajax()) {
            return;
        }

        if (!$this->is_portal_user()) {
            return;
        }

        wp_safe_redirect($this->get_dashboard_url());
        exit;
    }

    public function hide_admin_bar_for_portal_users($show)
    {
        if ($this->is_portal_user()) {
            return false;
        }

        return $show;
    }

    public function force_portal_user_login_redirect($redirect_to, $requested_redirect_to, $user)
    {
        if ($user instanceof WP_User && $this->is_portal_user($user)) {
            return $this->get_dashboard_url();
        }

        return $redirect_to;
    }

    public function enqueue_assets()
    {
        wp_enqueue_style('dashicons');

        wp_enqueue_style(
            'sdp-accounts-style',
            SDP_ACCOUNTS_URL . 'assets/css/sdp-accounts.css',
            array(),
            SDP_ACCOUNTS_VERSION
        );

        wp_enqueue_script(
            'sdp-accounts-script',
            SDP_ACCOUNTS_URL . 'assets/js/sdp-accounts.js',
            array(),
            SDP_ACCOUNTS_VERSION,
            true
        );
    }

    public function enqueue_admin_assets($hook_suffix)
    {
        if (!in_array($hook_suffix, array('post.php', 'post-new.php'), true)) {
            return;
        }

        $screen = function_exists('get_current_screen') ? get_current_screen() : null;

        if (!$screen || $screen->post_type !== self::TRAINING_DOC_POST_TYPE) {
            return;
        }

        wp_enqueue_media();
        wp_enqueue_script(
            'sdp-accounts-admin-script',
            SDP_ACCOUNTS_URL . 'assets/js/sdp-accounts-admin.js',
            array('jquery'),
            SDP_ACCOUNTS_VERSION,
            true
        );
    }

    private function get_request_action()
    {
        if (!isset($_POST['sdp_action'])) {
            return '';
        }

        return sanitize_key(wp_unslash($_POST['sdp_action']));
    }

    public function handle_form_requests()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $action = $this->get_request_action();

        if ($action === 'register_athlete') {
            $this->handle_registration(self::ATHLETE_ROLE);
            return;
        }

        if ($action === 'register_parent') {
            $this->handle_registration(self::PARENT_ROLE);
            return;
        }

        if ($action === 'login') {
            $this->handle_login();
            return;
        }

        if ($action === 'forgot_password') {
            $this->handle_forgot_password();
            return;
        }

        if ($action === 'reset_password') {
            $this->handle_reset_password();
            return;
        }

        if ($action === 'update_profile') {
            $this->handle_profile_update();
            return;
        }

        if ($action === 'marketplace_create') {
            $this->handle_marketplace_create_listing();
            return;
        }

        if ($action === 'marketplace_contact') {
            $this->handle_marketplace_contact_seller();
            return;
        }

        if ($action === 'marketplace_update') {
            $this->handle_marketplace_update_listing();
            return;
        }

        if ($action === 'marketplace_delete') {
            $this->handle_marketplace_delete_listing();
            return;
        }

        if ($action === 'marketplace_mark_sold') {
            $this->handle_marketplace_mark_sold();
            return;
        }

        if ($action === 'club_order_create') {
            $this->handle_club_order_create();
        }
    }

    private function can_manage_marketplace()
    {
        if (!is_user_logged_in()) {
            return false;
        }

        $user = wp_get_current_user();

        return in_array(self::ATHLETE_ROLE, $user->roles, true) || in_array(self::PARENT_ROLE, $user->roles, true);
    }

    private function can_place_club_orders()
    {
        return $this->can_manage_marketplace();
    }

    private function can_view_training_documents($user = null)
    {
        if (!$user instanceof WP_User) {
            $user = wp_get_current_user();
        }

        if (!$user instanceof WP_User || empty($user->roles)) {
            return false;
        }

        return in_array(self::ATHLETE_ROLE, $user->roles, true);
    }

    private function get_club_order_status_labels()
    {
        return array(
            'new' => 'Novo',
            'confirmed' => 'Potrjeno',
            'completed' => 'Zaključeno',
            'cancelled' => 'Preklicano',
        );
    }

    private function get_club_order_status_label($status)
    {
        $labels = $this->get_club_order_status_labels();

        return isset($labels[$status]) ? $labels[$status] : $labels['new'];
    }

    private function get_user_marketplace_summary($user_id)
    {
        $item_ids = get_posts(
            array(
                'post_type' => self::MARKETPLACE_POST_TYPE,
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'author' => (int) $user_id,
                'fields' => 'ids',
            )
        );

        $summary = array(
            'total' => count($item_ids),
            'active' => 0,
            'sold' => 0,
        );

        foreach ($item_ids as $item_id) {
            $state = (string) get_post_meta($item_id, self::META_ITEM_STATE, true);

            if ($state === 'sold') {
                $summary['sold']++;
            } else {
                $summary['active']++;
            }
        }

        return $summary;
    }

    private function get_user_latest_club_order_summary($user_id)
    {
        $orders = get_posts(
            array(
                'post_type' => self::CLUB_ORDER_POST_TYPE,
                'post_status' => 'publish',
                'posts_per_page' => 1,
                'author' => (int) $user_id,
                'orderby' => 'date',
                'order' => 'DESC',
            )
        );

        if (empty($orders)) {
            return array(
                'has_orders' => false,
                'status_label' => '',
                'items_summary' => '',
                'quantity' => 0,
            );
        }

        $latest_order = $orders[0];
        $items = $this->get_club_order_items($latest_order->ID);
        $status = (string) get_post_meta($latest_order->ID, self::META_CLUB_ORDER_STATUS, true);

        return array(
            'has_orders' => true,
            'status_label' => $this->get_club_order_status_label($status),
            'items_summary' => $this->get_club_order_items_summary_from_items($items),
            'quantity' => $this->get_club_order_total_quantity_from_items($items),
        );
    }

    private function get_athlete_training_documents($athlete_id)
    {
        return get_posts(
            array(
                'post_type' => self::TRAINING_DOC_POST_TYPE,
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'orderby' => 'date',
                'order' => 'DESC',
                'meta_query' => array(
                    array(
                        'key' => self::META_TRAINING_DOC_ATHLETE_ID,
                        'value' => (string) ((int) $athlete_id),
                        'compare' => '=',
                    ),
                ),
            )
        );
    }

    private function get_club_product_sizes($post_id)
    {
        $sizes_raw = (string) get_post_meta($post_id, self::META_CLUB_PRODUCT_SIZES, true);

        if ($sizes_raw === '') {
            return array();
        }

        $sizes = array_map('trim', explode(',', $sizes_raw));
        $sizes = array_filter($sizes, static function ($size) {
            return $size !== '';
        });

        return array_values(array_unique($sizes));
    }

    private function is_club_product_active($post_id)
    {
        return get_post_meta($post_id, self::META_CLUB_PRODUCT_ACTIVE, true) !== 'no';
    }

    private function get_club_product_price($post_id)
    {
        return (string) get_post_meta($post_id, self::META_CLUB_PRODUCT_PRICE, true);
    }

    private function format_price($price)
    {
        if ($price === '' || !is_numeric((string) $price)) {
            return '';
        }

        return number_format((float) $price, 2, ',', '.') . ' EUR';
    }

    private function get_club_product($post_id)
    {
        $post = get_post($post_id);

        if (!$post || $post->post_type !== self::CLUB_PRODUCT_POST_TYPE || $post->post_status !== 'publish') {
            return null;
        }

        if (!$this->is_club_product_active($post->ID)) {
            return null;
        }

        return $post;
    }

    private function get_club_order_items($order_id)
    {
        $raw_items = get_post_meta($order_id, self::META_CLUB_ORDER_ITEMS, true);
        $items = array();

        if (is_string($raw_items) && $raw_items !== '') {
            $decoded = json_decode($raw_items, true);

            if (is_array($decoded)) {
                foreach ($decoded as $item) {
                    if (!is_array($item)) {
                        continue;
                    }

                    $name = isset($item['name']) ? sanitize_text_field((string) $item['name']) : '';
                    $quantity = isset($item['quantity']) ? max(1, (int) $item['quantity']) : 0;
                    $price = isset($item['price']) && is_numeric((string) $item['price']) ? number_format((float) $item['price'], 2, '.', '') : '';

                    if ($name === '' || $quantity < 1) {
                        continue;
                    }

                    $items[] = array(
                        'product_id' => isset($item['product_id']) ? (int) $item['product_id'] : 0,
                        'name' => $name,
                        'price' => $price,
                        'quantity' => $quantity,
                        'size' => isset($item['size']) ? sanitize_text_field((string) $item['size']) : '',
                    );
                }
            }
        }

        if (!empty($items)) {
            return $items;
        }

        $legacy_name = (string) get_post_meta($order_id, self::META_CLUB_ORDER_PRODUCT_NAME, true);
        $legacy_quantity = max(1, (int) get_post_meta($order_id, self::META_CLUB_ORDER_QUANTITY, true));

        if ($legacy_name === '') {
            return array();
        }

        return array(
            array(
                'product_id' => (int) get_post_meta($order_id, self::META_CLUB_ORDER_PRODUCT_ID, true),
                'name' => $legacy_name,
                'price' => (string) get_post_meta($order_id, self::META_CLUB_ORDER_PRODUCT_PRICE, true),
                'quantity' => $legacy_quantity,
                'size' => (string) get_post_meta($order_id, self::META_CLUB_ORDER_SIZE, true),
            ),
        );
    }

    private function get_club_order_total_from_items($items)
    {
        $total = 0.0;

        foreach ($items as $item) {
            $price = isset($item['price']) ? (string) $item['price'] : '';
            $quantity = isset($item['quantity']) ? max(1, (int) $item['quantity']) : 1;

            if (is_numeric($price)) {
                $total += (float) $price * $quantity;
            }
        }

        return number_format($total, 2, '.', '');
    }

    private function get_club_order_total_quantity_from_items($items)
    {
        $quantity = 0;

        foreach ($items as $item) {
            $quantity += isset($item['quantity']) ? max(1, (int) $item['quantity']) : 1;
        }

        return max(1, $quantity);
    }

    private function get_club_order_items_summary_from_items($items)
    {
        if (empty($items)) {
            return '';
        }

        $first_name = isset($items[0]['name']) ? (string) $items[0]['name'] : '';

        if (count($items) === 1) {
            return $first_name;
        }

        return $first_name . ' +' . (count($items) - 1);
    }

    private function build_club_order_items_html($items)
    {
        if (empty($items)) {
            return '<p style="margin:0;">-</p>';
        }

        $html = '<ul style="margin:0;padding:0 0 0 18px;">';

        foreach ($items as $item) {
            $name = isset($item['name']) ? (string) $item['name'] : '';
            $quantity = isset($item['quantity']) ? max(1, (int) $item['quantity']) : 1;
            $size = isset($item['size']) ? (string) $item['size'] : '';
            $price = isset($item['price']) ? (string) $item['price'] : '';
            $line_total = is_numeric($price) ? number_format((float) $price * $quantity, 2, '.', '') : '';

            $html .= '<li style="margin:0 0 8px;">';
            $html .= '<strong>' . esc_html($name) . '</strong>';
            $html .= ' x ' . esc_html((string) $quantity);

            if ($size !== '') {
                $html .= ' | ' . esc_html($size);
            }

            if ($line_total !== '') {
                $html .= ' | ' . esc_html($this->format_price($line_total));
            }

            $html .= '</li>';
        }

        $html .= '</ul>';

        return $html;
    }

    private function build_club_order_items_text($items)
    {
        if (empty($items)) {
            return '-';
        }

        $lines = array();

        foreach ($items as $item) {
            $name = isset($item['name']) ? (string) $item['name'] : '';
            $quantity = isset($item['quantity']) ? max(1, (int) $item['quantity']) : 1;
            $size = isset($item['size']) ? (string) $item['size'] : '';
            $price = isset($item['price']) ? (string) $item['price'] : '';
            $line = $name . ' x ' . $quantity;

            if ($size !== '') {
                $line .= ' | ' . $size;
            }

            if (is_numeric($price)) {
                $line .= ' | ' . $this->format_price(number_format((float) $price * $quantity, 2, '.', ''));
            }

            $lines[] = $line;
        }

        return implode("\n", $lines);
    }

    private function handle_club_order_create()
    {
        if (!$this->can_place_club_orders()) {
            $this->redirect_with_message('error', 'Za oddajo naročila se morate prijaviti kot atlet ali starš.');
        }

        if (!$this->nonce_ok('sdp_club_order_create_nonce')) {
            $this->redirect_with_message('error', 'Varnostno preverjanje ni uspelo. Poskusite znova.');
        }

        $cart_payload = isset($_POST['club_cart_payload']) ? wp_unslash($_POST['club_cart_payload']) : '';
        $child_name = isset($_POST['club_child_name']) ? sanitize_text_field(wp_unslash($_POST['club_child_name'])) : '';
        $note = isset($_POST['club_note']) ? sanitize_textarea_field(wp_unslash($_POST['club_note'])) : '';

        if ($child_name === '') {
            $this->redirect_with_message('error', 'Prosimo, vnesite za koga je naročilo.');
        }

        if (!is_string($cart_payload) || $cart_payload === '') {
            $this->redirect_with_message('error', 'Košarica je prazna. Pred oddajo dodajte vsaj en izdelek.');
        }

        $decoded_cart = json_decode($cart_payload, true);

        if (!is_array($decoded_cart) || empty($decoded_cart)) {
            $this->redirect_with_message('error', 'Košarice ni bilo mogoče prebrati. Izdelke dodajte ponovno.');
        }

        $user_id = get_current_user_id();
        $user = get_user_by('id', $user_id);

        if (!$user || !is_email($user->user_email)) {
            $this->redirect_with_message('error', 'Za oddajo naročila potrebujete veljaven uporabniški račun.');
        }

        $first_name = (string) get_user_meta($user_id, 'first_name', true);
        $last_name = (string) get_user_meta($user_id, 'last_name', true);
        $phone = (string) get_user_meta($user_id, 'sdp_phone', true);
        $customer_name = trim($first_name . ' ' . $last_name);

        if ($customer_name === '') {
            $customer_name = $user->display_name ? $user->display_name : $user->user_login;
        }

        $normalized_items = array();

        foreach ($decoded_cart as $cart_item) {
            if (!is_array($cart_item)) {
                continue;
            }

            $product_id = isset($cart_item['product_id']) ? (int) $cart_item['product_id'] : 0;
            $quantity = isset($cart_item['quantity']) ? (int) $cart_item['quantity'] : 0;
            $size = isset($cart_item['size']) ? sanitize_text_field((string) $cart_item['size']) : '';

            if (!$product_id || $quantity < 1) {
                continue;
            }

            $product = $this->get_club_product($product_id);

            if (!$product) {
                $this->redirect_with_message('error', 'Eden od izbranih izdelkov ni več na voljo za naročilo.');
            }

            $available_sizes = $this->get_club_product_sizes($product->ID);

            if (!empty($available_sizes) && ($size === '' || !in_array($size, $available_sizes, true))) {
                $this->redirect_with_message('error', 'Eden od izdelkov nima veljavno izbrane velikosti.');
            }

            $price = $this->get_club_product_price($product->ID);

            $normalized_items[] = array(
                'product_id' => $product->ID,
                'name' => $product->post_title,
                'price' => $price,
                'quantity' => $quantity,
                'size' => $size,
            );
        }

        if (empty($normalized_items)) {
            $this->redirect_with_message('error', 'Košarica je prazna. Pred oddajo dodajte vsaj en izdelek.');
        }

        $order_title = sprintf('Naročilo - %s - %s', $customer_name, $child_name);

        $order_id = wp_insert_post(
            array(
                'post_type' => self::CLUB_ORDER_POST_TYPE,
                'post_title' => $order_title,
                'post_status' => 'publish',
                'post_author' => $user_id,
            ),
            true
        );

        if (is_wp_error($order_id)) {
            $this->redirect_with_message('error', 'Naročila ni bilo mogoče oddati. Poskusite ponovno.');
        }

        $first_item = $normalized_items[0];
        $summary_name = $this->get_club_order_items_summary_from_items($normalized_items);
        $summary_total = $this->get_club_order_total_from_items($normalized_items);
        $summary_quantity = $this->get_club_order_total_quantity_from_items($normalized_items);

        update_post_meta($order_id, self::META_CLUB_ORDER_STATUS, 'new');
        update_post_meta($order_id, self::META_CLUB_ORDER_ITEMS, wp_json_encode($normalized_items));
        update_post_meta($order_id, self::META_CLUB_ORDER_CHILD_NAME, $child_name);
        update_post_meta($order_id, self::META_CLUB_ORDER_PRODUCT_ID, $first_item['product_id']);
        update_post_meta($order_id, self::META_CLUB_ORDER_PRODUCT_NAME, $summary_name);
        update_post_meta($order_id, self::META_CLUB_ORDER_PRODUCT_PRICE, $summary_total);
        update_post_meta($order_id, self::META_CLUB_ORDER_QUANTITY, $summary_quantity);
        update_post_meta($order_id, self::META_CLUB_ORDER_SIZE, count($normalized_items) === 1 ? $first_item['size'] : 'Več izdelkov');
        update_post_meta($order_id, self::META_CLUB_ORDER_NOTE, $note);
        update_post_meta($order_id, self::META_CLUB_ORDER_CUSTOMER_NAME, $customer_name);
        update_post_meta($order_id, self::META_CLUB_ORDER_CUSTOMER_EMAIL, $user->user_email);
        update_post_meta($order_id, self::META_CLUB_ORDER_CUSTOMER_PHONE, $phone);

        $this->send_club_order_confirmation($order_id);
        $this->send_admin_club_order_notice($order_id);

        $this->redirect_with_message('success', 'Naročilo je bilo uspešno oddano. Potrditev boste prejeli po e-pošti.');
    }

    private function get_marketplace_allowed_conditions()
    {
        return array('novo', 'odlicno', 'dobro', 'solidno');
    }

    private function get_editable_marketplace_item($post_id)
    {
        $post = get_post($post_id);

        if (!$post || $post->post_type !== self::MARKETPLACE_POST_TYPE) {
            return null;
        }

        if ((int) $post->post_author !== get_current_user_id() && !current_user_can('manage_options')) {
            return null;
        }

        return $post;
    }

    private function handle_marketplace_image_upload($post_id, $field_name)
    {
        if (!isset($_FILES[$field_name]) || empty($_FILES[$field_name]['name'])) {
            return;
        }

        if (!function_exists('media_handle_upload')) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/image.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';
        }

        $attachment_id = media_handle_upload($field_name, $post_id);

        if (is_wp_error($attachment_id)) {
            $this->redirect_with_message('error', 'Slike ni bilo mogoče naložiti. Poskusite ponovno.');
        }

        set_post_thumbnail($post_id, $attachment_id);
    }

    private function handle_marketplace_create_listing()
    {
        if (!$this->can_manage_marketplace()) {
            $this->redirect_with_message('error', 'Za oddajo oglasa se morate prijaviti kot atlet ali starš.');
        }

        if (!$this->nonce_ok('sdp_marketplace_create_nonce')) {
            $this->redirect_with_message('error', 'Varnostno preverjanje ni uspelo. Poskusite znova.');
        }

        $title = isset($_POST['item_title']) ? sanitize_text_field(wp_unslash($_POST['item_title'])) : '';
        $description = isset($_POST['item_description']) ? sanitize_textarea_field(wp_unslash($_POST['item_description'])) : '';
        $price_raw = isset($_POST['item_price']) ? sanitize_text_field(wp_unslash($_POST['item_price'])) : '';
        $condition = isset($_POST['item_condition']) ? sanitize_key(wp_unslash($_POST['item_condition'])) : '';
        $category = isset($_POST['item_category']) ? sanitize_text_field(wp_unslash($_POST['item_category'])) : '';
        $size = isset($_POST['item_size']) ? sanitize_text_field(wp_unslash($_POST['item_size'])) : '';

        $allowed_conditions = $this->get_marketplace_allowed_conditions();

        if ($title === '' || $description === '' || $price_raw === '' || $condition === '') {
            $this->redirect_with_message('error', 'Prosimo, izpolnite vsa obvezna polja oglasa.');
        }

        if (!in_array($condition, $allowed_conditions, true)) {
            $this->redirect_with_message('error', 'Izbrano stanje izdelka ni veljavno.');
        }

        $price_normalized = str_replace(',', '.', $price_raw);

        if (!is_numeric($price_normalized)) {
            $this->redirect_with_message('error', 'Cena mora biti številčna vrednost.');
        }

        $price = number_format((float) $price_normalized, 2, '.', '');

        $post_id = wp_insert_post(
            array(
                'post_type' => self::MARKETPLACE_POST_TYPE,
                'post_title' => $title,
                'post_content' => $description,
                'post_status' => 'publish',
                'post_author' => get_current_user_id(),
            ),
            true
        );

        if (is_wp_error($post_id)) {
            $this->redirect_with_message('error', 'Oglasa ni bilo mogoče ustvariti. Poskusite ponovno.');
        }

        update_post_meta($post_id, self::META_ITEM_PRICE, $price);
        update_post_meta($post_id, self::META_ITEM_CONDITION, $condition);
        update_post_meta($post_id, self::META_ITEM_CATEGORY, $category);
        update_post_meta($post_id, self::META_ITEM_SIZE, $size);
        update_post_meta($post_id, self::META_ITEM_STATE, 'active');

        $this->handle_marketplace_image_upload($post_id, 'item_image');

        $this->redirect_with_message('success', 'Oglas je bil uspešno objavljen.');
    }

    private function handle_marketplace_update_listing()
    {
        if (!$this->can_manage_marketplace()) {
            $this->redirect_with_message('error', 'Za urejanje oglasa se morate prijaviti kot atlet ali starš.');
        }

        if (!$this->nonce_ok('sdp_marketplace_update_nonce')) {
            $this->redirect_with_message('error', 'Varnostno preverjanje ni uspelo. Poskusite znova.');
        }

        $post_id = isset($_POST['item_id']) ? (int) $_POST['item_id'] : 0;
        $post = $this->get_editable_marketplace_item($post_id);

        if (!$post) {
            $this->redirect_with_message('error', 'Izbranega oglasa ni mogoče urejati.');
        }

        $title = isset($_POST['item_title']) ? sanitize_text_field(wp_unslash($_POST['item_title'])) : '';
        $description = isset($_POST['item_description']) ? sanitize_textarea_field(wp_unslash($_POST['item_description'])) : '';
        $price_raw = isset($_POST['item_price']) ? sanitize_text_field(wp_unslash($_POST['item_price'])) : '';
        $condition = isset($_POST['item_condition']) ? sanitize_key(wp_unslash($_POST['item_condition'])) : '';
        $category = isset($_POST['item_category']) ? sanitize_text_field(wp_unslash($_POST['item_category'])) : '';
        $size = isset($_POST['item_size']) ? sanitize_text_field(wp_unslash($_POST['item_size'])) : '';

        if ($title === '' || $description === '' || $price_raw === '' || $condition === '') {
            $this->redirect_with_message('error', 'Prosimo, izpolnite vsa obvezna polja oglasa.');
        }

        if (!in_array($condition, $this->get_marketplace_allowed_conditions(), true)) {
            $this->redirect_with_message('error', 'Izbrano stanje izdelka ni veljavno.');
        }

        $price_normalized = str_replace(',', '.', $price_raw);

        if (!is_numeric($price_normalized)) {
            $this->redirect_with_message('error', 'Cena mora biti številčna vrednost.');
        }

        $price = number_format((float) $price_normalized, 2, '.', '');

        $update_result = wp_update_post(
            array(
                'ID' => $post->ID,
                'post_title' => $title,
                'post_content' => $description,
            ),
            true
        );

        if (is_wp_error($update_result)) {
            $this->redirect_with_message('error', 'Oglasa ni bilo mogoče posodobiti. Poskusite ponovno.');
        }

        update_post_meta($post->ID, self::META_ITEM_PRICE, $price);
        update_post_meta($post->ID, self::META_ITEM_CONDITION, $condition);
        update_post_meta($post->ID, self::META_ITEM_CATEGORY, $category);
        update_post_meta($post->ID, self::META_ITEM_SIZE, $size);

        $this->handle_marketplace_image_upload($post->ID, 'item_image');

        $edit_url = add_query_arg('sdp_edit_item', (int) $post->ID, get_permalink());
        $_POST['sdp_return_url'] = $edit_url;
        $this->redirect_with_message('success', 'Oglas je bil uspešno posodobljen.');
    }

    private function handle_marketplace_delete_listing()
    {
        if (!$this->can_manage_marketplace()) {
            $this->redirect_with_message('error', 'Za brisanje oglasa se morate prijaviti kot atlet ali starš.');
        }

        if (!$this->nonce_ok('sdp_marketplace_manage_nonce')) {
            $this->redirect_with_message('error', 'Varnostno preverjanje ni uspelo. Poskusite znova.');
        }

        $post_id = isset($_POST['item_id']) ? (int) $_POST['item_id'] : 0;
        $post = $this->get_editable_marketplace_item($post_id);

        if (!$post) {
            $this->redirect_with_message('error', 'Izbranega oglasa ni mogoče izbrisati.');
        }

        $deleted = wp_trash_post($post->ID);

        if (!$deleted) {
            $this->redirect_with_message('error', 'Oglasa ni bilo mogoče izbrisati. Poskusite ponovno.');
        }

        $this->redirect_with_message('success', 'Oglas je bil premaknjen v koš.');
    }

    private function handle_marketplace_mark_sold()
    {
        if (!$this->can_manage_marketplace()) {
            $this->redirect_with_message('error', 'Za spremembo statusa oglasa se morate prijaviti kot atlet ali starš.');
        }

        if (!$this->nonce_ok('sdp_marketplace_manage_nonce')) {
            $this->redirect_with_message('error', 'Varnostno preverjanje ni uspelo. Poskusite znova.');
        }

        $post_id = isset($_POST['item_id']) ? (int) $_POST['item_id'] : 0;
        $post = $this->get_editable_marketplace_item($post_id);

        if (!$post) {
            $this->redirect_with_message('error', 'Izbranega oglasa ni mogoče posodobiti.');
        }

        update_post_meta($post->ID, self::META_ITEM_STATE, 'sold');

        $this->redirect_with_message('success', 'Oglas je označen kot prodan.');
    }

    private function handle_marketplace_contact_seller()
    {
        if (!$this->nonce_ok('sdp_marketplace_contact_nonce')) {
            $this->redirect_with_message('error', 'Varnostno preverjanje ni uspelo. Poskusite znova.');
        }

        $post_id = isset($_POST['item_id']) ? (int) $_POST['item_id'] : 0;
        $buyer_name = isset($_POST['buyer_name']) ? sanitize_text_field(wp_unslash($_POST['buyer_name'])) : '';
        $buyer_email = isset($_POST['buyer_email']) ? sanitize_email(wp_unslash($_POST['buyer_email'])) : '';
        $message = isset($_POST['buyer_message']) ? sanitize_textarea_field(wp_unslash($_POST['buyer_message'])) : '';

        if (!$post_id || $buyer_name === '' || $buyer_email === '' || $message === '') {
            $this->redirect_with_message('error', 'Prosimo, izpolnite vse podatke za kontakt.');
        }

        if (!is_email($buyer_email)) {
            $this->redirect_with_message('error', 'Vnesite veljaven e-poštni naslov.');
        }

        $post = get_post($post_id);

        if (!$post || $post->post_type !== self::MARKETPLACE_POST_TYPE || $post->post_status !== 'publish') {
            $this->redirect_with_message('error', 'Izbran oglas ni več na voljo.');
        }

        $item_state = get_post_meta($post->ID, self::META_ITEM_STATE, true);

        if ($item_state === 'sold') {
            $this->redirect_with_message('error', 'Ta oglas je že označen kot prodan.');
        }

        $seller = get_user_by('id', (int) $post->post_author);

        if (!$seller || !is_email($seller->user_email)) {
            $this->redirect_with_message('error', 'Prodajalca ni mogoče kontaktirati.');
        }

        $subject = 'Novo povpraševanje za oglas: ' . $post->post_title;
        $from_name = wp_specialchars_decode(get_bloginfo('name'), ENT_QUOTES);
        $from_email = sanitize_email(get_option('admin_email'));

        if (!$from_email || !is_email($from_email)) {
            $from_email = 'noreply@' . wp_parse_url(home_url('/'), PHP_URL_HOST);
        }

        $html_message = $this->build_branded_email_html(
            'Novo povpraševanje za oglas',
            '<p style="margin:0 0 14px;font-size:16px;line-height:1.55;">Pozdravljeni,</p>' .
            '<p style="margin:0 0 14px;font-size:16px;line-height:1.55;">prejeli ste novo povpraševanje za vaš oglas:</p>' .
            '<p style="margin:0 0 18px;font-size:18px;line-height:1.5;font-weight:700;color:#183748;">' . esc_html($post->post_title) . '</p>' .
            '<p style="margin:0 0 8px;font-size:15px;line-height:1.55;"><strong>Ime kupca:</strong> ' . esc_html($buyer_name) . '</p>' .
            '<p style="margin:0 0 8px;font-size:15px;line-height:1.55;"><strong>E-pošta kupca:</strong> <a href="mailto:' . esc_attr($buyer_email) . '" style="color:#356f8c;">' . esc_html($buyer_email) . '</a></p>' .
            '<p style="margin:18px 0 8px;font-size:15px;line-height:1.55;"><strong>Sporočilo:</strong></p>' .
            '<div style="margin:0;padding:14px 16px;border-left:4px solid #c69a47;background:#f6fbff;border-radius:10px;color:#183748;white-space:pre-line;">' . esc_html($message) . '</div>'
        );

        $text_message = "Pozdravljeni,\n\n" .
            "prejeli ste novo povpraševanje za vaš oglas:\n" .
            $post->post_title . "\n\n" .
            "Ime kupca: {$buyer_name}\n" .
            "E-pošta kupca: {$buyer_email}\n\n" .
            "Sporočilo:\n{$message}\n";

        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'Reply-To: ' . $buyer_name . ' <' . $buyer_email . '>',
            'From: ' . $from_name . ' <' . $from_email . '>',
        );

        $sent = wp_mail($seller->user_email, $subject, $html_message, $headers);

        if (!$sent) {
            $fallback_headers = array(
                'Reply-To: ' . $buyer_name . ' <' . $buyer_email . '>',
                'From: ' . $from_name . ' <' . $from_email . '>',
            );
            $sent = wp_mail($seller->user_email, $subject, $text_message, $fallback_headers);
        }

        if (!$sent) {
            $this->redirect_with_message('error', 'Pošiljanje sporočila ni uspelo. Poskusite znova.');
        }

        $this->redirect_with_message('success', 'Sporočilo je bilo uspešno poslano prodajalcu.');
    }

    private function get_marketplace_condition_label($condition)
    {
        $labels = array(
            'novo' => 'Novo',
            'odlicno' => 'Odlično',
            'dobro' => 'Dobro',
            'solidno' => 'Solidno',
        );

        return isset($labels[$condition]) ? $labels[$condition] : 'Ni navedeno';
    }

    private function get_marketplace_item_state_label($state)
    {
        if ($state === 'sold') {
            return 'Prodano';
        }

        return 'Aktivno';
    }

    private function get_dashboard_url()
    {
        return home_url('/uporabniski-portal/');
    }

    private function nonce_ok($name)
    {
        return isset($_POST['_wpnonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), $name);
    }

    private function redirect_with_message($type, $message)
    {
        $url = isset($_POST['sdp_return_url']) ? esc_url_raw(wp_unslash($_POST['sdp_return_url'])) : '';

        if (!empty($url)) {
            $url = wp_validate_redirect($url, home_url('/'));
        }

        if (empty($url)) {
            $url = wp_get_referer();
        }

        if (!$url) {
            $url = home_url('/');
        }

        $url = add_query_arg(
            array(
                'sdp_notice_type' => rawurlencode($type),
                'sdp_notice_message' => rawurlencode($message),
            ),
            $url
        );

        wp_safe_redirect($url);
        exit;
    }

    private function handle_registration($role)
    {
        if (!$this->nonce_ok('sdp_register_nonce')) {
            $this->redirect_with_message('error', 'Varnostno preverjanje ni uspelo. Poskusite znova.');
        }

        $first_name = isset($_POST['first_name']) ? sanitize_text_field(wp_unslash($_POST['first_name'])) : '';
        $last_name = isset($_POST['last_name']) ? sanitize_text_field(wp_unslash($_POST['last_name'])) : '';
        $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
        $phone = isset($_POST['phone']) ? sanitize_text_field(wp_unslash($_POST['phone'])) : '';
        $requested_username = isset($_POST['username']) ? sanitize_user(wp_unslash($_POST['username']), true) : '';
        $password = isset($_POST['password']) ? wp_unslash($_POST['password']) : '';
        $password_confirm = isset($_POST['password_confirm']) ? wp_unslash($_POST['password_confirm']) : '';
        $terms = isset($_POST['terms']) ? (int) $_POST['terms'] : 0;
        $privacy = isset($_POST['privacy']) ? (int) $_POST['privacy'] : 0;

        $username_candidates = $this->build_username_candidates($first_name, $last_name);
        $username = '';

        if (!empty($requested_username)) {
            if (!in_array($requested_username, $username_candidates, true)) {
                $this->redirect_with_message('error', 'Izberite predlagano uporabniško ime.');
            }

            $username = $requested_username;
        }

        if (empty($username)) {
            $username = $this->pick_first_available_username($username_candidates);
        }

        if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
            $this->redirect_with_message('error', 'Prosimo, izpolnite vsa obvezna polja.');
        }

        if (empty($username)) {
            $this->redirect_with_message('error', 'Za ta račun ni mogoče pripraviti prostega uporabniškega imena. Poskusite znova.');
        }

        if (!is_email($email)) {
            $this->redirect_with_message('error', 'Vnesite veljaven e-poštni naslov.');
        }

        if ($password !== $password_confirm) {
            $this->redirect_with_message('error', 'Gesli se ne ujemata.');
        }

        if (strlen($password) < 8) {
            $this->redirect_with_message('error', 'Geslo mora vsebovati najmanj 8 znakov.');
        }

        if (!$terms || !$privacy) {
            $this->redirect_with_message('error', 'Za nadaljevanje morate potrditi pogoje in zasebnost.');
        }

        if (username_exists($username)) {
            $this->redirect_with_message('error', 'To uporabniško ime je že zasedeno.');
        }

        if (email_exists($email)) {
            $this->redirect_with_message('error', 'Ta e-poštni naslov je že v uporabi.');
        }

        $user_id = wp_create_user($username, $password, $email);

        if (is_wp_error($user_id)) {
            $this->redirect_with_message('error', 'Računa ni bilo mogoče ustvariti. Poskusite ponovno.');
        }

        $user = new WP_User($user_id);
        $user->set_role($role);

        update_user_meta($user_id, 'first_name', $first_name);
        update_user_meta($user_id, 'last_name', $last_name);
        update_user_meta($user_id, self::META_STATUS, 'pending');
        update_user_meta($user_id, self::META_ROLE_TYPE, $role);
        update_user_meta($user_id, 'sdp_phone', $phone);
        update_user_meta($user_id, 'sdp_privacy_accepted_at', current_time('mysql'));
        update_user_meta($user_id, 'sdp_terms_accepted_at', current_time('mysql'));

        if ($role === self::ATHLETE_ROLE) {
            $birth_date = isset($_POST['birth_date']) ? sanitize_text_field(wp_unslash($_POST['birth_date'])) : '';
            $gender = isset($_POST['gender']) ? sanitize_text_field(wp_unslash($_POST['gender'])) : '';

            update_user_meta($user_id, 'sdp_birth_date', $birth_date);
            update_user_meta($user_id, 'sdp_gender', $gender);
        }

        if ($role === self::PARENT_ROLE) {
            $child_info = isset($_POST['child_info']) ? sanitize_textarea_field(wp_unslash($_POST['child_info'])) : '';
            $notes = isset($_POST['notes']) ? sanitize_textarea_field(wp_unslash($_POST['notes'])) : '';

            update_user_meta($user_id, self::META_CHILD_INFO, $child_info);
            update_user_meta($user_id, 'sdp_notes', $notes);
        }

        $this->send_user_registration_confirmation($user_id, $role);
        $this->send_admin_registration_notice($user_id, $role);

        $this->redirect_with_message('success', 'Registracija je uspela. Zdaj se lahko prijavite spodaj.');
    }

    private function build_username_candidates($first_name, $last_name)
    {
        $first_raw = (string) $first_name;
        $last_raw = (string) $last_name;

        if (function_exists('mb_strtolower')) {
            $first_raw = mb_strtolower($first_raw, 'UTF-8');
            $last_raw = mb_strtolower($last_raw, 'UTF-8');
        } else {
            $first_raw = strtolower($first_raw);
            $last_raw = strtolower($last_raw);
        }

        $first = sanitize_user(remove_accents($first_raw), true);
        $last = sanitize_user(remove_accents($last_raw), true);

        if ($first === '' || $last === '') {
            return array();
        }

        $first_initial = substr($first, 0, 1);
        $last_initial = substr($last, 0, 1);

        $patterns = array(
            $first . '.' . $last,
            $first . $last,
            $first_initial . $last,
            $first . $last_initial,
            $last . '.' . $first,
            $first . '-' . $last,
        );

        $candidates = array();

        foreach ($patterns as $pattern) {
            $sanitized = sanitize_user($pattern, true);

            if ($sanitized === '') {
                continue;
            }

            $candidates[] = $sanitized;
        }

        $candidates = array_values(array_unique($candidates));

        $extended_candidates = $candidates;

        foreach ($candidates as $candidate) {
            for ($i = 1; $i <= 20; $i++) {
                $extended_candidates[] = $candidate . $i;
            }
        }

        return array_values(array_unique($extended_candidates));
    }

    private function pick_first_available_username($candidates)
    {
        foreach ($candidates as $candidate) {
            if (!username_exists($candidate)) {
                return $candidate;
            }
        }

        return '';
    }

    private function handle_login()
    {
        if (!$this->nonce_ok('sdp_login_nonce')) {
            $this->redirect_with_message('error', 'Varnostno preverjanje ni uspelo.');
        }

        $login = isset($_POST['login']) ? sanitize_text_field(wp_unslash($_POST['login'])) : '';
        $password = isset($_POST['password']) ? wp_unslash($_POST['password']) : '';
        $remember = !empty($_POST['remember']);

        if (empty($login) || empty($password)) {
            $this->redirect_with_message('error', 'Vnesite uporabniško ime/e-pošto in geslo.');
        }

        $creds = array(
            'user_login' => $login,
            'user_password' => $password,
            'remember' => $remember,
        );

        $user = wp_signon($creds, is_ssl());

        if (is_wp_error($user)) {
            $this->redirect_with_message('error', 'Prijava ni uspela. Preverite podatke in poskusite znova.');
        }

        wp_safe_redirect($this->get_dashboard_url());
        exit;
    }

    private function handle_profile_update()
    {
        if (!is_user_logged_in()) {
            $this->redirect_with_message('error', 'Za urejanje profila se morate prijaviti.');
        }

        if (!$this->nonce_ok('sdp_profile_nonce')) {
            $this->redirect_with_message('error', 'Varnostno preverjanje ni uspelo. Poskusite znova.');
        }

        $user_id = get_current_user_id();
        $first_name = isset($_POST['first_name']) ? sanitize_text_field(wp_unslash($_POST['first_name'])) : '';
        $last_name = isset($_POST['last_name']) ? sanitize_text_field(wp_unslash($_POST['last_name'])) : '';
        $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
        $phone = isset($_POST['phone']) ? sanitize_text_field(wp_unslash($_POST['phone'])) : '';

        if ($first_name === '' || $last_name === '' || $email === '') {
            $this->redirect_with_message('error', 'Ime, priimek in e-poštni naslov so obvezni.');
        }

        if (!is_email($email)) {
            $this->redirect_with_message('error', 'Vnesite veljaven e-poštni naslov.');
        }

        $existing_email_user_id = email_exists($email);

        if ($existing_email_user_id && (int) $existing_email_user_id !== (int) $user_id) {
            $this->redirect_with_message('error', 'Ta e-poštni naslov je že v uporabi.');
        }

        $update_result = wp_update_user(
            array(
                'ID' => $user_id,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'display_name' => trim($first_name . ' ' . $last_name),
                'user_email' => $email,
            )
        );

        if (is_wp_error($update_result)) {
            $this->redirect_with_message('error', 'Profila ni bilo mogoče posodobiti. Poskusite ponovno.');
        }

        update_user_meta($user_id, 'sdp_phone', $phone);

        $user = get_user_by('id', $user_id);
        $role = $user instanceof WP_User ? reset($user->roles) : '';

        if ($role === self::ATHLETE_ROLE) {
            $birth_date = isset($_POST['birth_date']) ? sanitize_text_field(wp_unslash($_POST['birth_date'])) : '';
            $gender = isset($_POST['gender']) ? sanitize_text_field(wp_unslash($_POST['gender'])) : '';

            update_user_meta($user_id, 'sdp_birth_date', $birth_date);
            update_user_meta($user_id, 'sdp_gender', $gender);
        }

        if ($role === self::PARENT_ROLE) {
            $child_info = isset($_POST['child_info']) ? sanitize_textarea_field(wp_unslash($_POST['child_info'])) : '';
            $notes = isset($_POST['notes']) ? sanitize_textarea_field(wp_unslash($_POST['notes'])) : '';

            update_user_meta($user_id, self::META_CHILD_INFO, $child_info);
            update_user_meta($user_id, 'sdp_notes', $notes);
        }

        $this->redirect_with_message('success', 'Profil je uspešno posodobljen.');
    }

    private function handle_forgot_password()
    {
        if (!$this->nonce_ok('sdp_forgot_nonce')) {
            $this->redirect_with_message('error', 'Varnostno preverjanje ni uspelo.');
        }

        $user_login = isset($_POST['user_login']) ? sanitize_text_field(wp_unslash($_POST['user_login'])) : '';

        if (empty($user_login)) {
            $this->redirect_with_message('error', 'Vnesite uporabniško ime ali e-poštni naslov.');
        }

        $result = retrieve_password($user_login);

        if (is_wp_error($result)) {
            $this->redirect_with_message('error', 'Pošiljanje povezave ni uspelo. Preverite vnos.');
        }

        $this->redirect_with_message('success', 'Povezava za ponastavitev gesla je bila poslana.');
    }

    private function handle_reset_password()
    {
        if (!$this->nonce_ok('sdp_reset_nonce')) {
            $this->redirect_with_message('error', 'Varnostno preverjanje ni uspelo.');
        }

        $key = isset($_POST['key']) ? sanitize_text_field(wp_unslash($_POST['key'])) : '';
        $login = isset($_POST['login']) ? sanitize_text_field(wp_unslash($_POST['login'])) : '';
        $password = isset($_POST['password']) ? wp_unslash($_POST['password']) : '';
        $password_confirm = isset($_POST['password_confirm']) ? wp_unslash($_POST['password_confirm']) : '';

        if (empty($key) || empty($login)) {
            $this->redirect_with_message('error', 'Povezava za ponastavitev ni veljavna.');
        }

        if (strlen($password) < 8) {
            $this->redirect_with_message('error', 'Geslo mora vsebovati najmanj 8 znakov.');
        }

        if ($password !== $password_confirm) {
            $this->redirect_with_message('error', 'Gesli se ne ujemata.');
        }

        $user = check_password_reset_key($key, $login);

        if (is_wp_error($user)) {
            $this->redirect_with_message('error', 'Povezava za ponastavitev je potekla ali ni veljavna.');
        }

        reset_password($user, $password);

        $this->redirect_with_message('success', 'Geslo je bilo uspešno spremenjeno. Zdaj se lahko prijavite.');
    }

    public function block_pending_users($user, $username, $password)
    {
        if ($user instanceof WP_User) {
            $status = get_user_meta($user->ID, self::META_STATUS, true);

            if ($status === 'pending') {
                return $user;
            }

            if ($status === 'rejected') {
                return new WP_Error('rejected_account', 'Vaša registracija je bila zavrnjena. Za pomoč nas kontaktirajte.');
            }
        }

        return $user;
    }

    public function register_admin_pages()
    {
        add_menu_page(
            'SD Portal',
            'SD Portal',
            'manage_options',
            'sdp-portal-users',
            array($this, 'render_portal_users_page'),
            'dashicons-groups'
        );

        add_submenu_page(
            'sdp-portal-users',
            'Users',
            'Users',
            'manage_options',
            'sdp-portal-users',
            array($this, 'render_portal_users_page')
        );

        add_submenu_page(
            'sdp-portal-users',
            'Settings',
            'Settings',
            'manage_options',
            'sdp-portal-settings',
            array($this, 'render_portal_settings_page')
        );
    }

    public function render_portal_users_page()
    {
        if (!current_user_can('list_users')) {
            wp_die('You do not have permission to access this page.');
        }

        $users_url = add_query_arg('sdp_portal', '1', admin_url('users.php'));
        wp_safe_redirect($users_url);
        exit;
    }

    public function filter_users_admin_lists($query)
    {
        if (!is_admin() || !($query instanceof WP_User_Query)) {
            return;
        }

        global $pagenow;

        if ($pagenow !== 'users.php') {
            return;
        }

        $portal_users_view = isset($_GET['sdp_portal']) && sanitize_text_field(wp_unslash($_GET['sdp_portal'])) === '1';

        if ($portal_users_view) {
            $query->set('role__in', array(self::PARENT_ROLE, self::ATHLETE_ROLE));
            return;
        }

        $excluded_roles = $query->get('role__not_in');
        $excluded_roles = is_array($excluded_roles) ? $excluded_roles : array();
        $excluded_roles[] = self::PARENT_ROLE;
        $excluded_roles[] = self::ATHLETE_ROLE;

        $query->set('role__not_in', array_values(array_unique($excluded_roles)));
    }

    public function render_portal_settings_page()
    {
        if (!current_user_can('manage_options')) {
            wp_die('You do not have permission to access this page.');
        }

        $admin_email = $this->get_admin_notification_email();
        $settings_updated = isset($_GET['sdp_settings_updated']) ? sanitize_text_field(wp_unslash($_GET['sdp_settings_updated'])) : '';
        $settings_error = isset($_GET['sdp_settings_error']) ? sanitize_text_field(wp_unslash($_GET['sdp_settings_error'])) : '';

        echo '<div class="wrap">';
        echo '<h1>SD Portal Settings</h1>';

        if ($settings_updated === '1') {
            echo '<div class="notice notice-success is-dismissible"><p>Settings saved.</p></div>';
        }

        if ($settings_error === 'invalid_email') {
            echo '<div class="notice notice-error"><p>Please enter a valid admin email address.</p></div>';
        }

        echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
        wp_nonce_field('sdp_portal_settings');
        echo '<input type="hidden" name="action" value="sdp_portal_save_settings" />';

        echo '<table class="form-table" role="presentation">';
        echo '<tr>';
        echo '<th scope="row"><label for="sdp_admin_notification_email">Admin Email</label></th>';
        echo '<td>';
        echo '<input name="sdp_admin_notification_email" id="sdp_admin_notification_email" type="email" class="regular-text" value="' . esc_attr($admin_email) . '" required />';
        echo '<p class="description">Notification emails about new registrations will be sent to this address.</p>';
        echo '</td>';
        echo '</tr>';
        echo '</table>';

        submit_button('Save Settings');

        echo '</form>';

        echo '<hr />';
        echo '<h2>Shortcodes</h2>';
        echo '<p>Use these shortcodes on the appropriate WordPress pages.</p>';

        echo '<table class="widefat striped" style="max-width: 1100px;">';
        echo '<thead><tr><th>Feature</th><th>Shortcode</th><th>Recommended Page</th></tr></thead><tbody>';
        echo '<tr><td>Athlete Registration</td><td><code>[sdp_register_athlete]</code></td><td>Registracija sportnika</td></tr>';
        echo '<tr><td>Parent Registration</td><td><code>[sdp_register_parent]</code></td><td>Registracija starsa</td></tr>';
        echo '<tr><td>Login</td><td><code>[sdp_login]</code></td><td>Prijava</td></tr>';
        echo '<tr><td>Forgot Password</td><td><code>[sdp_forgot_password]</code></td><td>Pozabljeno geslo</td></tr>';
        echo '<tr><td>Reset Password</td><td><code>[sdp_reset_password]</code></td><td>Ponastavitev gesla</td></tr>';
        echo '<tr><td>User Dashboard</td><td><code>[sdp_dashboard]</code></td><td>Uporabniski portal</td></tr>';
        echo '<tr><td>Marketplace Sell + My Listings</td><td><code>[sdp_marketplace_sell]</code></td><td>Prodaj rabljeno opremo</td></tr>';
        echo '<tr><td>Marketplace Browse</td><td><code>[sdp_marketplace]</code></td><td>Rabljena oprema</td></tr>';
        echo '<tr><td>Club Shop</td><td><code>[sdp_club_shop]</code></td><td>Klubska oprema</td></tr>';
        echo '<tr><td>Club Orders</td><td><code>[sdp_club_orders]</code></td><td>Moja narocila</td></tr>';
        echo '</tbody></table>';

        echo '</div>';
    }

    public function handle_admin_settings_save()
    {
        if (!current_user_can('manage_options')) {
            wp_die('Forbidden');
        }

        check_admin_referer('sdp_portal_settings');

        $admin_email = isset($_POST['sdp_admin_notification_email']) ? sanitize_email(wp_unslash($_POST['sdp_admin_notification_email'])) : '';
        $redirect_url = admin_url('admin.php?page=sdp-portal-settings');

        if (!$admin_email || !is_email($admin_email)) {
            wp_safe_redirect(add_query_arg('sdp_settings_error', 'invalid_email', $redirect_url));
            exit;
        }

        update_option(self::OPTION_ADMIN_NOTIFICATION_EMAIL, $admin_email);

        wp_safe_redirect(add_query_arg('sdp_settings_updated', '1', $redirect_url));
        exit;
    }

    public function register_club_shop_meta_boxes()
    {
        add_meta_box(
            'sdp-club-product-details',
            'Product Details',
            array($this, 'render_club_product_meta_box'),
            self::CLUB_PRODUCT_POST_TYPE,
            'normal',
            'default'
        );

        add_meta_box(
            'sdp-club-order-details',
            'Order Details',
            array($this, 'render_club_order_meta_box'),
            self::CLUB_ORDER_POST_TYPE,
            'normal',
            'default'
        );

        add_meta_box(
            'sdp-training-doc-details',
            'Training Document Details',
            array($this, 'render_training_doc_meta_box'),
            self::TRAINING_DOC_POST_TYPE,
            'normal',
            'default'
        );
    }

    public function render_club_product_meta_box($post)
    {
        wp_nonce_field('sdp_club_product_meta', 'sdp_club_product_meta_nonce');

        $price = $this->get_club_product_price($post->ID);
        $sizes = (string) get_post_meta($post->ID, self::META_CLUB_PRODUCT_SIZES, true);
        $is_active = $this->is_club_product_active($post->ID);

        echo '<p><label for="sdp_club_product_price"><strong>Price (EUR)</strong></label></p>';
        echo '<input id="sdp_club_product_price" name="sdp_club_product_price" type="number" min="0" step="0.01" value="' . esc_attr($price) . '" style="width:180px;" />';
        echo '<p class="description">Displayed on the frontend club shop card.</p>';

        echo '<p style="margin-top:16px;"><label for="sdp_club_product_sizes"><strong>Available Sizes</strong></label></p>';
        echo '<input id="sdp_club_product_sizes" name="sdp_club_product_sizes" type="text" value="' . esc_attr($sizes) . '" class="widefat" />';
        echo '<p class="description">Optional. Enter comma-separated sizes, for example: 128, 140, XS, S, M, L.</p>';

        echo '<p style="margin-top:16px;">';
        echo '<label><input name="sdp_club_product_active" type="checkbox" value="yes" ' . checked($is_active, true, false) . ' /> Active product</label>';
        echo '</p>';
    }

    public function render_club_order_meta_box($post)
    {
        wp_nonce_field('sdp_club_order_meta', 'sdp_club_order_meta_nonce');

        $items = $this->get_club_order_items($post->ID);
        $note = (string) get_post_meta($post->ID, self::META_CLUB_ORDER_NOTE, true);
        $child_name = (string) get_post_meta($post->ID, self::META_CLUB_ORDER_CHILD_NAME, true);
        $customer_name = (string) get_post_meta($post->ID, self::META_CLUB_ORDER_CUSTOMER_NAME, true);
        $customer_email = (string) get_post_meta($post->ID, self::META_CLUB_ORDER_CUSTOMER_EMAIL, true);
        $customer_phone = (string) get_post_meta($post->ID, self::META_CLUB_ORDER_CUSTOMER_PHONE, true);
        $status = (string) get_post_meta($post->ID, self::META_CLUB_ORDER_STATUS, true);
        $admin_note = (string) get_post_meta($post->ID, self::META_CLUB_ORDER_ADMIN_NOTE, true);
        $total = $this->get_club_order_total_from_items($items);

        echo '<table class="form-table" role="presentation">';
        echo '<tr><th scope="row">Customer</th><td>' . esc_html($customer_name !== '' ? $customer_name : 'Unknown') . '</td></tr>';
        echo '<tr><th scope="row">For Child/Athlete</th><td>' . esc_html($child_name !== '' ? $child_name : '-') . '</td></tr>';
        echo '<tr><th scope="row">Email</th><td><a href="mailto:' . esc_attr($customer_email) . '">' . esc_html($customer_email) . '</a></td></tr>';
        echo '<tr><th scope="row">Phone</th><td>' . esc_html($customer_phone !== '' ? $customer_phone : '-') . '</td></tr>';
        echo '<tr><th scope="row">Items</th><td>' . $this->build_club_order_items_html($items) . '</td></tr>';
        echo '<tr><th scope="row">Total Quantity</th><td>' . esc_html((string) $this->get_club_order_total_quantity_from_items($items)) . '</td></tr>';
        echo '<tr><th scope="row">Total</th><td>' . esc_html($this->format_price($total)) . '</td></tr>';
        echo '<tr><th scope="row">Customer Note</th><td>' . nl2br(esc_html($note !== '' ? $note : '-')) . '</td></tr>';
        echo '<tr><th scope="row"><label for="sdp_club_order_status">Status</label></th><td>';
        echo '<select name="sdp_club_order_status" id="sdp_club_order_status">';
        foreach ($this->get_club_order_status_labels() as $status_key => $label) {
            echo '<option value="' . esc_attr($status_key) . '" ' . selected($status, $status_key, false) . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
        echo '</td></tr>';
        echo '<tr><th scope="row"><label for="sdp_club_order_admin_note">Admin Note</label></th><td>';
        echo '<textarea name="sdp_club_order_admin_note" id="sdp_club_order_admin_note" rows="4" class="large-text">' . esc_textarea($admin_note) . '</textarea>';
        echo '</td></tr>';
        echo '</table>';
    }

    public function render_training_doc_meta_box($post)
    {
        wp_nonce_field('sdp_training_doc_meta', 'sdp_training_doc_meta_nonce');

        $athlete_id = (int) get_post_meta($post->ID, self::META_TRAINING_DOC_ATHLETE_ID, true);
        $file_url = (string) get_post_meta($post->ID, self::META_TRAINING_DOC_FILE_URL, true);
        $description = (string) get_post_meta($post->ID, self::META_TRAINING_DOC_DESCRIPTION, true);
        $athletes = get_users(
            array(
                'role' => self::ATHLETE_ROLE,
                'number' => 1000,
                'orderby' => 'display_name',
                'order' => 'ASC',
            )
        );

        echo '<table class="form-table" role="presentation">';
        echo '<tr><th scope="row"><label for="sdp_training_doc_athlete_id">Athlete</label></th><td>';
        echo '<select name="sdp_training_doc_athlete_id" id="sdp_training_doc_athlete_id" required>';
        echo '<option value="">Select athlete</option>';

        foreach ($athletes as $athlete) {
            $name = trim((string) get_user_meta($athlete->ID, 'first_name', true) . ' ' . (string) get_user_meta($athlete->ID, 'last_name', true));
            $name = $name !== '' ? $name : $athlete->display_name;
            echo '<option value="' . esc_attr((string) $athlete->ID) . '" ' . selected($athlete_id, $athlete->ID, false) . '>' . esc_html($name . ' (' . $athlete->user_email . ')') . '</option>';
        }

        echo '</select>';
        echo '<p class="description">Choose which athlete should see this document on the portal dashboard.</p>';
        echo '</td></tr>';

        echo '<tr><th scope="row"><label for="sdp_training_doc_file_url">Document file</label></th><td>';
        echo '<div class="sdp-training-doc-file-field">';
        echo '<input type="url" name="sdp_training_doc_file_url" id="sdp_training_doc_file_url" class="regular-text sdp-training-doc-file-url" value="' . esc_attr($file_url) . '" placeholder="https://.../training-plan.pdf" required /> ';
        echo '<button type="button" class="button sdp-training-doc-upload">Select from Media</button>';
        echo '</div>';
        echo '<p class="description">PDF is recommended. You can also use DOCX, image, or similar training files.</p>';

        if ($file_url !== '') {
            echo '<p><a href="' . esc_url($file_url) . '" target="_blank" rel="noopener noreferrer">Open current file</a></p>';
        }

        echo '</td></tr>';

        echo '<tr><th scope="row"><label for="sdp_training_doc_description">Short description</label></th><td>';
        echo '<textarea name="sdp_training_doc_description" id="sdp_training_doc_description" rows="4" class="large-text" placeholder="Optional short summary shown to the athlete.">' . esc_textarea($description) . '</textarea>';
        echo '</td></tr>';
        echo '</table>';
    }

    public function save_club_product_meta($post_id)
    {
        if (!isset($_POST['sdp_club_product_meta_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['sdp_club_product_meta_nonce'])), 'sdp_club_product_meta')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $price_raw = isset($_POST['sdp_club_product_price']) ? sanitize_text_field(wp_unslash($_POST['sdp_club_product_price'])) : '';
        $sizes = isset($_POST['sdp_club_product_sizes']) ? sanitize_text_field(wp_unslash($_POST['sdp_club_product_sizes'])) : '';
        $is_active = isset($_POST['sdp_club_product_active']) && sanitize_text_field(wp_unslash($_POST['sdp_club_product_active'])) === 'yes' ? 'yes' : 'no';

        if ($price_raw !== '' && is_numeric(str_replace(',', '.', $price_raw))) {
            $price = number_format((float) str_replace(',', '.', $price_raw), 2, '.', '');
            update_post_meta($post_id, self::META_CLUB_PRODUCT_PRICE, $price);
        } else {
            delete_post_meta($post_id, self::META_CLUB_PRODUCT_PRICE);
        }

        update_post_meta($post_id, self::META_CLUB_PRODUCT_SIZES, $sizes);
        update_post_meta($post_id, self::META_CLUB_PRODUCT_ACTIVE, $is_active);
    }

    public function save_club_order_meta($post_id)
    {
        if (!isset($_POST['sdp_club_order_meta_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['sdp_club_order_meta_nonce'])), 'sdp_club_order_meta')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $status = isset($_POST['sdp_club_order_status']) ? sanitize_key(wp_unslash($_POST['sdp_club_order_status'])) : 'new';
        $admin_note = isset($_POST['sdp_club_order_admin_note']) ? sanitize_textarea_field(wp_unslash($_POST['sdp_club_order_admin_note'])) : '';
        $labels = $this->get_club_order_status_labels();

        if (!isset($labels[$status])) {
            $status = 'new';
        }

        update_post_meta($post_id, self::META_CLUB_ORDER_STATUS, $status);
        update_post_meta($post_id, self::META_CLUB_ORDER_ADMIN_NOTE, $admin_note);
    }

    public function save_training_doc_meta($post_id)
    {
        if (!isset($_POST['sdp_training_doc_meta_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['sdp_training_doc_meta_nonce'])), 'sdp_training_doc_meta')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $athlete_id = isset($_POST['sdp_training_doc_athlete_id']) ? (int) $_POST['sdp_training_doc_athlete_id'] : 0;
        $file_url = isset($_POST['sdp_training_doc_file_url']) ? esc_url_raw(wp_unslash($_POST['sdp_training_doc_file_url'])) : '';
        $description = isset($_POST['sdp_training_doc_description']) ? sanitize_textarea_field(wp_unslash($_POST['sdp_training_doc_description'])) : '';
        $athlete = $athlete_id > 0 ? get_user_by('id', $athlete_id) : false;

        if ($athlete instanceof WP_User && in_array(self::ATHLETE_ROLE, $athlete->roles, true)) {
            update_post_meta($post_id, self::META_TRAINING_DOC_ATHLETE_ID, $athlete_id);
        } else {
            delete_post_meta($post_id, self::META_TRAINING_DOC_ATHLETE_ID);
        }

        if ($file_url !== '') {
            update_post_meta($post_id, self::META_TRAINING_DOC_FILE_URL, $file_url);
        } else {
            delete_post_meta($post_id, self::META_TRAINING_DOC_FILE_URL);
        }

        if ($description !== '') {
            update_post_meta($post_id, self::META_TRAINING_DOC_DESCRIPTION, $description);
        } else {
            delete_post_meta($post_id, self::META_TRAINING_DOC_DESCRIPTION);
        }
    }

    public function set_club_product_columns($columns)
    {
        $columns['sdp_club_product_price'] = 'Price';
        $columns['sdp_club_product_sizes'] = 'Sizes';
        $columns['sdp_club_product_active'] = 'Active';

        return $columns;
    }

    public function render_club_product_column($column, $post_id)
    {
        if ($column === 'sdp_club_product_price') {
            echo esc_html($this->format_price($this->get_club_product_price($post_id)));
            return;
        }

        if ($column === 'sdp_club_product_sizes') {
            $sizes = $this->get_club_product_sizes($post_id);
            echo esc_html(!empty($sizes) ? implode(', ', $sizes) : '-');
            return;
        }

        if ($column === 'sdp_club_product_active') {
            echo esc_html($this->is_club_product_active($post_id) ? 'Yes' : 'No');
        }
    }

    public function set_club_order_columns($columns)
    {
        $columns['sdp_club_order_customer'] = 'Customer';
        $columns['sdp_club_order_product'] = 'Items';
        $columns['sdp_club_order_quantity'] = 'Quantity';
        $columns['sdp_club_order_total'] = 'Total';
        $columns['sdp_club_order_status'] = 'Status';

        return $columns;
    }

    public function render_club_order_column($column, $post_id)
    {
        $items = $this->get_club_order_items($post_id);

        if ($column === 'sdp_club_order_customer') {
            $customer_name = (string) get_post_meta($post_id, self::META_CLUB_ORDER_CUSTOMER_NAME, true);
            $child_name = (string) get_post_meta($post_id, self::META_CLUB_ORDER_CHILD_NAME, true);
            echo esc_html($customer_name);

            if ($child_name !== '') {
                echo '<br><span style="color:#50575e;">For: ' . esc_html($child_name) . '</span>';
            }
            return;
        }

        if ($column === 'sdp_club_order_product') {
            echo esc_html($this->get_club_order_items_summary_from_items($items));
            return;
        }

        if ($column === 'sdp_club_order_quantity') {
            echo esc_html((string) $this->get_club_order_total_quantity_from_items($items));
            return;
        }

        if ($column === 'sdp_club_order_total') {
            echo esc_html($this->format_price($this->get_club_order_total_from_items($items)));
            return;
        }

        if ($column === 'sdp_club_order_status') {
            $status = (string) get_post_meta($post_id, self::META_CLUB_ORDER_STATUS, true);
            echo esc_html($this->get_club_order_status_label($status));
        }
    }

    public function set_training_doc_columns($columns)
    {
        $columns['sdp_training_doc_athlete'] = 'Athlete';
        $columns['sdp_training_doc_file'] = 'Document';

        return $columns;
    }

    public function render_training_doc_column($column, $post_id)
    {
        if ($column === 'sdp_training_doc_athlete') {
            $athlete_id = (int) get_post_meta($post_id, self::META_TRAINING_DOC_ATHLETE_ID, true);
            $athlete = $athlete_id > 0 ? get_user_by('id', $athlete_id) : false;

            if (!$athlete instanceof WP_User) {
                echo '-';
                return;
            }

            $name = trim((string) get_user_meta($athlete->ID, 'first_name', true) . ' ' . (string) get_user_meta($athlete->ID, 'last_name', true));
            $name = $name !== '' ? $name : $athlete->display_name;
            echo esc_html($name);
            return;
        }

        if ($column === 'sdp_training_doc_file') {
            $file_url = (string) get_post_meta($post_id, self::META_TRAINING_DOC_FILE_URL, true);

            if ($file_url === '') {
                echo '-';
                return;
            }

            echo '<a href="' . esc_url($file_url) . '" target="_blank" rel="noopener noreferrer">Open file</a>';
        }
    }

    private function get_admin_notification_email()
    {
        $admin_email = get_option(self::OPTION_ADMIN_NOTIFICATION_EMAIL, '');

        if ($admin_email && is_email($admin_email)) {
            return $admin_email;
        }

        return (string) get_option('admin_email');
    }

    public function render_pending_accounts_page()
    {
        if (!current_user_can('manage_options')) {
            wp_die('You do not have permission to access this page.');
        }

        $pending_users = get_users(
            array(
                'meta_key' => self::META_STATUS,
                'meta_value' => 'pending',
                'number' => 200,
            )
        );

        echo '<div class="wrap">';
        echo '<h1>Pending Accounts</h1>';

        if (isset($_GET['updated'])) {
            echo '<div class="notice notice-success"><p>Account status updated.</p></div>';
        }

        if (empty($pending_users)) {
            echo '<p>No pending accounts.</p>';
            echo '</div>';
            return;
        }

        echo '<table class="widefat striped">';
        echo '<thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Actions</th></tr></thead><tbody>';

        foreach ($pending_users as $pending_user) {
            $role = get_user_meta($pending_user->ID, self::META_ROLE_TYPE, true);
            $first_name = get_user_meta($pending_user->ID, 'first_name', true);
            $last_name = get_user_meta($pending_user->ID, 'last_name', true);
            $display_name = trim($first_name . ' ' . $last_name);

            $approve_url = wp_nonce_url(
                admin_url('admin-post.php?action=sdp_account_action&mode=approve&user_id=' . (int) $pending_user->ID),
                'sdp_account_action'
            );

            $reject_url = wp_nonce_url(
                admin_url('admin-post.php?action=sdp_account_action&mode=reject&user_id=' . (int) $pending_user->ID),
                'sdp_account_action'
            );

            echo '<tr>';
            echo '<td>' . esc_html((string) $pending_user->ID) . '</td>';
            echo '<td>' . esc_html($display_name ?: $pending_user->display_name) . '</td>';
            echo '<td>' . esc_html($pending_user->user_email) . '</td>';
            echo '<td>' . esc_html($role) . '</td>';
            echo '<td>';
            echo '<a class="button button-primary" href="' . esc_url($approve_url) . '">Approve</a> ';
            echo '<a class="button" href="' . esc_url($reject_url) . '">Reject</a>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
        echo '</div>';
    }

    public function handle_admin_account_action()
    {
        if (!current_user_can('manage_options')) {
            wp_die('Forbidden');
        }

        check_admin_referer('sdp_account_action');

        $mode = isset($_GET['mode']) ? sanitize_key(wp_unslash($_GET['mode'])) : '';
        $user_id = isset($_GET['user_id']) ? (int) $_GET['user_id'] : 0;

        if (!$user_id || !in_array($mode, array('approve', 'reject'), true)) {
            wp_safe_redirect(admin_url('admin.php?page=sdp-accounts'));
            exit;
        }

        update_user_meta($user_id, self::META_STATUS, $mode === 'approve' ? 'approved' : 'rejected');

        wp_safe_redirect(admin_url('admin.php?page=sdp-accounts&updated=1'));
        exit;
    }

    public function render_account_links_page()
    {
        if (!current_user_can('manage_options')) {
            wp_die('You do not have permission to access this page.');
        }

        $parents = get_users(
            array(
                'role' => self::PARENT_ROLE,
                'number' => 500,
            )
        );

        $athletes = get_users(
            array(
                'role' => self::ATHLETE_ROLE,
                'number' => 500,
            )
        );

        echo '<div class="wrap">';
        echo '<h1>Parent-Athlete Links</h1>';
        echo '<p>Create or remove links between parent and athlete accounts. This is admin-only.</p>';

        if (isset($_GET['linked'])) {
            echo '<div class="notice notice-success"><p>Link action completed.</p></div>';
        }

        echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
        wp_nonce_field('sdp_link_action');
        echo '<input type="hidden" name="action" value="sdp_link_action" />';

        echo '<table class="form-table">';

        echo '<tr><th><label for="parent_id">Parent</label></th><td><select name="parent_id" id="parent_id" required>';
        echo '<option value="">Select parent</option>';
        foreach ($parents as $parent) {
            echo '<option value="' . esc_attr((string) $parent->ID) . '">' . esc_html($parent->display_name . ' (' . $parent->user_email . ')') . '</option>';
        }
        echo '</select></td></tr>';

        echo '<tr><th><label for="athlete_id">Athlete</label></th><td><select name="athlete_id" id="athlete_id" required>';
        echo '<option value="">Select athlete</option>';
        foreach ($athletes as $athlete) {
            echo '<option value="' . esc_attr((string) $athlete->ID) . '">' . esc_html($athlete->display_name . ' (' . $athlete->user_email . ')') . '</option>';
        }
        echo '</select></td></tr>';

        echo '<tr><th><label for="mode">Action</label></th><td><select name="mode" id="mode">';
        echo '<option value="link">Link</option>';
        echo '<option value="unlink">Unlink</option>';
        echo '</select></td></tr>';

        echo '</table>';

        submit_button('Save Link Action');

        echo '</form>';
        echo '</div>';
    }

    public function handle_admin_link_action()
    {
        if (!current_user_can('manage_options')) {
            wp_die('Forbidden');
        }

        check_admin_referer('sdp_link_action');

        $parent_id = isset($_POST['parent_id']) ? (int) $_POST['parent_id'] : 0;
        $athlete_id = isset($_POST['athlete_id']) ? (int) $_POST['athlete_id'] : 0;
        $mode = isset($_POST['mode']) ? sanitize_key(wp_unslash($_POST['mode'])) : 'link';

        if (!$parent_id || !$athlete_id || !in_array($mode, array('link', 'unlink'), true)) {
            wp_safe_redirect(admin_url('admin.php?page=sdp-account-links'));
            exit;
        }

        $parent_athlete_ids = get_user_meta($parent_id, self::META_ATHLETE_IDS, true);
        $athlete_parent_ids = get_user_meta($athlete_id, self::META_PARENT_IDS, true);

        $parent_athlete_ids = is_array($parent_athlete_ids) ? $parent_athlete_ids : array();
        $athlete_parent_ids = is_array($athlete_parent_ids) ? $athlete_parent_ids : array();

        if ($mode === 'link') {
            if (!in_array($athlete_id, $parent_athlete_ids, true)) {
                $parent_athlete_ids[] = $athlete_id;
            }

            if (!in_array($parent_id, $athlete_parent_ids, true)) {
                $athlete_parent_ids[] = $parent_id;
            }
        } else {
            $parent_athlete_ids = array_values(array_diff($parent_athlete_ids, array($athlete_id)));
            $athlete_parent_ids = array_values(array_diff($athlete_parent_ids, array($parent_id)));
        }

        update_user_meta($parent_id, self::META_ATHLETE_IDS, $parent_athlete_ids);
        update_user_meta($athlete_id, self::META_PARENT_IDS, $athlete_parent_ids);

        wp_safe_redirect(admin_url('admin.php?page=sdp-account-links&linked=1'));
        exit;
    }

    private function get_notice_html()
    {
        $notice = $this->get_notice_data();
        $type = $notice['type'];
        $message = $notice['message'];

        if (!$type || !$message) {
            return '';
        }

        $class = $type === 'success' ? 'sdp-notice-success' : 'sdp-notice-error';

        $html = '<div class="sdp-notice ' . esc_attr($class) . '">' . esc_html($message) . '</div>';

        return $html;
    }

    private function get_notice_data()
    {
        $type = isset($_GET['sdp_notice_type']) ? sanitize_text_field(wp_unslash($_GET['sdp_notice_type'])) : '';
        $message = isset($_GET['sdp_notice_message']) ? sanitize_text_field(wp_unslash($_GET['sdp_notice_message'])) : '';

        return array(
            'type' => $type,
            'message' => $message,
        );
    }

    private function is_registration_success_notice()
    {
        $notice = $this->get_notice_data();

        return $notice['type'] === 'success' && strpos($notice['message'], 'Registracija je uspela') !== false;
    }

    private function get_current_request_url()
    {
        $request_uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '/';

        return home_url($request_uri);
    }

    private function get_auth_bar_html($current_url = '')
    {
        if ($current_url === '') {
            $current_url = $this->get_current_request_url();
        }

        $login_url = home_url('/uporabniski-portal/prijava/');
        $html = '<div class="sdp-auth-toolbar">';

        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            $display_name = $user->display_name ? $user->display_name : $user->user_login;
            $html .= '<span class="sdp-auth-toolbar-user">Prijavljeni kot: ' . esc_html($display_name) . '</span>';
            $html .= '<a class="sdp-auth-toolbar-link" href="' . esc_url(wp_logout_url($current_url)) . '">Odjava</a>';
        } else {
            $html .= '<span class="sdp-auth-toolbar-user">Niste prijavljeni</span>';
            $html .= '<a class="sdp-auth-toolbar-link" href="' . esc_url($login_url) . '">Prijava</a>';
        }

        $html .= '</div>';

        return $html;
    }

    private function get_marketplace_categories()
    {
        $posts = get_posts(
            array(
                'post_type' => self::MARKETPLACE_POST_TYPE,
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'fields' => 'ids',
            )
        );

        $categories = array();

        foreach ($posts as $post_id) {
            $category = trim((string) get_post_meta($post_id, self::META_ITEM_CATEGORY, true));

            if ($category !== '') {
                $categories[] = $category;
            }
        }

        $categories = array_values(array_unique($categories));
        natcasesort($categories);

        return $categories;
    }

    private function marketplace_item_matches_search($item_id, $search_term)
    {
        $search_term = trim((string) $search_term);

        if ($search_term === '') {
            return true;
        }

        $post = get_post($item_id);

        if (!$post) {
            return false;
        }

        $price = (string) get_post_meta($item_id, self::META_ITEM_PRICE, true);
        $condition = (string) get_post_meta($item_id, self::META_ITEM_CONDITION, true);
        $category = (string) get_post_meta($item_id, self::META_ITEM_CATEGORY, true);
        $size = (string) get_post_meta($item_id, self::META_ITEM_SIZE, true);
        $seller = get_user_by('id', (int) $post->post_author);
        $seller_name = $seller ? trim((string) get_user_meta($seller->ID, 'first_name', true) . ' ' . (string) get_user_meta($seller->ID, 'last_name', true)) : '';
        $seller_name = $seller_name !== '' ? $seller_name : ($seller ? $seller->display_name : '');

        $haystack = implode(
            ' ',
            array(
                (string) $post->post_title,
                (string) $post->post_content,
                $price,
                $this->get_marketplace_condition_label($condition),
                $category,
                $size,
                $seller_name,
            )
        );

        $normalized_haystack = remove_accents($haystack);
        $normalized_search = remove_accents($search_term);

        if (function_exists('mb_stripos')) {
            return mb_stripos($normalized_haystack, $normalized_search, 0, 'UTF-8') !== false;
        }

        return stripos($normalized_haystack, $normalized_search) !== false;
    }

    private function sort_marketplace_item_ids($item_ids, $sort_by)
    {
        if (empty($item_ids)) {
            return $item_ids;
        }

        if ($sort_by === 'price_asc' || $sort_by === 'price_desc') {
            usort(
                $item_ids,
                function ($left_id, $right_id) use ($sort_by) {
                    $left_price = (float) get_post_meta($left_id, self::META_ITEM_PRICE, true);
                    $right_price = (float) get_post_meta($right_id, self::META_ITEM_PRICE, true);

                    if ($left_price === $right_price) {
                        return 0;
                    }

                    if ($sort_by === 'price_asc') {
                        return $left_price < $right_price ? -1 : 1;
                    }

                    return $left_price > $right_price ? -1 : 1;
                }
            );

            return $item_ids;
        }

        usort(
            $item_ids,
            function ($left_id, $right_id) {
                $left_post = get_post($left_id);
                $right_post = get_post($right_id);

                if (!$left_post || !$right_post) {
                    return 0;
                }

                return strcmp($right_post->post_date_gmt, $left_post->post_date_gmt);
            }
        );

        return $item_ids;
    }

    private function render_marketplace_form_inner($return_url, $editing_item = null)
    {
        $item_title = $editing_item ? $editing_item->post_title : '';
        $item_description = $editing_item ? $editing_item->post_content : '';
        $item_price = $editing_item ? get_post_meta($editing_item->ID, self::META_ITEM_PRICE, true) : '';
        $item_condition = $editing_item ? get_post_meta($editing_item->ID, self::META_ITEM_CONDITION, true) : '';
        $item_category = $editing_item ? get_post_meta($editing_item->ID, self::META_ITEM_CATEGORY, true) : '';
        $item_size = $editing_item ? get_post_meta($editing_item->ID, self::META_ITEM_SIZE, true) : '';

        ob_start();
        ?>
        <form method="post" class="sdp-form-grid" enctype="multipart/form-data">
            <?php if ($editing_item) : ?>
                <?php wp_nonce_field('sdp_marketplace_update_nonce'); ?>
                <input type="hidden" name="sdp_action" value="marketplace_update" />
                <input type="hidden" name="item_id" value="<?php echo esc_attr((string) $editing_item->ID); ?>" />
            <?php else : ?>
                <?php wp_nonce_field('sdp_marketplace_create_nonce'); ?>
                <input type="hidden" name="sdp_action" value="marketplace_create" />
            <?php endif; ?>
            <input type="hidden" name="sdp_return_url" value="<?php echo esc_url($return_url); ?>" />

            <label>Naslov oglasa *<input type="text" name="item_title" value="<?php echo esc_attr((string) $item_title); ?>" required></label>
            <label>Opis *<textarea name="item_description" rows="4" required><?php echo esc_textarea((string) $item_description); ?></textarea></label>
            <label>Cena (EUR) *<input type="text" name="item_price" value="<?php echo esc_attr((string) $item_price); ?>" placeholder="Npr. 35.00" required></label>
            <label>Stanje *
                <select name="item_condition" required>
                    <option value="">Izberi stanje</option>
                    <option value="novo" <?php selected($item_condition, 'novo'); ?>>Novo</option>
                    <option value="odlicno" <?php selected($item_condition, 'odlicno'); ?>>Odlično</option>
                    <option value="dobro" <?php selected($item_condition, 'dobro'); ?>>Dobro</option>
                    <option value="solidno" <?php selected($item_condition, 'solidno'); ?>>Solidno</option>
                </select>
            </label>
            <label>Kategorija
                <input type="text" name="item_category" value="<?php echo esc_attr((string) $item_category); ?>" placeholder="Npr. Dres, Jopica, Šprintarice">
            </label>
            <label>Velikost
                <input type="text" name="item_size" value="<?php echo esc_attr((string) $item_size); ?>" placeholder="Npr. S, M, L, 38, 164">
            </label>
            <label>Slika izdelka
                <input type="file" name="item_image" accept="image/*">
            </label>

            <?php if ($editing_item && has_post_thumbnail($editing_item->ID)) : ?>
                <div class="sdp-market-image-preview">
                    <?php echo get_the_post_thumbnail($editing_item->ID, 'medium'); ?>
                </div>
            <?php endif; ?>

            <button type="submit" class="sdp-btn-primary"><?php echo $editing_item ? 'Shrani spremembe' : 'Objavi oglas'; ?></button>
            <?php if ($editing_item) : ?>
                <p class="sdp-helper-links"><a href="<?php echo esc_url(remove_query_arg('sdp_edit_item', $return_url)); ?>">Prekliči urejanje</a></p>
            <?php endif; ?>
        </form>
        <?php

        return ob_get_clean();
    }

    private function render_marketplace_manage_listings_inner($return_url, $edit_url_base = '')
    {
        if ($edit_url_base === '') {
            $edit_url_base = $return_url;
        }

        $my_query = new WP_Query(
            array(
                'post_type' => self::MARKETPLACE_POST_TYPE,
                'post_status' => 'publish',
                'posts_per_page' => 50,
                'author' => get_current_user_id(),
                'orderby' => 'date',
                'order' => 'DESC',
            )
        );

        ob_start();

        if (!$my_query->have_posts()) :
            ?>
            <p>Trenutno še nimate objavljenih oglasov.</p>
            <?php
        else :
            ?>
            <div class="sdp-market-manage-grid">
                <?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
                    <?php
                    $my_item_id = get_the_ID();
                    $my_price = get_post_meta($my_item_id, self::META_ITEM_PRICE, true);
                    $my_state = get_post_meta($my_item_id, self::META_ITEM_STATE, true);
                    ?>
                    <article class="sdp-market-card">
                        <?php if (has_post_thumbnail($my_item_id)) : ?>
                            <div class="sdp-market-thumb-wrap"><?php echo get_the_post_thumbnail($my_item_id, 'medium'); ?></div>
                        <?php endif; ?>
                        <h4><?php echo esc_html(get_the_title()); ?></h4>
                        <p class="sdp-market-meta">
                            <strong>Cena:</strong> <?php echo esc_html(number_format((float) $my_price, 2, ',', '.')); ?> EUR<br>
                            <strong>Status:</strong> <?php echo esc_html($this->get_marketplace_item_state_label((string) $my_state)); ?>
                        </p>
                        <div class="sdp-market-actions">
                            <a class="sdp-btn-secondary" href="<?php echo esc_url(add_query_arg('sdp_edit_item', (int) $my_item_id, $edit_url_base)); ?>">Uredi</a>

                            <?php if ($my_state !== 'sold') : ?>
                                <form method="post">
                                    <?php wp_nonce_field('sdp_marketplace_manage_nonce'); ?>
                                    <input type="hidden" name="sdp_action" value="marketplace_mark_sold" />
                                    <input type="hidden" name="sdp_return_url" value="<?php echo esc_url($return_url); ?>" />
                                    <input type="hidden" name="item_id" value="<?php echo esc_attr((string) $my_item_id); ?>" />
                                    <button type="submit" class="sdp-btn-secondary">Označi kot prodano</button>
                                </form>
                            <?php endif; ?>

                            <form method="post" onsubmit="return confirm('Ali ste prepričani, da želite izbrisati oglas?');">
                                <?php wp_nonce_field('sdp_marketplace_manage_nonce'); ?>
                                <input type="hidden" name="sdp_action" value="marketplace_delete" />
                                <input type="hidden" name="sdp_return_url" value="<?php echo esc_url($return_url); ?>" />
                                <input type="hidden" name="item_id" value="<?php echo esc_attr((string) $my_item_id); ?>" />
                                <button type="submit" class="sdp-btn-danger">Izbriši</button>
                            </form>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            <?php
        endif;

        wp_reset_postdata();

        return ob_get_clean();
    }

    private function render_marketplace_detail_inner($item_id, $page_url)
    {
        $post = get_post($item_id);

        if (!$post || $post->post_type !== self::MARKETPLACE_POST_TYPE || $post->post_status !== 'publish') {
            return '<p>Izbran oglas ni na voljo.</p>';
        }

        $state = get_post_meta($item_id, self::META_ITEM_STATE, true);

        if ($state === 'sold') {
            return '<p>Ta oglas je že označen kot prodan.</p>';
        }

        $price = get_post_meta($item_id, self::META_ITEM_PRICE, true);
        $condition = get_post_meta($item_id, self::META_ITEM_CONDITION, true);
        $category = get_post_meta($item_id, self::META_ITEM_CATEGORY, true);
        $size = get_post_meta($item_id, self::META_ITEM_SIZE, true);
        $seller = get_user_by('id', (int) $post->post_author);
        $seller_name = $seller ? trim((string) get_user_meta($seller->ID, 'first_name', true) . ' ' . (string) get_user_meta($seller->ID, 'last_name', true)) : '';
        $seller_name = $seller_name !== '' ? $seller_name : ($seller ? $seller->display_name : 'Neznan uporabnik');
        $back_url = remove_query_arg('sdp_item', $page_url);

        ob_start();
        ?>
        <div class="sdp-market-detail">
            <p class="sdp-helper-links"><a href="<?php echo esc_url($back_url); ?>">Nazaj na oglase</a></p>
            <?php if (has_post_thumbnail($item_id)) : ?>
                <div class="sdp-market-detail-image"><?php echo get_the_post_thumbnail($item_id, 'large'); ?></div>
            <?php endif; ?>
            <h3><?php echo esc_html($post->post_title); ?></h3>
            <p class="sdp-market-meta">
                <strong>Cena:</strong> <?php echo esc_html(number_format((float) $price, 2, ',', '.')); ?> EUR<br>
                <strong>Stanje:</strong> <?php echo esc_html($this->get_marketplace_condition_label((string) $condition)); ?>
                <?php if ($size !== '') : ?>
                    <br><strong>Velikost:</strong> <?php echo esc_html($size); ?>
                <?php endif; ?>
                <?php if ($category !== '') : ?>
                    <br><strong>Kategorija:</strong> <?php echo esc_html($category); ?>
                <?php endif; ?>
            </p>
            <div class="sdp-market-description"><?php echo wpautop(esc_html($post->post_content)); ?></div>
            <p class="sdp-market-seller"><strong>Prodajalec:</strong> <?php echo esc_html($seller_name); ?></p>

            <form method="post" class="sdp-market-contact-form">
                <?php wp_nonce_field('sdp_marketplace_contact_nonce'); ?>
                <input type="hidden" name="sdp_action" value="marketplace_contact" />
                <input type="hidden" name="sdp_return_url" value="<?php echo esc_url($page_url); ?>" />
                <input type="hidden" name="item_id" value="<?php echo esc_attr((string) $item_id); ?>" />
                <label>Vaše ime *<input type="text" name="buyer_name" required></label>
                <label>Vaš e-poštni naslov *<input type="email" name="buyer_email" required></label>
                <label>Sporočilo *<textarea name="buyer_message" rows="4" required>Pozdravljeni, zanima me vaš oglas: <?php echo esc_html($post->post_title); ?>.</textarea></label>
                <button type="submit" class="sdp-btn-primary">Kontaktiraj prodajalca</button>
            </form>
        </div>
        <?php

        return ob_get_clean();
    }

    public function render_athlete_registration()
    {
        $show_login_after_registration = $this->is_registration_success_notice();
        $current_url = get_permalink();

        ob_start();
        ?>
        <div class="sdp-auth-wrap">
            <?php echo wp_kses_post($this->get_notice_html()); ?>
            <?php echo wp_kses_post($this->get_auth_bar_html($current_url)); ?>
            <div class="sdp-auth-card">
                <?php if ($show_login_after_registration) : ?>
                    <h2>Prijava</h2>
                    <form method="post" class="sdp-form-grid">
                        <?php wp_nonce_field('sdp_login_nonce'); ?>
                        <input type="hidden" name="sdp_action" value="login" />
                        <input type="hidden" name="sdp_return_url" value="<?php echo esc_url(get_permalink()); ?>" />

                        <label>Uporabniško ime ali e-pošta *<input name="login" type="text" required></label>
                        <label>Geslo *<input name="password" type="password" required></label>
                        <label class="sdp-check"><input type="checkbox" name="remember" value="1"> Zapomni si me</label>

                        <button type="submit" class="sdp-btn-primary">Prijava</button>
                    </form>
                    <p class="sdp-helper-links"><a href="<?php echo esc_url(home_url('/uporabniski-portal/pozabljeno-geslo/')); ?>">Ste pozabili geslo?</a></p>
                <?php else : ?>
                    <h2>Registracija atleta</h2>
                    <form method="post" class="sdp-form-grid sdp-registration-form">
                        <?php wp_nonce_field('sdp_register_nonce'); ?>
                        <input type="hidden" name="sdp_action" value="register_athlete" />
                        <input type="hidden" name="sdp_return_url" value="<?php echo esc_url(get_permalink()); ?>" />

                        <label>Ime *<input name="first_name" type="text" required></label>
                        <label>Priimek *<input name="last_name" type="text" required></label>
                        <label>Datum rojstva *<input name="birth_date" type="date" required></label>
                        <label>Spol
                            <select name="gender">
                                <option value="">Izberi</option>
                                <option value="moski">Moški</option>
                                <option value="zenski">Ženski</option>
                                <option value="drugo">Drugo</option>
                            </select>
                        </label>
                        <label>E-poštni naslov *<input name="email" type="email" required></label>
                        <label>Telefon starša/skrbnika <input name="phone" type="text"></label>
                        <label>Uporabniško ime *
                            <select name="username" class="sdp-username-select" required>
                                <option value="">Najprej vnesite ime in priimek</option>
                            </select>
                        </label>
                        <p class="sdp-username-help">Predlogi uporabniškega imena se pripravijo samodejno glede na ime in priimek.</p>
                        <label>Geslo *<input name="password" type="password" minlength="8" required></label>
                        <label>Potrditev gesla *<input name="password_confirm" type="password" minlength="8" required></label>

                        <label class="sdp-check"><input type="checkbox" name="privacy" value="1" required> Soglašam s politiko zasebnosti *</label>
                        <label class="sdp-check"><input type="checkbox" name="terms" value="1" required> Strinjam se s pogoji uporabe *</label>

                        <button type="submit" class="sdp-btn-primary">Ustvari račun</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_parent_registration()
    {
        $show_login_after_registration = $this->is_registration_success_notice();
        $current_url = get_permalink();

        ob_start();
        ?>
        <div class="sdp-auth-wrap">
            <?php echo wp_kses_post($this->get_notice_html()); ?>
            <?php echo wp_kses_post($this->get_auth_bar_html($current_url)); ?>
            <div class="sdp-auth-card">
                <?php if ($show_login_after_registration) : ?>
                    <h2>Prijava</h2>
                    <form method="post" class="sdp-form-grid">
                        <?php wp_nonce_field('sdp_login_nonce'); ?>
                        <input type="hidden" name="sdp_action" value="login" />
                        <input type="hidden" name="sdp_return_url" value="<?php echo esc_url(get_permalink()); ?>" />

                        <label>Uporabniško ime ali e-pošta *<input name="login" type="text" required></label>
                        <label>Geslo *<input name="password" type="password" required></label>
                        <label class="sdp-check"><input type="checkbox" name="remember" value="1"> Zapomni si me</label>

                        <button type="submit" class="sdp-btn-primary">Prijava</button>
                    </form>
                    <p class="sdp-helper-links"><a href="<?php echo esc_url(home_url('/uporabniski-portal/pozabljeno-geslo/')); ?>">Ste pozabili geslo?</a></p>
                <?php else : ?>
                    <h2>Registracija starša</h2>
                    <form method="post" class="sdp-form-grid sdp-registration-form">
                        <?php wp_nonce_field('sdp_register_nonce'); ?>
                        <input type="hidden" name="sdp_action" value="register_parent" />
                        <input type="hidden" name="sdp_return_url" value="<?php echo esc_url(get_permalink()); ?>" />

                        <label>Ime *<input name="first_name" type="text" required></label>
                        <label>Priimek *<input name="last_name" type="text" required></label>
                        <label>E-poštni naslov *<input name="email" type="email" required></label>
                        <label>Telefon <input name="phone" type="text"></label>
                        <label>Uporabniško ime *
                            <select name="username" class="sdp-username-select" required>
                                <option value="">Najprej vnesite ime in priimek</option>
                            </select>
                        </label>
                        <p class="sdp-username-help">Predlogi uporabniškega imena se pripravijo samodejno glede na ime in priimek.</p>
                        <label>Geslo *<input name="password" type="password" minlength="8" required></label>
                        <label>Potrditev gesla *<input name="password_confirm" type="password" minlength="8" required></label>
                        <label>Ime in priimek otroka (informativno)
                            <textarea name="child_info" rows="2" placeholder="Npr. Ana Novak"></textarea>
                        </label>
                        <label>Opombe
                            <textarea name="notes" rows="3" placeholder="Dodatne informacije"></textarea>
                        </label>

                        <label class="sdp-check"><input type="checkbox" name="privacy" value="1" required> Soglašam s politiko zasebnosti *</label>
                        <label class="sdp-check"><input type="checkbox" name="terms" value="1" required> Strinjam se s pogoji uporabe *</label>

                        <button type="submit" class="sdp-btn-primary">Ustvari račun</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_login_form()
    {
        $current_url = get_permalink();
        $parent_registration_url = $this->get_registration_page_url('parent');
        $athlete_registration_url = $this->get_registration_page_url('athlete');

        ob_start();
        ?>
        <div class="sdp-auth-wrap">
            <?php echo wp_kses_post($this->get_notice_html()); ?>
            <?php echo wp_kses_post($this->get_auth_bar_html($current_url)); ?>
            <div class="sdp-auth-card">
                <h2>Prijava</h2>
                <form method="post" class="sdp-form-grid">
                    <?php wp_nonce_field('sdp_login_nonce'); ?>
                    <input type="hidden" name="sdp_action" value="login" />
                    <input type="hidden" name="sdp_return_url" value="<?php echo esc_url(get_permalink()); ?>" />

                    <label>Uporabniško ime ali e-pošta *<input name="login" type="text" required></label>
                    <label>Geslo *<input name="password" type="password" required></label>
                    <label class="sdp-check"><input type="checkbox" name="remember" value="1"> Zapomni si me</label>

                    <button type="submit" class="sdp-btn-primary">Prijava</button>
                </form>
                <p class="sdp-helper-links"><a href="?show=forgot">Ste pozabili geslo?</a></p>
                <div class="sdp-auth-cta-row">
                    <a class="sdp-auth-cta sdp-auth-cta-parent" href="<?php echo esc_url($parent_registration_url); ?>">
                        <span class="sdp-auth-cta-icon" aria-hidden="true">👪</span>
                        <span>
                            <strong>Registracija starša</strong>
                            <small>Ustvari račun za starša</small>
                        </span>
                    </a>
                    <a class="sdp-auth-cta sdp-auth-cta-athlete" href="<?php echo esc_url($athlete_registration_url); ?>">
                        <span class="sdp-auth-cta-icon" aria-hidden="true">🏃</span>
                        <span>
                            <strong>Registracija atleta</strong>
                            <small>Ustvari račun za atleta</small>
                        </span>
                    </a>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    private function get_registration_page_url($type)
    {
        $search_map = array(
            'parent' => array(
                'slugs' => array('registracija-starsa', 'registracija-starsev', 'registracija-starša', 'registracija-za-starsa', 'registracija-za-starse'),
                'titles' => array('Registracija starša', 'Registracija staršev', 'Registracija starsev', 'Registracija starša in atleta'),
            ),
            'athlete' => array(
                'slugs' => array('registracija-atleta', 'registracija-sportnika', 'registracija-sportnika', 'registracija-za-atleta'),
                'titles' => array('Registracija atleta', 'Registracija športnika', 'Registracija sportnika', 'Registracija športnika in starša'),
            ),
        );

        $entry = isset($search_map[$type]) ? $search_map[$type] : null;

        if (!$entry) {
            return home_url('/uporabniski-portal/');
        }

        foreach ($entry['slugs'] as $slug) {
            $page = get_page_by_path($slug);
            if ($page && !empty($page->ID)) {
                return get_permalink($page->ID);
            }
        }

        foreach ($entry['titles'] as $title) {
            $page = get_page_by_title($title);
            if ($page && !empty($page->ID)) {
                return get_permalink($page->ID);
            }
        }

        return home_url('/uporabniski-portal/');
    }

    public function render_forgot_password_form()
    {
        $current_url = get_permalink();

        ob_start();
        ?>
        <div class="sdp-auth-wrap">
            <?php echo wp_kses_post($this->get_notice_html()); ?>
            <?php echo wp_kses_post($this->get_auth_bar_html($current_url)); ?>
            <div class="sdp-auth-card">
                <h2>Pozabljeno geslo</h2>
                <form method="post" class="sdp-form-grid">
                    <?php wp_nonce_field('sdp_forgot_nonce'); ?>
                    <input type="hidden" name="sdp_action" value="forgot_password" />
                    <input type="hidden" name="sdp_return_url" value="<?php echo esc_url(get_permalink()); ?>" />

                    <label>Uporabniško ime ali e-pošta *<input name="user_login" type="text" required></label>

                    <button type="submit" class="sdp-btn-primary">Pošlji povezavo za ponastavitev</button>
                </form>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_reset_password_form()
    {
        $key = isset($_GET['key']) ? sanitize_text_field(wp_unslash($_GET['key'])) : '';
        $login = isset($_GET['login']) ? sanitize_text_field(wp_unslash($_GET['login'])) : '';
        $current_url = $this->get_current_request_url();

        ob_start();
        ?>
        <div class="sdp-auth-wrap">
            <?php echo wp_kses_post($this->get_notice_html()); ?>
            <?php echo wp_kses_post($this->get_auth_bar_html($current_url)); ?>
            <div class="sdp-auth-card">
                <h2>Ponastavitev gesla</h2>
                <form method="post" class="sdp-form-grid">
                    <?php wp_nonce_field('sdp_reset_nonce'); ?>
                    <input type="hidden" name="sdp_action" value="reset_password" />
                    <input type="hidden" name="sdp_return_url" value="<?php echo esc_url(get_permalink()); ?>" />
                    <input type="hidden" name="key" value="<?php echo esc_attr($key); ?>" />
                    <input type="hidden" name="login" value="<?php echo esc_attr($login); ?>" />

                    <label>Novo geslo *<input name="password" type="password" minlength="8" required></label>
                    <label>Potrdi novo geslo *<input name="password_confirm" type="password" minlength="8" required></label>

                    <button type="submit" class="sdp-btn-primary">Shrani novo geslo</button>
                </form>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_dashboard()
    {
        ob_start();

        $current_url = $this->get_current_request_url();

        if (!is_user_logged_in()) {
            $login_url = home_url('/uporabniski-portal/prijava/');
            ?>
            <div class="sdp-auth-wrap">
                <?php echo wp_kses_post($this->get_auth_bar_html($current_url)); ?>
                <div class="sdp-auth-card">
                    <h2>Uporabniški portal</h2>
                    <p>Za ogled portala se morate prijaviti.</p>
                    <p class="sdp-helper-links"><a href="<?php echo esc_url($login_url); ?>">Pojdi na prijavo</a></p>
                </div>
            </div>
            <?php
            return ob_get_clean();
        }

        $user_id = get_current_user_id();
        $user = get_user_by('id', $user_id);

        if (!$user) {
            return '';
        }

        $role = reset($user->roles);
        $dashboard_url = get_permalink();
        $tab = isset($_GET['sdp_tab']) ? sanitize_key(wp_unslash($_GET['sdp_tab'])) : 'overview';
        $allowed_tabs = array('overview', 'profile', 'club-shop', 'club-orders', 'my-listings', 'new-listing');

        if ($role === self::ATHLETE_ROLE) {
            $allowed_tabs[] = 'training-docs';
        }

        if (!in_array($tab, $allowed_tabs, true)) {
            $tab = 'overview';
        }

        $overview_url = add_query_arg('sdp_tab', 'overview', $dashboard_url);
        $profile_url = add_query_arg('sdp_tab', 'profile', $dashboard_url);
        $club_shop_url = add_query_arg('sdp_tab', 'club-shop', $dashboard_url);
        $club_orders_url = add_query_arg('sdp_tab', 'club-orders', $dashboard_url);
        $my_listings_url = add_query_arg('sdp_tab', 'my-listings', $dashboard_url);
        $new_listing_url = add_query_arg('sdp_tab', 'new-listing', $dashboard_url);
        $training_docs_url = add_query_arg('sdp_tab', 'training-docs', $dashboard_url);

        $first_name = get_user_meta($user_id, 'first_name', true);
        $last_name = get_user_meta($user_id, 'last_name', true);
        $email = $user->user_email;
        $phone = get_user_meta($user_id, 'sdp_phone', true);
        $birth_date = get_user_meta($user_id, 'sdp_birth_date', true);
        $gender = get_user_meta($user_id, 'sdp_gender', true);
        $child_info = get_user_meta($user_id, self::META_CHILD_INFO, true);
        $notes = get_user_meta($user_id, 'sdp_notes', true);
        $marketplace_summary = $this->get_user_marketplace_summary($user_id);
        $club_order_summary = $this->get_user_latest_club_order_summary($user_id);
        $training_docs = $this->can_view_training_documents($user) ? $this->get_athlete_training_documents($user_id) : array();
        $training_docs_count = count($training_docs);
        ?>
        <div class="sdp-auth-wrap">
            <?php echo wp_kses_post($this->get_notice_html()); ?>
            <?php echo wp_kses_post($this->get_auth_bar_html($current_url)); ?>
            <div class="sdp-auth-card">
                <h2>Uporabniški portal</h2>
                <nav class="sdp-dashboard-nav" aria-label="Navigacija uporabniškega portala">
                    <a class="sdp-dashboard-tab <?php echo $tab === 'overview' ? 'is-active' : ''; ?>" href="<?php echo esc_url($overview_url); ?>">Pregled</a>
                    <a class="sdp-dashboard-tab <?php echo $tab === 'profile' ? 'is-active' : ''; ?>" href="<?php echo esc_url($profile_url); ?>">Uredi profil</a>
                    <?php if ($this->can_place_club_orders()) : ?>
                        <a class="sdp-dashboard-tab <?php echo $tab === 'club-shop' ? 'is-active' : ''; ?>" href="<?php echo esc_url($club_shop_url); ?>">Klubska oprema</a>
                        <a class="sdp-dashboard-tab <?php echo $tab === 'club-orders' ? 'is-active' : ''; ?>" href="<?php echo esc_url($club_orders_url); ?>">Moja naročila</a>
                    <?php endif; ?>
                    <?php if ($this->can_manage_marketplace()) : ?>
                        <a class="sdp-dashboard-tab <?php echo $tab === 'my-listings' ? 'is-active' : ''; ?>" href="<?php echo esc_url($my_listings_url); ?>">Moji oglasi</a>
                        <a class="sdp-dashboard-tab <?php echo $tab === 'new-listing' ? 'is-active' : ''; ?>" href="<?php echo esc_url($new_listing_url); ?>">Dodaj oglas</a>
                    <?php endif; ?>
                    <?php if ($this->can_view_training_documents($user)) : ?>
                        <a class="sdp-dashboard-tab <?php echo $tab === 'training-docs' ? 'is-active' : ''; ?>" href="<?php echo esc_url($training_docs_url); ?>">Trening načrti</a>
                    <?php endif; ?>
                </nav>

                <?php if ($tab === 'overview') : ?>
                    <div class="sdp-dashboard-panel">
                        <div class="sdp-dashboard-hero">
                            <p class="sdp-dashboard-kicker">Uporabniški portal</p>
                            <h3>Pozdravljeni, <?php echo esc_html($first_name ? $first_name : $user->display_name); ?>.</h3>
                            <p class="sdp-dashboard-lead">Tukaj lahko urejate svoj profil, spremljate svoje oglase in oddajate naročila klubske opreme. <?php echo $this->can_view_training_documents($user) ? 'Na voljo imate tudi trening načrte in dokumente trenerjev.' : ''; ?></p>
                        </div>

                        <div class="sdp-dashboard-highlights">
                            <div class="sdp-dashboard-highlight">
                                <div class="sdp-dashboard-highlight-head">
                                    <span class="dashicons dashicons-id sdp-dashboard-highlight-icon" aria-hidden="true"></span>
                                    <strong>Uredi profil</strong>
                                </div>
                                <span>Posodobite svoje podatke in kontaktne informacije.</span>
                                <span class="sdp-dashboard-highlight-meta">Uporabniško ime: <?php echo esc_html($user->user_login); ?></span>
                            </div>
                            <div class="sdp-dashboard-highlight">
                                <div class="sdp-dashboard-highlight-head">
                                    <span class="dashicons dashicons-store sdp-dashboard-highlight-icon" aria-hidden="true"></span>
                                    <strong>Prodaja rabljenih predmetov</strong>
                                </div>
                                <span>Dodajte oglas, uredite svoje oglase in označite prodane izdelke.</span>
                                <?php if ($marketplace_summary['total'] > 0) : ?>
                                    <span class="sdp-dashboard-highlight-meta">Objavljenih oglasov: <?php echo esc_html((string) $marketplace_summary['total']); ?> (aktivnih: <?php echo esc_html((string) $marketplace_summary['active']); ?>, prodanih: <?php echo esc_html((string) $marketplace_summary['sold']); ?>).</span>
                                <?php else : ?>
                                    <span class="sdp-dashboard-highlight-meta">Trenutno še nimate objavljenih oglasov.</span>
                                <?php endif; ?>
                            </div>
                            <div class="sdp-dashboard-highlight">
                                <div class="sdp-dashboard-highlight-head">
                                    <span class="dashicons dashicons-cart sdp-dashboard-highlight-icon" aria-hidden="true"></span>
                                    <strong>Klubska oprema</strong>
                                </div>
                                <span>Oddajte naročilo za uradno klubsko opremo in spremljajte svoja naročila.</span>
                                <?php if ($club_order_summary['has_orders']) : ?>
                                    <span class="sdp-dashboard-highlight-meta">
                                        <span class="sdp-dashboard-inline-status">Status: <?php echo esc_html($club_order_summary['status_label']); ?></span>
                                        Zadnje naročilo: <?php echo esc_html($club_order_summary['items_summary']); ?> (<?php echo esc_html((string) $club_order_summary['quantity']); ?> kosov).
                                    </span>
                                <?php else : ?>
                                    <span class="sdp-dashboard-highlight-meta">Trenutno še nimate oddanih naročil klubske opreme.</span>
                                <?php endif; ?>
                            </div>
                            <?php if ($this->can_view_training_documents($user)) : ?>
                                <div class="sdp-dashboard-highlight">
                                    <div class="sdp-dashboard-highlight-head">
                                        <span class="dashicons dashicons-media-document sdp-dashboard-highlight-icon" aria-hidden="true"></span>
                                        <strong>Trening načrti</strong>
                                    </div>
                                    <span>Dostopajte do trening načrtov in dokumentov, ki jih objavi klub.</span>
                                    <span class="sdp-dashboard-highlight-meta">Na voljo dokumentov: <?php echo esc_html((string) $training_docs_count); ?>.</span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <p class="sdp-dashboard-note">Uporabite zavihke zgoraj za hiter dostop do profila, klubske opreme, oglasov<?php echo $this->can_view_training_documents($user) ? ' in trening načrtov' : ''; ?>.</p>
                    </div>
                <?php endif; ?>

                <?php if ($tab === 'profile') : ?>
                    <div class="sdp-dashboard-panel">
                        <h3>Moj profil</h3>
                        <p class="sdp-profile-intro">Tukaj lahko posodobite svoje podatke. Uporabniškega imena ni mogoče spreminjati.</p>
                        <p class="sdp-profile-username"><strong>Uporabniško ime:</strong> <?php echo esc_html($user->user_login); ?></p>

                        <form method="post" class="sdp-form-grid">
                            <?php wp_nonce_field('sdp_profile_nonce'); ?>
                            <input type="hidden" name="sdp_action" value="update_profile" />
                            <input type="hidden" name="sdp_return_url" value="<?php echo esc_url($profile_url); ?>" />

                            <label>Ime *<input name="first_name" type="text" value="<?php echo esc_attr($first_name); ?>" required></label>
                            <label>Priimek *<input name="last_name" type="text" value="<?php echo esc_attr($last_name); ?>" required></label>
                            <label>E-poštni naslov *<input name="email" type="email" value="<?php echo esc_attr($email); ?>" required></label>
                            <label>Telefon <input name="phone" type="text" value="<?php echo esc_attr($phone); ?>"></label>

                            <?php if ($role === self::ATHLETE_ROLE) : ?>
                                <label>Datum rojstva <input name="birth_date" type="date" value="<?php echo esc_attr($birth_date); ?>"></label>
                                <label>Spol
                                    <select name="gender">
                                        <option value="" <?php selected($gender, ''); ?>>Izberi</option>
                                        <option value="moski" <?php selected($gender, 'moski'); ?>>Moški</option>
                                        <option value="zenski" <?php selected($gender, 'zenski'); ?>>Ženski</option>
                                        <option value="drugo" <?php selected($gender, 'drugo'); ?>>Drugo</option>
                                    </select>
                                </label>
                            <?php endif; ?>

                            <?php if ($role === self::PARENT_ROLE) : ?>
                                <label>Ime in priimek otroka (informativno)
                                    <textarea name="child_info" rows="2" placeholder="Npr. Ana Novak"><?php echo esc_textarea($child_info); ?></textarea>
                                </label>
                                <label>Opombe
                                    <textarea name="notes" rows="3" placeholder="Dodatne informacije"><?php echo esc_textarea($notes); ?></textarea>
                                </label>
                            <?php endif; ?>

                            <button type="submit" class="sdp-btn-primary">Shrani profil</button>
                        </form>
                    </div>
                <?php endif; ?>

                <?php if ($tab === 'club-shop' && $this->can_place_club_orders()) : ?>
                    <div class="sdp-dashboard-panel">
                        <h3>Klubska oprema</h3>
                        <?php echo $this->render_club_shop_inner($club_shop_url); ?>
                    </div>
                <?php endif; ?>

                <?php if ($tab === 'club-orders' && $this->can_place_club_orders()) : ?>
                    <div class="sdp-dashboard-panel">
                        <h3>Moja naročila</h3>
                        <?php echo $this->render_club_orders_inner(); ?>
                    </div>
                <?php endif; ?>

                <?php if ($tab === 'training-docs' && $this->can_view_training_documents($user)) : ?>
                    <div class="sdp-dashboard-panel">
                        <h3>Trening načrti</h3>
                        <p>Tu najdete trening načrte in dodatne dokumente, ki so pripravljeni posebej za vaš profil atleta.</p>
                        <?php echo $this->render_training_documents_inner($user_id); ?>
                    </div>
                <?php endif; ?>

                <?php if ($tab === 'my-listings' && $this->can_manage_marketplace()) : ?>
                    <div class="sdp-dashboard-panel">
                        <h3>Moji oglasi</h3>
                        <?php echo $this->render_marketplace_manage_listings_inner($my_listings_url, $new_listing_url); ?>
                    </div>
                <?php endif; ?>

                <?php if ($tab === 'new-listing' && $this->can_manage_marketplace()) : ?>
                    <?php
                    $editing_item = null;

                    if (isset($_GET['sdp_edit_item'])) {
                        $editing_item = $this->get_editable_marketplace_item((int) $_GET['sdp_edit_item']);
                    }
                    ?>
                    <div class="sdp-dashboard-panel">
                        <h3><?php echo $editing_item ? 'Uredi oglas' : 'Dodaj oglas'; ?></h3>
                        <?php echo $this->render_marketplace_form_inner($new_listing_url, $editing_item); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_club_shop()
    {
        $current_url = $this->get_current_request_url();

        ob_start();
        ?>
        <div class="sdp-auth-wrap">
            <?php echo wp_kses_post($this->get_notice_html()); ?>
            <?php echo wp_kses_post($this->get_auth_bar_html($current_url)); ?>
            <div class="sdp-auth-card">
                <h2>Klubska oprema</h2>
                <?php echo $this->render_club_shop_inner(get_permalink()); ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_club_orders()
    {
        $current_url = $this->get_current_request_url();

        ob_start();
        ?>
        <div class="sdp-auth-wrap">
            <?php echo wp_kses_post($this->get_notice_html()); ?>
            <?php echo wp_kses_post($this->get_auth_bar_html($current_url)); ?>
            <div class="sdp-auth-card">
                <h2>Moja naročila</h2>
                <?php echo $this->render_club_orders_inner(); ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    private function render_training_documents_inner($athlete_id)
    {
        $documents = $this->get_athlete_training_documents($athlete_id);

        if (empty($documents)) {
            return '<p>Trenutno še ni naloženih trening načrtov ali dokumentov.</p>';
        }

        ob_start();
        ?>
        <div class="sdp-training-doc-list">
            <?php foreach ($documents as $document) : ?>
                <?php
                $file_url = (string) get_post_meta($document->ID, self::META_TRAINING_DOC_FILE_URL, true);
                $description = (string) get_post_meta($document->ID, self::META_TRAINING_DOC_DESCRIPTION, true);
                ?>
                <article class="sdp-training-doc-card">
                    <div class="sdp-training-doc-head">
                        <span class="dashicons dashicons-media-document sdp-training-doc-icon" aria-hidden="true"></span>
                        <strong><?php echo esc_html($document->post_title); ?></strong>
                    </div>

                    <?php if ($description !== '') : ?>
                        <p class="sdp-training-doc-desc"><?php echo esc_html($description); ?></p>
                    <?php endif; ?>

                    <p class="sdp-training-doc-meta">Objavljeno: <?php echo esc_html(get_the_date('d. m. Y', $document)); ?></p>

                    <?php if ($file_url !== '') : ?>
                        <a class="sdp-btn-secondary" href="<?php echo esc_url($file_url); ?>" target="_blank" rel="noopener noreferrer">Odpri dokument</a>
                    <?php else : ?>
                        <p class="sdp-training-doc-meta">Datoteka trenutno ni na voljo.</p>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
        <?php

        return ob_get_clean();
    }

    private function render_club_shop_inner($return_url)
    {
        if (!$this->can_place_club_orders()) {
            return '<p>Naročanje klubske opreme je na voljo prijavljenim staršem in atletom.</p>';
        }

        $products = get_posts(
            array(
                'post_type' => self::CLUB_PRODUCT_POST_TYPE,
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'orderby' => array(
                    'menu_order' => 'ASC',
                    'title' => 'ASC',
                ),
                'meta_query' => array(
                    'relation' => 'OR',
                    array(
                        'key' => self::META_CLUB_PRODUCT_ACTIVE,
                        'compare' => 'NOT EXISTS',
                    ),
                    array(
                        'key' => self::META_CLUB_PRODUCT_ACTIVE,
                        'value' => 'yes',
                        'compare' => '=',
                    ),
                ),
            )
        );

        if (empty($products)) {
            return '<p>Trenutno ni aktivnih izdelkov klubske opreme.</p>';
        }

        ob_start();
        ?>
        <div class="sdp-club-shop-wrap">
            <div class="sdp-club-cart-panel">
                <div class="sdp-club-cart-header">
                    <div>
                        <h4>Košarica</h4>
                        <p>Dodajte več izdelkov in nato oddajte eno skupno naročilo.</p>
                    </div>
                    <div class="sdp-club-cart-summary">
                        <span class="sdp-club-cart-count">0 izdelkov</span>
                        <span class="sdp-club-cart-total">Skupaj: 0,00 EUR</span>
                    </div>
                </div>

                <div class="sdp-club-cart-message" hidden></div>
                <div class="sdp-club-cart-empty">Košarica je trenutno prazna.</div>
                <div class="sdp-club-cart-items"></div>

                <form method="post" class="sdp-form-grid sdp-club-cart-submit">
                    <?php wp_nonce_field('sdp_club_order_create_nonce'); ?>
                    <input type="hidden" name="sdp_action" value="club_order_create" />
                    <input type="hidden" name="sdp_return_url" value="<?php echo esc_url($return_url); ?>" />
                    <input type="hidden" name="club_cart_payload" value="" />

                    <label>Za koga je naročilo? *
                        <input name="club_child_name" type="text" placeholder="Ime in priimek otroka / atleta" required>
                    </label>

                    <label>Skupna opomba
                        <textarea name="club_note" rows="3" placeholder="Dodatne informacije za naročilo"></textarea>
                    </label>

                    <button type="submit" class="sdp-btn-primary" disabled>Oddaj naročilo</button>
                </form>
            </div>

            <div class="sdp-club-grid">
                <?php foreach ($products as $product) : ?>
                    <?php
                    $price = $this->get_club_product_price($product->ID);
                    $sizes = $this->get_club_product_sizes($product->ID);
                    $price_label = $price !== '' ? $this->format_price($price) : '';
                    ?>
                    <article class="sdp-club-card" data-product-id="<?php echo esc_attr((string) $product->ID); ?>" data-product-name="<?php echo esc_attr($product->post_title); ?>" data-product-price="<?php echo esc_attr($price); ?>" data-product-price-label="<?php echo esc_attr($price_label); ?>">
                        <?php if (has_post_thumbnail($product->ID)) : ?>
                            <div class="sdp-club-thumb-wrap"><?php echo get_the_post_thumbnail($product->ID, 'medium'); ?></div>
                        <?php else : ?>
                            <div class="sdp-club-thumb-placeholder">ŠD Pohorje</div>
                        <?php endif; ?>
                        <div class="sdp-club-card-body">
                            <h4><?php echo esc_html($product->post_title); ?></h4>
                            <?php if ($price !== '') : ?>
                                <p class="sdp-club-price"><?php echo esc_html($price_label); ?></p>
                            <?php endif; ?>
                            <div class="sdp-club-description"><?php echo wpautop(wp_kses_post($product->post_content)); ?></div>

                            <div class="sdp-form-grid sdp-club-order-form">
                                <label>Količina
                                    <input name="club_quantity" type="number" min="1" step="1" value="1" required>
                                </label>

                                <?php if (!empty($sizes)) : ?>
                                    <label>Velikost
                                        <select name="club_size" required>
                                            <option value="">Izberi velikost</option>
                                            <?php foreach ($sizes as $size_option) : ?>
                                                <option value="<?php echo esc_attr($size_option); ?>"><?php echo esc_html($size_option); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </label>
                                <?php else : ?>
                                    <label>Velikost
                                        <input name="club_size" type="text" placeholder="Po želji">
                                    </label>
                                <?php endif; ?>

                                <button type="button" class="sdp-btn-secondary sdp-club-add-to-cart">Dodaj v košarico</button>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
        <?php

        return ob_get_clean();
    }

    private function render_club_orders_inner()
    {
        if (!$this->can_place_club_orders()) {
            return '<p>Pregled naročil je na voljo prijavljenim staršem in atletom.</p>';
        }

        $orders = get_posts(
            array(
                'post_type' => self::CLUB_ORDER_POST_TYPE,
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'author' => get_current_user_id(),
                'orderby' => 'date',
                'order' => 'DESC',
            )
        );

        if (empty($orders)) {
            return '<p>Trenutno še nimate oddanih naročil.</p>';
        }

        ob_start();
        ?>
        <div class="sdp-club-order-list">
            <?php foreach ($orders as $order) : ?>
                <?php
                $items = $this->get_club_order_items($order->ID);
                $child_name = (string) get_post_meta($order->ID, self::META_CLUB_ORDER_CHILD_NAME, true);
                $status = (string) get_post_meta($order->ID, self::META_CLUB_ORDER_STATUS, true);
                $total = $this->get_club_order_total_from_items($items);
                ?>
                <article class="sdp-club-order-card">
                    <div class="sdp-club-order-head">
                        <strong><?php echo esc_html($this->get_club_order_items_summary_from_items($items)); ?></strong>
                        <span class="sdp-club-order-status"><?php echo esc_html($this->get_club_order_status_label($status)); ?></span>
                    </div>
                    <p><strong>Za:</strong> <?php echo esc_html($child_name !== '' ? $child_name : '-'); ?></p>
                    <p><strong>Datum:</strong> <?php echo esc_html(get_the_date('d. m. Y H:i', $order)); ?></p>
                    <p><strong>Št. kosov:</strong> <?php echo esc_html((string) $this->get_club_order_total_quantity_from_items($items)); ?></p>
                    <div class="sdp-club-order-items"><?php echo $this->build_club_order_items_html($items); ?></div>
                    <p><strong>Skupaj:</strong> <?php echo esc_html($this->format_price($total)); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
        <?php

        return ob_get_clean();
    }

    public function render_marketplace_sell_form()
    {
        $editing_item = null;
        $current_url = $this->get_current_request_url();

        if ($this->can_manage_marketplace() && isset($_GET['sdp_edit_item'])) {
            $edit_item_id = (int) $_GET['sdp_edit_item'];
            $editing_item = $this->get_editable_marketplace_item($edit_item_id);
        }

        ob_start();
        ?>
        <div class="sdp-auth-wrap">
            <?php echo wp_kses_post($this->get_notice_html()); ?>
            <?php echo wp_kses_post($this->get_auth_bar_html($current_url)); ?>
            <div class="sdp-auth-card">
                <h2><?php echo $editing_item ? 'Uredi oglas' : 'Prodaj rabljeno opremo'; ?></h2>
                <?php if (!$this->can_manage_marketplace()) : ?>
                    <p>Oddaja oglasa je na voljo prijavljenim staršem in atletom.</p>
                    <p class="sdp-helper-links"><a href="<?php echo esc_url(home_url('/uporabniski-portal/prijava/')); ?>">Pojdi na prijavo</a></p>
                <?php else : ?>
                    <?php echo $this->render_marketplace_form_inner(get_permalink(), $editing_item); ?>

                    <div class="sdp-market-section-divider"></div>
                    <h3 class="sdp-market-section-title">Moji oglasi</h3>
                    <?php echo $this->render_marketplace_manage_listings_inner(get_permalink()); ?>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_marketplace_listings()
    {
        $page_url = $this->get_current_request_url();
        $current_url = $page_url;
        $detail_item_id = isset($_GET['sdp_item']) ? (int) $_GET['sdp_item'] : 0;
        $selected_search = isset($_GET['sdp_search']) ? sanitize_text_field(wp_unslash($_GET['sdp_search'])) : '';
        $selected_condition = isset($_GET['sdp_condition']) ? sanitize_key(wp_unslash($_GET['sdp_condition'])) : '';
        $selected_category = isset($_GET['sdp_category']) ? sanitize_text_field(wp_unslash($_GET['sdp_category'])) : '';
        $selected_sort = isset($_GET['sdp_sort']) ? sanitize_key(wp_unslash($_GET['sdp_sort'])) : 'newest';

        if (!in_array($selected_sort, array('newest', 'price_asc', 'price_desc'), true)) {
            $selected_sort = 'newest';
        }

        $query_args = array(
            'post_type' => self::MARKETPLACE_POST_TYPE,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'relation' => 'OR',
                    array(
                        'key' => self::META_ITEM_STATE,
                        'compare' => 'NOT EXISTS',
                    ),
                    array(
                        'key' => self::META_ITEM_STATE,
                        'value' => 'active',
                        'compare' => '=',
                    ),
                ),
            ),
        );

        if ($selected_condition !== '') {
            $query_args['meta_query'][] = array(
                'key' => self::META_ITEM_CONDITION,
                'value' => $selected_condition,
                'compare' => '=',
            );
        }

        if ($selected_category !== '') {
            $query_args['meta_query'][] = array(
                'key' => self::META_ITEM_CATEGORY,
                'value' => $selected_category,
                'compare' => '=',
            );
        }

        $query = new WP_Query($query_args);
        $categories = $this->get_marketplace_categories();
        $matching_item_ids = array();

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();

                if ($this->marketplace_item_matches_search(get_the_ID(), $selected_search)) {
                    $matching_item_ids[] = get_the_ID();
                }
            }

            wp_reset_postdata();
        }

        $matching_item_ids = $this->sort_marketplace_item_ids($matching_item_ids, $selected_sort);

        ob_start();
        ?>
        <div class="sdp-auth-wrap">
            <?php echo wp_kses_post($this->get_notice_html()); ?>
            <?php echo wp_kses_post($this->get_auth_bar_html($current_url)); ?>
            <div class="sdp-auth-card">
                <h2>Rabljena oprema</h2>

                <form method="get" class="sdp-market-filters" action="<?php echo esc_url(remove_query_arg('sdp_item', $page_url)); ?>">
                    <label class="sdp-market-filters-search">Išči
                        <input type="text" name="sdp_search" value="<?php echo esc_attr($selected_search); ?>" placeholder="Išči po naslovu, opisu, velikosti, kategoriji ...">
                    </label>
                    <label>Stanje
                        <select name="sdp_condition">
                            <option value="">Vsa stanja</option>
                            <option value="novo" <?php selected($selected_condition, 'novo'); ?>>Novo</option>
                            <option value="odlicno" <?php selected($selected_condition, 'odlicno'); ?>>Odlično</option>
                            <option value="dobro" <?php selected($selected_condition, 'dobro'); ?>>Dobro</option>
                            <option value="solidno" <?php selected($selected_condition, 'solidno'); ?>>Solidno</option>
                        </select>
                    </label>
                    <label>Kategorija
                        <select name="sdp_category">
                            <option value="">Vse kategorije</option>
                            <?php foreach ($categories as $category_option) : ?>
                                <option value="<?php echo esc_attr($category_option); ?>" <?php selected($selected_category, $category_option); ?>><?php echo esc_html($category_option); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label>Razvrsti
                        <select name="sdp_sort">
                            <option value="newest" <?php selected($selected_sort, 'newest'); ?>>Najnovejši</option>
                            <option value="price_asc" <?php selected($selected_sort, 'price_asc'); ?>>Najnižja cena</option>
                            <option value="price_desc" <?php selected($selected_sort, 'price_desc'); ?>>Najvišja cena</option>
                        </select>
                    </label>
                    <button type="submit" class="sdp-btn-secondary">Filtriraj</button>
                </form>

                <?php if ($detail_item_id) : ?>
                    <?php echo $this->render_marketplace_detail_inner($detail_item_id, $page_url); ?>
                <?php elseif (empty($matching_item_ids)) : ?>
                    <p>Trenutno ni aktivnih oglasov za izbrane filtre.</p>
                <?php else : ?>
                    <div class="sdp-market-grid">
                        <?php foreach ($matching_item_ids as $item_id) : ?>
                            <?php
                            $post = get_post($item_id);
                            if (!$post) {
                                continue;
                            }
                            $price = get_post_meta($item_id, self::META_ITEM_PRICE, true);
                            $condition = get_post_meta($item_id, self::META_ITEM_CONDITION, true);
                            $category = get_post_meta($item_id, self::META_ITEM_CATEGORY, true);
                            $size = get_post_meta($item_id, self::META_ITEM_SIZE, true);
                            $seller = get_user_by('id', (int) $post->post_author);
                            $seller_name = $seller ? trim((string) get_user_meta($seller->ID, 'first_name', true) . ' ' . (string) get_user_meta($seller->ID, 'last_name', true)) : '';
                            $seller_name = $seller_name !== '' ? $seller_name : ($seller ? $seller->display_name : 'Neznan uporabnik');
                            $detail_url = add_query_arg('sdp_item', (int) $item_id, $page_url);
                            ?>
                            <article class="sdp-market-card">
                                <?php if (has_post_thumbnail($item_id)) : ?>
                                    <div class="sdp-market-thumb-wrap"><?php echo get_the_post_thumbnail($item_id, 'medium'); ?></div>
                                <?php endif; ?>
                                <h3><?php echo esc_html($post->post_title); ?></h3>
                                <p class="sdp-market-meta">
                                    <strong>Cena:</strong> <?php echo esc_html(number_format((float) $price, 2, ',', '.')); ?> EUR<br>
                                    <strong>Stanje:</strong> <?php echo esc_html($this->get_marketplace_condition_label((string) $condition)); ?>
                                    <?php if ($size !== '') : ?>
                                        <br><strong>Velikost:</strong> <?php echo esc_html($size); ?>
                                    <?php endif; ?>
                                    <?php if ($category !== '') : ?>
                                        <br><strong>Kategorija:</strong> <?php echo esc_html($category); ?>
                                    <?php endif; ?>
                                </p>
                                <p><?php echo esc_html(wp_trim_words(wp_strip_all_tags($post->post_content), 24)); ?></p>
                                <p class="sdp-market-seller"><strong>Prodajalec:</strong> <?php echo esc_html($seller_name); ?></p>
                                <a class="sdp-btn-secondary" href="<?php echo esc_url($detail_url); ?>">Poglej oglas</a>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php

        wp_reset_postdata();

        return ob_get_clean();
    }

    private function send_club_order_confirmation($order_id)
    {
        $order = get_post($order_id);

        if (!$order || $order->post_type !== self::CLUB_ORDER_POST_TYPE) {
            return;
        }

        $customer_email = (string) get_post_meta($order_id, self::META_CLUB_ORDER_CUSTOMER_EMAIL, true);

        if (!is_email($customer_email)) {
            return;
        }

        $customer_name = (string) get_post_meta($order_id, self::META_CLUB_ORDER_CUSTOMER_NAME, true);
        $child_name = (string) get_post_meta($order_id, self::META_CLUB_ORDER_CHILD_NAME, true);
        $items = $this->get_club_order_items($order_id);
        $total = $this->get_club_order_total_from_items($items);
        $from_name = wp_specialchars_decode(get_bloginfo('name'), ENT_QUOTES);
        $from_email = sanitize_email(get_option('admin_email'));

        if (!$from_email || !is_email($from_email)) {
            $from_email = 'noreply@' . wp_parse_url(home_url('/'), PHP_URL_HOST);
        }

        $subject = 'ŠD Pohorje: Potrditev naročila klubske opreme';
        $html_message = $this->build_branded_email_html(
            'Potrditev naročila',
            '<p style="margin:0 0 14px;font-size:16px;line-height:1.55;">Pozdravljeni, ' . esc_html($customer_name) . '!</p>' .
            '<p style="margin:0 0 14px;font-size:16px;line-height:1.55;">Vaše naročilo klubske opreme je bilo uspešno oddano.</p>' .
            '<p style="margin:0 0 8px;font-size:15px;line-height:1.55;"><strong>Za:</strong> ' . esc_html($child_name !== '' ? $child_name : '-') . '</p>' .
            '<p style="margin:0 0 8px;font-size:15px;line-height:1.55;"><strong>Izdelki:</strong></p>' .
            $this->build_club_order_items_html($items) .
            '<p style="margin:12px 0 8px;font-size:15px;line-height:1.55;"><strong>Skupaj:</strong> ' . esc_html($this->format_price($total)) . '</p>' .
            '<p style="margin:16px 0 0;font-size:15px;line-height:1.55;">O nadaljnjih korakih vas bomo obvestili po e-pošti.</p>'
        );

        $text_message = "Pozdravljeni, {$customer_name}!\n\n" .
            "Vaše naročilo klubske opreme je bilo uspešno oddano.\n\n" .
            'Za: ' . ($child_name !== '' ? $child_name : '-') . "\n\n" .
            "Izdelki:\n" . $this->build_club_order_items_text($items) . "\n\n" .
            'Skupaj: ' . $this->format_price($total) . "\n\n" .
            "Lep pozdrav,\n" .
            "ekipa ŠD Pohorje";

        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $from_name . ' <' . $from_email . '>',
        );

        $sent = wp_mail($customer_email, $subject, $html_message, $headers);

        if (!$sent) {
            $sent = wp_mail($customer_email, $subject, $text_message, array('From: ' . $from_name . ' <' . $from_email . '>'));
        }

        if (!$sent) {
            error_log('SDP Accounts: failed to send club order confirmation to ' . $customer_email);
        }
    }

    private function send_admin_club_order_notice($order_id)
    {
        $order = get_post($order_id);
        $admin_email = $this->get_admin_notification_email();

        if (!$order || $order->post_type !== self::CLUB_ORDER_POST_TYPE || !$admin_email || !is_email($admin_email)) {
            return;
        }

        $customer_name = (string) get_post_meta($order_id, self::META_CLUB_ORDER_CUSTOMER_NAME, true);
        $customer_email = (string) get_post_meta($order_id, self::META_CLUB_ORDER_CUSTOMER_EMAIL, true);
        $customer_phone = (string) get_post_meta($order_id, self::META_CLUB_ORDER_CUSTOMER_PHONE, true);
        $child_name = (string) get_post_meta($order_id, self::META_CLUB_ORDER_CHILD_NAME, true);
        $items = $this->get_club_order_items($order_id);
        $note = (string) get_post_meta($order_id, self::META_CLUB_ORDER_NOTE, true);
        $total = $this->get_club_order_total_from_items($items);
        $edit_url = admin_url('post.php?post=' . (int) $order_id . '&action=edit');
        $from_name = wp_specialchars_decode(get_bloginfo('name'), ENT_QUOTES);
        $from_email = sanitize_email(get_option('admin_email'));

        if (!$from_email || !is_email($from_email)) {
            $from_email = 'noreply@' . wp_parse_url(home_url('/'), PHP_URL_HOST);
        }

        $subject = 'Novo naročilo klubske opreme: ' . $this->get_club_order_items_summary_from_items($items);
        $html_message = $this->build_branded_email_html(
            'Novo naročilo klubske opreme',
            '<p style="margin:0 0 14px;font-size:16px;line-height:1.55;">Prejeli ste novo naročilo klubske opreme.</p>' .
            '<p style="margin:0 0 8px;font-size:15px;line-height:1.55;"><strong>Order ID:</strong> ' . esc_html((string) $order_id) . '</p>' .
            '<p style="margin:0 0 8px;font-size:15px;line-height:1.55;"><strong>Kupec:</strong> ' . esc_html($customer_name) . '</p>' .
            '<p style="margin:0 0 8px;font-size:15px;line-height:1.55;"><strong>Za:</strong> ' . esc_html($child_name !== '' ? $child_name : '-') . '</p>' .
            '<p style="margin:0 0 8px;font-size:15px;line-height:1.55;"><strong>E-pošta:</strong> <a href="mailto:' . esc_attr($customer_email) . '" style="color:#356f8c;">' . esc_html($customer_email) . '</a></p>' .
            '<p style="margin:0 0 8px;font-size:15px;line-height:1.55;"><strong>Telefon:</strong> ' . esc_html($customer_phone !== '' ? $customer_phone : '-') . '</p>' .
            '<p style="margin:0 0 8px;font-size:15px;line-height:1.55;"><strong>Izdelki:</strong></p>' .
            $this->build_club_order_items_html($items) .
            '<p style="margin:12px 0 8px;font-size:15px;line-height:1.55;"><strong>Skupaj:</strong> ' . esc_html($this->format_price($total)) . '</p>' .
            '<p style="margin:18px 0 8px;font-size:15px;line-height:1.55;"><strong>Opomba kupca:</strong></p>' .
            '<div style="margin:0 0 18px;padding:14px 16px;border-left:4px solid #c69a47;background:#f6fbff;border-radius:10px;color:#183748;white-space:pre-line;">' . esc_html($note !== '' ? $note : '-') . '</div>' .
            '<p style="margin:0 0 8px;font-size:15px;line-height:1.55;"><a href="' . esc_url($edit_url) . '" style="display:inline-block;background:#356f8c;color:#ffffff;text-decoration:none;font-weight:700;padding:12px 18px;border-radius:999px;">Odpri naročilo v administraciji</a></p>'
        );
        $text_message = "Novo naročilo klubske opreme\n\n" .
            "Order ID: {$order_id}\n" .
            "Customer: {$customer_name}\n" .
            'For: ' . ($child_name !== '' ? $child_name : '-') . "\n" .
            "Email: {$customer_email}\n" .
            'Phone: ' . ($customer_phone !== '' ? $customer_phone : '-') . "\n" .
            "Items:\n" . $this->build_club_order_items_text($items) . "\n" .
            'Total: ' . $this->format_price($total) . "\n" .
            'Customer note: ' . ($note !== '' ? $note : '-') . "\n\n" .
            "Open order: {$edit_url}\n";

        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $from_name . ' <' . $from_email . '>',
        );

        $sent = wp_mail($admin_email, $subject, $html_message, $headers);

        if (!$sent) {
            $sent = wp_mail($admin_email, $subject, $text_message, array('From: ' . $from_name . ' <' . $from_email . '>'));
        }

        if (!$sent) {
            error_log('SDP Accounts: failed to send admin club order notice to ' . $admin_email);
        }
    }

    private function send_admin_registration_notice($user_id, $role)
    {
        $admin_email = $this->get_admin_notification_email();
        $user = get_user_by('id', $user_id);

        if (!$admin_email || !$user) {
            return;
        }

        $subject = 'New registration pending approval';
        $message = sprintf(
            "A new account is pending approval.\n\nUser ID: %d\nUsername: %s\nEmail: %s\nRole: %s\n",
            $user->ID,
            $user->user_login,
            $user->user_email,
            $role
        );

        wp_mail($admin_email, $subject, $message);
    }

    private function build_branded_email_html($heading, $inner_html)
    {
        return '<div style="margin:0;padding:24px;background:#f2f7fb;font-family:Arial,Helvetica,sans-serif;color:#183748;">'
            . '<div style="max-width:640px;margin:0 auto;background:#ffffff;border:1px solid #d3e3ef;border-radius:14px;overflow:hidden;">'
            . '<div style="background:#356f8c;padding:18px 24px;">'
            . '<h1 style="margin:0;color:#ffffff;font-size:22px;line-height:1.3;">ŠD Pohorje</h1>'
            . '</div>'
            . '<div style="padding:24px;">'
            . '<h2 style="margin:0 0 18px;font-size:24px;line-height:1.25;color:#183748;">' . esc_html($heading) . '</h2>'
            . $inner_html
            . '<p style="margin:24px 0 0;font-size:14px;line-height:1.55;color:#4b6574;">Lep pozdrav,<br>ekipa ŠD Pohorje</p>'
            . '</div>'
            . '</div>'
            . '</div>';
    }

    private function send_user_registration_confirmation($user_id, $role)
    {
        $user = get_user_by('id', $user_id);

        if (!$user || empty($user->user_email)) {
            return;
        }

        $first_name = get_user_meta($user_id, 'first_name', true);
        $login_url = home_url('/uporabniski-portal/prijava/');
        $portal_url = $this->get_dashboard_url();
        $display_name = $first_name ? $first_name : $user->user_login;

        $role_label = $role === self::ATHLETE_ROLE ? 'atleta' : 'starša';

        $subject = 'ŠD Pohorje: Registracija uspešna';
        $from_name = wp_specialchars_decode(get_bloginfo('name'), ENT_QUOTES);
        $from_email = sanitize_email(get_option('admin_email'));

        if (!$from_email || !is_email($from_email)) {
            $from_email = 'noreply@' . wp_parse_url(home_url('/'), PHP_URL_HOST);
        }

        $html_message = $this->build_branded_email_html(
            'Registracija uspešna',
            '<p style="margin:0 0 14px;font-size:16px;line-height:1.55;">Pozdravljeni, ' . esc_html($display_name) . '!</p>' .
            '<p style="margin:0 0 14px;font-size:16px;line-height:1.55;">Hvala za registracijo ' . esc_html($role_label) . ' v portal ŠD Pohorje.</p>' .
            '<p style="margin:0 0 14px;font-size:16px;line-height:1.55;">Registracija je uspešna. Prijavite se lahko tukaj:</p>' .
            '<p style="margin:0 0 20px;"><a href="' . esc_url($login_url) . '" style="display:inline-block;background:#c69a47;color:#ffffff;text-decoration:none;font-weight:700;padding:12px 18px;border-radius:999px;">Na prijavo</a></p>' .
            '<p style="margin:0 0 14px;font-size:15px;line-height:1.55;">Po prijavi boste preusmerjeni v uporabniški portal:</p>' .
            '<p style="margin:0 0 14px;font-size:15px;line-height:1.55;"><a href="' . esc_url($portal_url) . '" style="color:#2f3b45;">' . esc_html($portal_url) . '</a></p>'
        );

        $text_message = "Pozdravljeni, {$display_name}!\n\n" .
            "Hvala za registracijo {$role_label} v portal ŠD Pohorje.\n" .
            "Registracija je uspešna.\n\n" .
            "Prijava: {$login_url}\n" .
            "Uporabniški portal: {$portal_url}\n\n" .
            "Lep pozdrav,\n" .
            "ekipa ŠD Pohorje";

        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $from_name . ' <' . $from_email . '>',
        );

        $sent = wp_mail($user->user_email, $subject, $html_message, $headers);

        if (!$sent) {
            $fallback_headers = array('From: ' . $from_name . ' <' . $from_email . '>');
            $sent = wp_mail($user->user_email, $subject, $text_message, $fallback_headers);
        }

        if (!$sent) {
            error_log('SDP Accounts: failed to send registration email to ' . $user->user_email);
        }
    }
}
