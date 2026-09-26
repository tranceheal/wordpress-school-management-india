<?php
if (!defined('ABSPATH')) {
    exit;
}

class SMSI_Loader {
    protected $post_types;
    protected $admin;
    protected $shortcodes;

    public function run() {
        $this->post_types = new SMSI_Post_Types();
        $this->admin = new SMSI_Admin();
        $this->shortcodes = new SMSI_Shortcodes();

        add_action('init', array($this->post_types, 'register_post_types'));
        add_action('admin_menu', array($this->admin, 'register_menu_pages'));
        add_action('admin_init', array($this->admin, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this->admin, 'enqueue_assets'));
        add_shortcode('smsi_student_directory', array($this->shortcodes, 'render_student_directory'));
        add_shortcode('smsi_teacher_directory', array($this->shortcodes, 'render_teacher_directory'));
        add_shortcode('smsi_school_noticeboard', array($this->shortcodes, 'render_noticeboard'));
    }

    public static function activate() {
        if (class_exists('SMSI_Post_Types')) {
            $post_types = new SMSI_Post_Types();
            $post_types->register_post_types();
        }

        flush_rewrite_rules();
    }

    public static function deactivate() {
        flush_rewrite_rules();
    }
}
