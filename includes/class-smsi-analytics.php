<?php
if (!defined('ABSPATH')) {
    exit;
}

class SMSI_Analytics {
    public function get_class_summary($class_name) {
        $class_name = trim((string) $class_name);
        if (empty($class_name)) {
            return array(
                'class_name' => '',
                'students' => array(),
                'average_percentage' => 0,
            );
        }

        $students = get_posts(array(
            'post_type' => 'smsi_student',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => 'smsi_student_class',
                    'value' => $class_name,
                    'compare' => '=',
                ),
            ),
        ));

        $student_rows = array();
        $class_total = 0;
        $count = 0;

        foreach ($students as $student) {
            $student_id = $student->ID;
            $results = get_posts(array(
                'post_type' => 'smsi_exam',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'meta_key' => 'smsi_exam_student_id',
                'meta_value' => (string) $student_id,
            ));

            $marks_obtained = 0;
            $total_marks = 0;

            foreach ($results as $result) {
                $marks_obtained += floatval(get_post_meta($result->ID, 'smsi_exam_marks_obtained', true));
                $total_marks += floatval(get_post_meta($result->ID, 'smsi_exam_total_marks', true));
            }

            $percentage = 0;
            if ($total_marks > 0) {
                $percentage = ($marks_obtained / $total_marks) * 100;
            }

            $student_rows[] = array(
                'student_id' => $student_id,
                'name' => get_the_title($student),
                'percentage' => $percentage,
            );

            $class_total += $percentage;
            $count++;
        }

        if ($count > 0) {
            $average = $class_total / $count;
        } else {
            $average = 0;
        }

        return array(
            'class_name' => $class_name,
            'students' => $student_rows,
            'average_percentage' => $average,
        );
    }

    public function render_class_analytics($atts = array()) {
        $atts = shortcode_atts(array(
            'class' => '',
        ), $atts, 'smsi_class_analytics');

        $class_name = trim((string) $atts['class']);

        if (empty($class_name)) {
            $students = get_posts(array(
                'post_type' => 'smsi_student',
                'post_status' => 'publish',
                'posts_per_page' => -1,
            ));

            $class_names = array();
            foreach ($students as $student) {
                $name = get_post_meta($student->ID, 'smsi_student_class', true);
                if (!empty($name)) {
                    $class_names[$name] = $name;
                }
            }

            if (empty($class_names)) {
                return '<p>' . __('No class data is available yet.', 'school-management-system-india') . '</p>';
            }

            $class_name = array_values($class_names)[0];
        }

        $summary = $this->get_class_summary($class_name);

        $rows = '';
        foreach ($summary['students'] as $student) {
            $rows .= '<tr>';
            $rows .= '<td>' . esc_html($student['name']) . '</td>';
            $rows .= '<td>' . esc_html(number_format((float) $student['percentage'], 2)) . '%</td>';
            $rows .= '</tr>';
        }

        if (empty($rows)) {
            $rows = '<tr><td colspan="2">' . __('No exam data available for this class.', 'school-management-system-india') . '</td></tr>';
        }

        $output = '<div class="smsi-class-analytics" style="max-width: 700px;">';
        $output .= '<h3>' . esc_html($summary['class_name']) . '</h3>';
        $output .= '<p><strong>' . __('Average Class Percentage', 'school-management-system-india') . ':</strong> ' . esc_html(number_format((float) $summary['average_percentage'], 2)) . '%</p>';
        $output .= '<table class="widefat"><thead><tr><th>' . __('Student', 'school-management-system-india') . '</th><th>' . __('Percentage', 'school-management-system-india') . '</th></tr></thead><tbody>' . $rows . '</tbody></table>';
        $output .= '</div>';

        return $output;
    }
}
