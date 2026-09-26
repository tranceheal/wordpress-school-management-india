<?php
if (!defined('ABSPATH')) {
    exit;
}

class SMSI_Shortcodes {
    public function render_student_directory($atts = array()) {
        $atts = shortcode_atts(array('limit' => 10), $atts, 'smsi_student_directory');
        $students = get_posts(array('post_type' => 'smsi_student', 'post_status' => 'publish', 'posts_per_page' => absint($atts['limit'])));

        if (empty($students)) {
            return '<p>' . __('No student records found.', 'school-management-system-india') . '</p>';
        }

        $output = '<div class="smsi-directory">';
        foreach ($students as $student) {
            $class_name = get_post_meta($student->ID, 'smsi_student_class', true);
            $output .= '<div class="smsi-directory-item">';
            $output .= '<h3>' . esc_html(get_the_title($student)) . '</h3>';
            $output .= '<p><strong>' . __('Class', 'school-management-system-india') . ':</strong> ' . esc_html($class_name) . '</p>';
            $output .= '<div>' . wp_kses_post($student->post_content) . '</div>';
            $output .= '</div>';
        }
        $output .= '</div>';

        return $output;
    }

    public function render_teacher_directory($atts = array()) {
        $atts = shortcode_atts(array('limit' => 10), $atts, 'smsi_teacher_directory');
        $teachers = get_posts(array('post_type' => 'smsi_teacher', 'post_status' => 'publish', 'posts_per_page' => absint($atts['limit'])));

        if (empty($teachers)) {
            return '<p>' . __('No teacher records found.', 'school-management-system-india') . '</p>';
        }

        $output = '<div class="smsi-directory">';
        foreach ($teachers as $teacher) {
            $department = get_post_meta($teacher->ID, 'smsi_teacher_department', true);
            $output .= '<div class="smsi-directory-item">';
            $output .= '<h3>' . esc_html(get_the_title($teacher)) . '</h3>';
            $output .= '<p><strong>' . __('Department', 'school-management-system-india') . ':</strong> ' . esc_html($department) . '</p>';
            $output .= '<div>' . wp_kses_post($teacher->post_content) . '</div>';
            $output .= '</div>';
        }
        $output .= '</div>';

        return $output;
    }

    public function render_noticeboard($atts = array()) {
        $atts = shortcode_atts(array('limit' => 5), $atts, 'smsi_school_noticeboard');
        $notices = get_posts(array('post_type' => 'smsi_notice', 'post_status' => 'publish', 'posts_per_page' => absint($atts['limit'])));

        if (empty($notices)) {
            return '<p>' . __('No notices published yet.', 'school-management-system-india') . '</p>';
        }

        $output = '<div class="smsi-noticeboard">';
        foreach ($notices as $notice) {
            $output .= '<div class="smsi-notice">';
            $output .= '<h3>' . esc_html(get_the_title($notice)) . '</h3>';
            $output .= '<div>' . wp_kses_post($notice->post_content) . '</div>';
            $output .= '</div>';
        }
        $output .= '</div>';

        return $output;
    }
}
