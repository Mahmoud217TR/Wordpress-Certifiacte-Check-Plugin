<?php

class CertificateCheck
{

    const ID = 'certificate-check';

    const NONCE_KEY = 'certificate_check_admin';

    protected $views = array(
        'main' => 'views/main',
        'edit' => 'views/edit',
        'form' => 'views/form',
        'alerts' => 'views/alerts',
    );
    const WHITELISTED_KEYS = array(
        'id',
        'certificate_number',
        'first_name',
        'last_name',
        'email',
        'mobile',
        'product',
        'issue_date',
        'exam_date',
        'result',
    );

    private $default_values = array();
    private $current_page = '';

    public function init()
    {
        add_action('admin_menu', array($this, 'add_menu_page'), 20);

        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        
        add_shortcode('certificate-check-form', array($this, 'certificate_check_form'));

        add_action('admin_post_certificate_check_admin_save', array($this, 'submit_save'));
        add_action('admin_post_certificate_check_admin_update', array($this, 'submit_update'));
        add_action('admin_post_certificate_check_admin_delete', array($this, 'submit_delete'));
        add_action('admin_post_certificate_check_query', array($this, 'verify_query'));
        add_action('admin_post_nopriv_certificate_check_query', array($this, 'verify_query'));
    }

    public function certificate_check_form()
    {
        $contnet = '';
        $this->current_page = 'form';
        $current_views = isset($this->views[$this->current_page]) ? $this->views[$this->current_page] : $this->views['not-found'];

        $step_data_func_name = $this->current_page . '_data';

        $args = [];
        /**
         * prepare data for view
         */
        if (method_exists($this, $step_data_func_name)) {
            $args = $this->$step_data_func_name();
        }
        /**
         * Default Admin Form Template
         */
        $contnet .='<div class="certificate-check ' . $this->current_page . '">';

        $contnet .= '<div class="container container1">';
        $contnet .= '<div class="inner">';

        $this->includeWithVariables(certificate_check_admin_template_server_path('views/alerts', false));

        $this->includeWithVariables(certificate_check_admin_template_server_path($current_views, false), $args);

        $contnet .= '</div>';
        $contnet .= '</div>';

        $contnet .= '</div>';
        return $contnet;
    }

    public function get_id()
    {
        return self::ID;
    }

    public function get_nonce_key()
    {
        return self::NONCE_KEY;
    }

    public function get_whitelisted_keys()
    {
        return self::WHITELISTED_KEYS;
    }

    private function get_defaults()
    {
        $defaults = array();
        foreach ($this->get_whitelisted_keys() as $key => $val) {
            $defaults[$val] = get_option($val);
        }
        return $defaults;
    }


    public function add_menu_page()
    {
        add_menu_page(
            esc_html__('Certificate Check', 'certificate-check'),
            esc_html__('Certificate Check', 'certificate-check'),
            'manage_options',
            $this->get_id(),
            array(&$this, 'load_view'),
            'dashicons-admin-page'
        );

        add_plugins_page(
            esc_html__('Certificate Check', 'certificate-check'),
            esc_html__('Certificate Check', 'certificate-check'),
            'manage_options',
            $this->get_id().'_edit',
            array(&$this, 'load_view')
        );
    }


    function load_view()
    {
        $this->default_values = $this->get_defaults();
        $this->current_page = certificate_check_admin_current_view();

        $current_views = isset($this->views[$this->current_page]) ? $this->views[$this->current_page] : $this->views['not-found'];

        $step_data_func_name = $this->current_page . '_data';

        $args = [];
        /**
         * prepare data for view
         */
        if (method_exists($this, $step_data_func_name)) {
            $args = $this->$step_data_func_name();
        }
        /**
         * Default Admin Form Template
         */

        echo '<div class="certificate-check ' . $this->current_page . '">';

        echo '<div class="container container1">';
        echo '<div class="inner">';

        $this->includeWithVariables(certificate_check_admin_template_server_path('views/alerts', false));

        $this->includeWithVariables(certificate_check_admin_template_server_path($current_views, false), $args);

        echo '</div>';
        echo '</div>';

        echo '</div>';
    }


    function includeWithVariables($filePath, $variables = array(), $print = true)
    {
        $output = NULL;
        if (file_exists($filePath)) {
            // Extract the variables to a local namespace
            extract($variables);

            // Start output buffering
            ob_start();

            // Include the template file
            include $filePath;

            // End buffering and return its contents
            $output = ob_get_clean();
        }
        if ($print) {
            print $output;
        }
        return $output;

    }


    public function admin_enqueue_scripts($hook_suffix)
    {
        if (strpos($hook_suffix, $this->get_id()) === false) {
            return;
        }

        wp_enqueue_style('certificate-check-bs', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css', CERTIFICATE_CHECK_VERSION);
        wp_enqueue_style('certificate-check-bs-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css', CERTIFICATE_CHECK_VERSION);

        wp_enqueue_script('certificate-check-bs', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js',
            array('jquery'),
            CERTIFICATE_CHECK_VERSION,
            true
        );


        wp_enqueue_style('certificate-check', certificate_check_admin_url('assets/style.css'), CERTIFICATE_CHECK_VERSION);

        wp_enqueue_script('certificate-check-js', certificate_check_admin_url('assets/custom.js'),
            array('jquery'),
            CERTIFICATE_CHECK_VERSION,
            true
        );
    }


    public function submit_save()
    {
        $nonce = sanitize_text_field($_POST[$this->get_nonce_key()]);
        $action = sanitize_text_field($_POST['action']);

        if (!isset($nonce) || !wp_verify_nonce($nonce, $action)) {
            print 'Sorry, your nonce did not verify.';
            exit;
        }
        if (!current_user_can('manage_options')) {
            print 'You can\'t manage options';
            exit;
        }
        /**
         * whitelist keys that can be updated
         */
        $whitelisted_keys = $this->get_whitelisted_keys();

        $fields_to_update = [];

        foreach ($whitelisted_keys as $key) {
            if (array_key_exists($key, $_POST)) {
                $fields_to_update[$key] = $_POST[$key];
            }
        }

        /**
         * Loop through form fields keys and update data in DB (wp_options)
         */
        $this->db_create_certificate($fields_to_update);

        $redirect_to = $_POST['redirectToUrl'];

        if ($redirect_to) {
            add_settings_error('ct_msg', 'ct_msg_option', __("Certificate added."), 'success');
            set_transient('settings_errors', get_settings_errors(), 30);
            wp_safe_redirect($redirect_to);
            exit;
        }
    }

    public function submit_update()
    {
        $nonce = sanitize_text_field($_POST[$this->get_nonce_key()]);
        $action = sanitize_text_field($_POST['action']);

        if (!isset($nonce) || !wp_verify_nonce($nonce, $action)) {
            print 'Sorry, your nonce did not verify.';
            exit;
        }
        if (!current_user_can('manage_options')) {
            print 'You can\'t manage options';
            exit;
        }
        /**
         * whitelist keys that can be updated
         */
        $whitelisted_keys = $this->get_whitelisted_keys();

        $fields_to_update = [];

        foreach ($whitelisted_keys as $key) {
            if (array_key_exists($key, $_POST)) {
                $fields_to_update[$key] = $_POST[$key];
            }
        }

        /**
         * Loop through form fields keys and update data in DB (wp_options)
         */
        $this->db_update_certificate($fields_to_update);
        
        $redirect_to = $_POST['redirectToUrl'];

        if ($redirect_to) {
            add_settings_error('ct_msg', 'ct_msg_option', __("Certificate updated."), 'success');
            set_transient('settings_errors', get_settings_errors(), 30);
            wp_safe_redirect($redirect_to);
            exit;
        }
    }

    public function submit_delete()
    {
        $nonce = sanitize_text_field($_POST[$this->get_nonce_key()]);
        $action = sanitize_text_field($_POST['action']);
        
        if (!isset($nonce) || !wp_verify_nonce($nonce, $action)) {
            print 'Sorry, your nonce did not verify.';
            exit;
        }
        if (!current_user_can('manage_options')) {
            print 'You can\'t manage options';
            exit;
        }
        /**
         * whitelist keys that can be updated
         */
        $key = 'id';
        $id = null;

        if (array_key_exists($key, $_POST)) {
            $id = $_POST[$key];
        }

        $this->delete_certificate_table($id);

        $redirect_to = $_POST['redirectToUrl'];

        if ($redirect_to) {
            add_settings_error('ct_msg', 'ct_msg_option', __("Certificate Deleted."), 'success');
            set_transient('settings_errors', get_settings_errors(), 30);
            wp_safe_redirect($redirect_to);
            exit;
        }
    }

    public function verify_query()
    {
        $nonce = sanitize_text_field($_POST[$this->get_nonce_key()]);
        $action = sanitize_text_field($_POST['action']);
        
        if (!isset($nonce) || !wp_verify_nonce($nonce, $action)) {
            print 'Sorry, your nonce did not verify.';
            exit;
        }
        /**
         * whitelist keys that can be updated
         */
        $key = 'certificate_number';
        $certificate_number = null;

        if (array_key_exists($key, $_POST)) {
            $certificate_number = trim($_POST[$key]);
        }
        
        $certificate_id = -1;
        $go_to = '#verify-form';
        $redirect_to = $_POST['redirectToUrl'];

        $certificate = verify_certificate($certificate_number)[0];
        if(!is_null($certificate->id)) {
            $certificate_id = $certificate->id;
            $go_to = '#certificate';
        }
        
        $redirect_url = esc_url(add_query_arg('certificate_id', $certificate_id, $redirect_to.$go_to));

        wp_safe_redirect($redirect_url);
        exit;
    }

    private function db_create_certificate($group)
    {
        $this->insert_into_certificate_table(
            trim($group['certificate_number']),
            $group['first_name'],
            $group['last_name'],
            $group['email'],
            $group['mobile'],
            $group['product'],
            $group['result'],
            $group['issue_date'],
            $group['exam_date']
        );
    }

    private function insert_into_certificate_table(
        string $certificate_number,
        string $first_name,
        string $last_name,
        string $email,
        string $mobile,
        string $product,
        string $result,
        $issue_date,
        $exam_date
    ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'cer_ch_certificates';
        $issue_date = date('Y-m-d', strtotime($issue_date));
        $exam_date = date('Y-m-d', strtotime($exam_date));
        $query = "INSERT INTO $table_name(id, certificate_number, first_name, last_name, email, mobile, product, result, issue_date, exam_date) VALUES(NULL, '$certificate_number', '$first_name', '$last_name', '$email', '$mobile', '$product', '$result', '$issue_date', '$exam_date')";
        $wpdb->query($query);
    }

    private function db_update_certificate($group)
    {
        $this->update_certificate_table(
            $group['id'],
            trim($group['certificate_number']),
            $group['first_name'],
            $group['last_name'],
            $group['email'],
            $group['mobile'],
            $group['product'],
            $group['result'],
            $group['issue_date'],
            $group['exam_date']
        );
    }

    private function update_certificate_table(
        int $id,
        string $certificate_number,
        string $first_name,
        string $last_name,
        string $email,
        string $mobile,
        string $product,
        string $result,
        $issue_date,
        $exam_date
    ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'cer_ch_certificates';
        $issue_date = date('Y-m-d', strtotime($issue_date));
        $exam_date = date('Y-m-d', strtotime($exam_date));
        $query = "UPDATE $table_name SET certificate_number = '$certificate_number', first_name = '$first_name', last_name = '$last_name', email = '$email', mobile = '$mobile', product = '$product', result = '$result', issue_date = '$issue_date', exam_date = '$exam_date' WHERE id = $id;";
        $wpdb->query($query);
    }

    private function delete_certificate_table(int $id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'cer_ch_certificates';
        $query = "DELETE FROM $table_name WHERE id = $id;";
        $wpdb->query($query);
    }
}