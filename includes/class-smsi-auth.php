<?php
if (!defined('ABSPATH')) {
    exit;
}

class SMSI_Auth {
    public function register_roles() {
        if (!get_role('smsi_student')) {
            add_role(
                'smsi_student',
                __('SMSI Student', 'school-management-system-india'),
                array(
                    'read' => true,
                    'level_0' => true,
                )
            );
        }

        if (!get_role('smsi_parent')) {
            add_role(
                'smsi_parent',
                __('SMSI Parent', 'school-management-system-india'),
                array(
                    'read' => true,
                    'level_0' => true,
                )
            );
        }
    }

    public function render_login_form($atts = array()) {
        if (is_user_logged_in()) {
            return $this->render_student_portal();
        }

        return wp_login_form(array(
            'echo' => false,
            'redirect' => home_url(),
        ));
    }

    public function render_student_portal($atts = array()) {
        if (!is_user_logged_in()) {
            return '<p>' . __('Please log in to view your student portal.', 'school-management-system-india') . '</p>';
        }

        $current_user = wp_get_current_user();
        $student_id = get_user_meta($current_user->ID, 'smsi_student_id', true);
        $student_post = $student_id ? get_post($student_id) : null;

        if (!$student_post && current_user_can('smsi_student')) {
            $student_posts = get_posts(array(
                'post_type' => 'smsi_student',
                'post_status' => 'publish',
                'posts_per_page' => 1,
                'author' => $current_user->ID,
            ));
            $student_post = !empty($student_posts) ? $student_posts[0] : null;
        }

        if (!$student_post) {
            return '<p>' . __('No student profile is linked to your account.', 'school-management-system-india') . '</p>';
        }

        $class_name = get_post_meta($student_post->ID, 'smsi_student_class', true);
        $guardian_name = get_post_meta($student_post->ID, 'smsi_student_guardian_name', true);
        $contact_number = get_post_meta($student_post->ID, 'smsi_student_contact_number', true);

        $exams = new SMSI_Exams();
        $summary = $exams->get_student_result_summary($student_post->ID);
        $percentage = number_format((float) $summary['percentage'], 2);

        $output  = '<div class="smsi-student-portal">';
        $output .= '<h3>' . esc_html(get_the_title($student_post)) . '</h3>';
        $output .= '<p><strong>' . __('Class', 'school-management-system-india') . ':</strong> ' . esc_html($class_name ?: __('N/A', 'school-management-system-india')) . '</p>';
        $output .= '<p><strong>' . __('Parent / Guardian', 'school-management-system-india') . ':</strong> ' . esc_html($guardian_name ?: __('N/A', 'school-management-system-india')) . '</p>';
        $output .= '<p><strong>' . __('Contact Number', 'school-management-system-india') . ':</strong> ' . esc_html($contact_number ?: __('N/A', 'school-management-system-india')) . '</p>';
        $output .= '<p><strong>' . __('Overall Percentage', 'school-management-system-india') . ':</strong> ' . esc_html($percentage) . '%</p>';
        $output .= '</div>';

        return $output;
    }

    public function render_parent_dashboard($atts = array()) {
        if (!is_user_logged_in()) {
            return '<p>' . __('Please log in to view the parent dashboard.', 'school-management-system-india') . '</p>';
        }

        $student_posts = get_posts(array(
            'post_type' => 'smsi_student',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ));

        if (empty($student_posts)) {
            return '<p>' . __('No student records available.', 'school-management-system-india') . '</p>';
        }

        $exams = new SMSI_Exams();
        $output = '<div class="smsi-parent-dashboard"><table class="widefat"><thead><tr><th>' . __('Student', 'school-management-system-india') . '</th><th>' . __('Class', 'school-management-system-india') . '</th><th>' . __('Parent', 'school-management-system-india') . '</th><th>' . __('Overall %', 'school-management-system-india') . '</th><th>' . __('Marks Summary', 'school-management-system-india') . '</th></tr></thead><tbody>';

        foreach ($student_posts as $student) {
            $class_name = get_post_meta($student->ID, 'smsi_student_class', true);
            $guardian_name = get_post_meta($student->ID, 'smsi_student_guardian_name', true);
            $summary = $exams->get_student_result_summary($student->ID);
            $percentage = number_format((float) $summary['percentage'], 2) . '%';
            $subject_count = count($summary['subjects']);
            $subject_label = $subject_count > 0 ? sprintf(_n('%s subject', '%s subjects', $subject_count, 'school-management-system-india'), number_format_i18n($subject_count)) : __('No marks yet', 'school-management-system-india');

            $output .= '<tr>';
            $output .= '<td>' . esc_html(get_the_title($student)) . '</td>';
            $output .= '<td>' . esc_html($class_name ?: __('N/A', 'school-management-system-india')) . '</td>';
            $output .= '<td>' . esc_html($guardian_name ?: __('Not provided', 'school-management-system-india')) . '</td>';
            $output .= '<td>' . esc_html($percentage) . '</td>';
            $output .= '<td>' . esc_html($subject_label) . '</td>';
            $output .= '</tr>';
        }

        $output .= '</tbody></table></div>';

        return $output;
    }
}
