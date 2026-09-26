<?php
if (!defined('ABSPATH')) {
    exit;
}

class SMSI_Exams {
    public function register_post_type() {
        register_post_type(
            'smsi_exam',
            array(
                'labels' => array(
                    'name' => __('Exams', 'school-management-system-india'),
                    'singular_name' => __('Exam', 'school-management-system-india'),
                    'add_new_item' => __('Add Exam Result', 'school-management-system-india'),
                    'edit_item' => __('Edit Exam Result', 'school-management-system-india'),
                ),
                'public' => true,
                'has_archive' => true,
                'menu_icon' => 'dashicons-clipboard',
                'supports' => array('title', 'editor'),
                'show_in_rest' => true,
                'rewrite' => array('slug' => 'exams'),
            )
        );
    }

    public function register_menu_pages() {
        add_submenu_page(
            'smsi-dashboard',
            __('Exam Results', 'school-management-system-india'),
            __('Exam Results', 'school-management-system-india'),
            'manage_options',
            'edit.php?post_type=smsi_exam'
        );
    }

    public function render_result_board($atts = array()) {
        $atts = shortcode_atts(array(
            'limit' => 20,
        ), $atts, 'smsi_exam_results');

        $results = get_posts(array(
            'post_type' => 'smsi_exam',
            'post_status' => 'publish',
            'posts_per_page' => absint($atts['limit']),
        ));

        if (empty($results)) {
            return '<p>' . __('No exam results available yet.', 'school-management-system-india') . '</p>';
        }

        $output = '<div class="smsi-exam-results"><table class="widefat"><thead><tr><th>' . __('Student', 'school-management-system-india') . '</th><th>' . __('Subject', 'school-management-system-india') . '</th><th>' . __('Marks', 'school-management-system-india') . '</th><th>' . __('Grade', 'school-management-system-india') . '</th></tr></thead><tbody>';

        foreach ($results as $result) {
            $student_id = get_post_meta($result->ID, 'smsi_exam_student_id', true);
            $student_name = $student_id ? get_the_title($student_id) : get_the_title($result);
            $subject = get_post_meta($result->ID, 'smsi_exam_subject', true);
            $marks = get_post_meta($result->ID, 'smsi_exam_marks_obtained', true);
            $total_marks = get_post_meta($result->ID, 'smsi_exam_total_marks', true);
            $grade = get_post_meta($result->ID, 'smsi_exam_grade', true);

            $output .= '<tr>';
            $output .= '<td>' . esc_html($student_name) . '</td>';
            $output .= '<td>' . esc_html($subject ?: __('N/A', 'school-management-system-india')) . '</td>';
            $output .= '<td>' . esc_html($marks ?: '0') . ' / ' . esc_html($total_marks ?: '0') . '</td>';
            $output .= '<td>' . esc_html($grade ?: __('Pending', 'school-management-system-india')) . '</td>';
            $output .= '</tr>';
        }

        $output .= '</tbody></table></div>';

        return $output;
    }

    public function render_report_card($atts = array()) {
        $atts = shortcode_atts(array(
            'student_id' => 0,
        ), $atts, 'smsi_report_card');

        $student_id = absint($atts['student_id']);
        if (empty($student_id)) {
            $students = get_posts(array(
                'post_type' => 'smsi_student',
                'post_status' => 'publish',
                'posts_per_page' => 1,
            ));
            if (!empty($students)) {
                $student_id = $students[0]->ID;
            }
        }

        if (empty($student_id)) {
            return '<p>' . __('No student has been added yet.', 'school-management-system-india') . '</p>';
        }

        $student = get_post($student_id);
        if (!$student) {
            return '<p>' . __('Student not found.', 'school-management-system-india') . '</p>';
        }

        $results = get_posts(array(
            'post_type' => 'smsi_exam',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_key' => 'smsi_exam_student_id',
            'meta_value' => (string) $student_id,
        ));

        $settings = get_option('smsi_school_settings', array());
        $school_name = !empty($settings['school_name']) ? $settings['school_name'] : __('School', 'school-management-system-india');
        $board_name = !empty($settings['board_name']) ? $settings['board_name'] : __('Board', 'school-management-system-india');
        $session_name = !empty($settings['academic_session']) ? $settings['academic_session'] : __('Current Session', 'school-management-system-india');
        $class_name = get_post_meta($student_id, 'smsi_student_class', true);

        $total_marks = 0;
        $obtainable = 0;
        $rows = '';

        foreach ($results as $result) {
            $subject = get_post_meta($result->ID, 'smsi_exam_subject', true);
            $marks = (float) get_post_meta($result->ID, 'smsi_exam_marks_obtained', true);
            $max_marks = (float) get_post_meta($result->ID, 'smsi_exam_total_marks', true);
            $grade = get_post_meta($result->ID, 'smsi_exam_grade', true);

            $total_marks += $marks;
            $obtainable += $max_marks;

            $rows .= '<tr>';
            $rows .= '<td>' . esc_html($subject ?: __('Subject', 'school-management-system-india')) . '</td>';
            $rows .= '<td>' . esc_html($marks) . '</td>';
            $rows .= '<td>' . esc_html($max_marks) . '</td>';
            $rows .= '<td>' . esc_html($grade ?: __('Pending', 'school-management-system-india')) . '</td>';
            $rows .= '</tr>';
        }

        $percentage = 0;
        if ($obtainable > 0) {
            $percentage = ($total_marks / $obtainable) * 100;
        }

        $output = '<div class="smsi-report-card" style="border:1px solid #ddd; padding:20px; max-width:700px; background:#fff;">';
        $output .= '<h2>' . esc_html($school_name) . '</h2>';
        $output .= '<p><strong>' . __('Board', 'school-management-system-india') . ':</strong> ' . esc_html($board_name) . ' &nbsp; <strong>' . __('Session', 'school-management-system-india') . ':</strong> ' . esc_html($session_name) . '</p>';
        $output .= '<h3>' . esc_html(get_the_title($student)) . '</h3>';
        $output .= '<p><strong>' . __('Class', 'school-management-system-india') . ':</strong> ' . esc_html($class_name ?: __('N/A', 'school-management-system-india')) . '</p>';
        $output .= '<table class="widefat" style="border-collapse:collapse; width:100%;"><thead><tr><th>' . __('Subject', 'school-management-system-india') . '</th><th>' . __('Marks Obtained', 'school-management-system-india') . '</th><th>' . __('Total Marks', 'school-management-system-india') . '</th><th>' . __('Grade', 'school-management-system-india') . '</th></tr></thead><tbody>';
        $output .= $rows ?: '<tr><td colspan="4">' . __('No results recorded yet.', 'school-management-system-india') . '</td></tr>';
        $output .= '</tbody></table>';
        $output .= '<p><strong>' . __('Overall Percentage', 'school-management-system-india') . ':</strong> ' . esc_html(number_format($percentage, 2)) . '%</p>';
        $output .= '</div>';

        return $output;
    }
}
