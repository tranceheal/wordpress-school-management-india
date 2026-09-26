<?php
if (!defined('ABSPATH')) {
    exit;
}

class SMSI_Attendance {
    public function register_post_type() {
        register_post_type(
            'smsi_attendance',
            array(
                'labels' => array(
                    'name' => __('Attendance', 'school-management-system-india'),
                    'singular_name' => __('Attendance Record', 'school-management-system-india'),
                    'add_new_item' => __('Add Attendance Record', 'school-management-system-india'),
                    'edit_item' => __('Edit Attendance Record', 'school-management-system-india'),
                ),
                'public' => true,
                'has_archive' => true,
                'menu_icon' => 'dashicons-calendar-alt',
                'supports' => array('title', 'editor'),
                'show_in_rest' => true,
                'rewrite' => array('slug' => 'attendance'),
            )
        );
    }

    public function register_menu_pages() {
        add_submenu_page('smsi-dashboard', __('Attendance', 'school-management-system-india'), __('Attendance', 'school-management-system-india'), 'manage_options', 'edit.php?post_type=smsi_attendance');
    }

    public function render_attendance_board($atts = array()) {
        $atts = shortcode_atts(array('limit' => 10), $atts, 'smsi_attendance_board');
        $students = get_posts(array('post_type' => 'smsi_student', 'posts_per_page' => absint($atts['limit']), 'post_status' => 'publish'));

        if (empty($students)) {
            return '<p>' . __('No student records available for attendance.', 'school-management-system-india') . '</p>';
        }

        $output = '<div class="smsi-attendance-board"><table class="widefat"><thead><tr><th>' . __('Student', 'school-management-system-india') . '</th><th>' . __('Class', 'school-management-system-india') . '</th><th>' . __('Attendance', 'school-management-system-india') . '</th></tr></thead><tbody>';

        foreach ($students as $student) {
            $class_name = get_post_meta($student->ID, 'smsi_student_class', true);
            $attendance = get_post_meta($student->ID, 'smsi_attendance_percentage', true);
            $output .= '<tr>';
            $output .= '<td>' . esc_html(get_the_title($student)) . '</td>';
            $output .= '<td>' . esc_html($class_name) . '</td>';
            $output .= '<td>' . esc_html($attendance ? $attendance : '0%') . '</td>';
            $output .= '</tr>';
        }

        $output .= '</tbody></table></div>';

        return $output;
    }
}
