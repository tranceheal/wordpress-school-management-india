<?php
if (!defined('ABSPATH')) {
    exit;
}

class SMSI_Loader {
    protected $post_types;
    protected $meta_boxes;
    protected $admin;
    protected $shortcodes;
    protected $attendance;
    protected $fees;
    protected $exams;
    protected $auth;

    public function run() {
        $this->post_types = new SMSI_Post_Types();
        $this->meta_boxes = new SMSI_Meta_Boxes();
        $this->admin = new SMSI_Admin();
        $this->shortcodes = new SMSI_Shortcodes();
        $this->attendance = new SMSI_Attendance();
        $this->fees = new SMSI_Fees();
        $this->exams = new SMSI_Exams();
        $this->auth = new SMSI_Auth();

        add_action('init', array($this->post_types, 'register_post_types'));
        add_action('init', array($this->attendance, 'register_post_type'));
        add_action('init', array($this->fees, 'register_post_type'));
        add_action('init', array($this->exams, 'register_post_type'));
        add_action('init', array($this->auth, 'register_roles'));

        add_action('admin_post_smsi_export_csv', array($this->admin, 'export_csv'));
        add_action('add_meta_boxes', array($this->meta_boxes, 'register_meta_boxes'));
        add_action('save_post', array($this->meta_boxes, 'save_meta_boxes'));

        add_action('admin_menu', array($this->admin, 'register_menu_pages'));
        add_action('admin_menu', array($this->attendance, 'register_menu_pages'));
        add_action('admin_menu', array($this->fees, 'register_menu_pages'));
        add_action('admin_menu', array($this->exams, 'register_menu_pages'));
        add_action('admin_init', array($this->admin, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this->admin, 'enqueue_assets'));

        add_shortcode('smsi_student_directory', array($this->shortcodes, 'render_student_directory'));
        add_shortcode('smsi_teacher_directory', array($this->shortcodes, 'render_teacher_directory'));
        add_shortcode('smsi_school_noticeboard', array($this->shortcodes, 'render_noticeboard'));
        add_shortcode('smsi_attendance_board', array($this->attendance, 'render_attendance_board'));
        add_shortcode('smsi_fee_summary', array($this->fees, 'render_fee_summary'));
        add_shortcode('smsi_fee_reminders', array($this->fees, 'render_reminder_board'));
        add_shortcode('smsi_exam_results', array($this->exams, 'render_result_board'));
        add_shortcode('smsi_report_card', array($this->exams, 'render_report_card'));
        add_shortcode('smsi_student_login', array($this->auth, 'render_login_form'));
        add_shortcode('smsi_student_portal', array($this->auth, 'render_student_portal'));
        add_shortcode('smsi_parent_dashboard', array($this->auth, 'render_parent_dashboard'));
    }

    public static function activate() {
        if (class_exists('SMSI_Post_Types')) {
            $post_types = new SMSI_Post_Types();
            $post_types->register_post_types();
        }

        if (class_exists('SMSI_Attendance')) {
            $attendance = new SMSI_Attendance();
            $attendance->register_post_type();
        }

        if (class_exists('SMSI_Fees')) {
            $fees = new SMSI_Fees();
            $fees->register_post_type();
        }

        if (class_exists('SMSI_Exams')) {
            $exams = new SMSI_Exams();
            $exams->register_post_type();
        }

        if (class_exists('SMSI_Auth')) {
            $auth = new SMSI_Auth();
            $auth->register_roles();
        }

        flush_rewrite_rules();
    }

    public static function deactivate() {
        flush_rewrite_rules();
    }
}
