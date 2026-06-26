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

        add_filter('authenticate', array($this, 'block_pending_users'), 30, 3);

        add_action('admin_menu', array($this, 'register_admin_pages'));
        add_action('admin_post_sdp_account_action', array($this, 'handle_admin_account_action'));
        add_action('admin_post_sdp_link_action', array($this, 'handle_admin_link_action'));
    }

    public function register_roles_runtime()
    {
        self::register_roles();
    }

    public function enqueue_assets()
    {
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
        }
    }

    private function nonce_ok($name)
    {
        return isset($_POST['_wpnonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), $name);
    }

    private function redirect_with_message($type, $message)
    {
        $url = wp_get_referer();

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
        $username = isset($_POST['username']) ? sanitize_user(wp_unslash($_POST['username']), true) : '';
        $password = isset($_POST['password']) ? wp_unslash($_POST['password']) : '';
        $password_confirm = isset($_POST['password_confirm']) ? wp_unslash($_POST['password_confirm']) : '';
        $terms = isset($_POST['terms']) ? (int) $_POST['terms'] : 0;
        $privacy = isset($_POST['privacy']) ? (int) $_POST['privacy'] : 0;

        if (empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($username) || empty($password)) {
            $this->redirect_with_message('error', 'Prosimo, izpolnite vsa obvezna polja.');
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

        $this->send_admin_registration_notice($user_id, $role);

        $this->redirect_with_message('success', 'Registracija je uspela. Vaš račun čaka na odobritev skrbnika.');
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

        wp_safe_redirect(home_url('/'));
        exit;
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
                return new WP_Error('pending_account', 'Vaš račun še ni odobren. Prosimo, počakajte na potrditev skrbnika.');
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
            'SDP Accounts',
            'SDP Accounts',
            'manage_options',
            'sdp-accounts',
            array($this, 'render_pending_accounts_page'),
            'dashicons-groups'
        );

        add_submenu_page(
            'sdp-accounts',
            'Pending Accounts',
            'Pending Accounts',
            'manage_options',
            'sdp-accounts',
            array($this, 'render_pending_accounts_page')
        );

        add_submenu_page(
            'sdp-accounts',
            'Parent-Athlete Links',
            'Parent-Athlete Links',
            'manage_options',
            'sdp-account-links',
            array($this, 'render_account_links_page')
        );
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
        $type = isset($_GET['sdp_notice_type']) ? sanitize_text_field(wp_unslash($_GET['sdp_notice_type'])) : '';
        $message = isset($_GET['sdp_notice_message']) ? sanitize_text_field(wp_unslash($_GET['sdp_notice_message'])) : '';

        if (!$type || !$message) {
            return '';
        }

        $class = $type === 'success' ? 'sdp-notice-success' : 'sdp-notice-error';

        return '<div class="sdp-notice ' . esc_attr($class) . '">' . esc_html($message) . '</div>';
    }

    public function render_athlete_registration()
    {
        ob_start();
        ?>
        <div class="sdp-auth-wrap">
            <?php echo wp_kses_post($this->get_notice_html()); ?>
            <div class="sdp-auth-card">
                <h2>Registracija atleta</h2>
                <form method="post" class="sdp-form-grid">
                    <?php wp_nonce_field('sdp_register_nonce'); ?>
                    <input type="hidden" name="sdp_action" value="register_athlete" />

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
                    <label>Telefon starša/skrbnika *<input name="phone" type="text" required></label>
                    <label>Uporabniško ime *<input name="username" type="text" required></label>
                    <label>Geslo *<input name="password" type="password" minlength="8" required></label>
                    <label>Potrditev gesla *<input name="password_confirm" type="password" minlength="8" required></label>

                    <label class="sdp-check"><input type="checkbox" name="privacy" value="1" required> Soglašam s politiko zasebnosti *</label>
                    <label class="sdp-check"><input type="checkbox" name="terms" value="1" required> Strinjam se s pogoji uporabe *</label>

                    <button type="submit" class="sdp-btn-primary">Ustvari račun</button>
                </form>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_parent_registration()
    {
        ob_start();
        ?>
        <div class="sdp-auth-wrap">
            <?php echo wp_kses_post($this->get_notice_html()); ?>
            <div class="sdp-auth-card">
                <h2>Registracija starša</h2>
                <form method="post" class="sdp-form-grid">
                    <?php wp_nonce_field('sdp_register_nonce'); ?>
                    <input type="hidden" name="sdp_action" value="register_parent" />

                    <label>Ime *<input name="first_name" type="text" required></label>
                    <label>Priimek *<input name="last_name" type="text" required></label>
                    <label>E-poštni naslov *<input name="email" type="email" required></label>
                    <label>Telefon *<input name="phone" type="text" required></label>
                    <label>Uporabniško ime *<input name="username" type="text" required></label>
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
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_login_form()
    {
        ob_start();
        ?>
        <div class="sdp-auth-wrap">
            <?php echo wp_kses_post($this->get_notice_html()); ?>
            <div class="sdp-auth-card">
                <h2>Prijava</h2>
                <form method="post" class="sdp-form-grid">
                    <?php wp_nonce_field('sdp_login_nonce'); ?>
                    <input type="hidden" name="sdp_action" value="login" />

                    <label>Uporabniško ime ali e-pošta *<input name="login" type="text" required></label>
                    <label>Geslo *<input name="password" type="password" required></label>
                    <label class="sdp-check"><input type="checkbox" name="remember" value="1"> Zapomni si me</label>

                    <button type="submit" class="sdp-btn-primary">Prijava</button>
                </form>
                <p class="sdp-helper-links"><a href="?show=forgot">Ste pozabili geslo?</a></p>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_forgot_password_form()
    {
        ob_start();
        ?>
        <div class="sdp-auth-wrap">
            <?php echo wp_kses_post($this->get_notice_html()); ?>
            <div class="sdp-auth-card">
                <h2>Pozabljeno geslo</h2>
                <form method="post" class="sdp-form-grid">
                    <?php wp_nonce_field('sdp_forgot_nonce'); ?>
                    <input type="hidden" name="sdp_action" value="forgot_password" />

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

        ob_start();
        ?>
        <div class="sdp-auth-wrap">
            <?php echo wp_kses_post($this->get_notice_html()); ?>
            <div class="sdp-auth-card">
                <h2>Ponastavitev gesla</h2>
                <form method="post" class="sdp-form-grid">
                    <?php wp_nonce_field('sdp_reset_nonce'); ?>
                    <input type="hidden" name="sdp_action" value="reset_password" />
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

    private function send_admin_registration_notice($user_id, $role)
    {
        $admin_email = get_option('admin_email');
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
}
