<?php
/*
Plugin Name: Applicant Form
Description: A plugin to manage applicant information and submissions.
Version: 0.1
Author: Pronob Mozumder
Author URI: https://www.linkedin.com/in/pronob1010
Requires at least: 5.0 
Requires PHP: 7.0
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: applicant-form
*/

// Direct access is not allowed
if (!defined('ABSPATH')) {
    exit;
}

class Applicant_Form {
    protected $version = '0.1';
    protected $plugin_slug = 'applicant-form';
    protected $plugin_name = 'Applicant Form';

    public function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
    }


    private function define_constants() {
        define('APPLICANT_FORM_VERSION', $this->version);
        define('APPLICANT_FORM_SLUG', $this->plugin_slug);
        define('APPLICANT_FORM_NAME', $this->plugin_name);
        define( 'APPLICANT_FORM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
        define( 'APPLICANT_FORM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
    }

    private function includes() {
        require_once APPLICANT_FORM_PLUGIN_DIR . 'includes/class-applicant-form-db.php';
        require_once APPLICANT_FORM_PLUGIN_DIR . 'includes/class-email-handler.php';
        require_once APPLICANT_FORM_PLUGIN_DIR . 'includes/class-applicant-form-frontend.php';
        require_once APPLICANT_FORM_PLUGIN_DIR . 'includes/class-applicant-form-admin.php';
    }

    private function init_hooks() {
        register_activation_hook( __FILE__, array( 'Applicant_Form_DB', 'create_tables' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        new Applicant_Form_Frontend();
        new Applicant_Form_Admin();

        new Email_Handler();
    }

    public function enqueue_scripts() {
        wp_enqueue_style( 'tailwind_css', 'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css' );
        wp_enqueue_script( 'applicant_form_script', APPLICANT_FORM_PLUGIN_URL . 'js/applicant-form.js', array('jquery'), '1.0', true);
        wp_localize_script('applicant_form_script', 'applicant_form_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
        ));
    }
}


new Applicant_Form();