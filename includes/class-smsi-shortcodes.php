<?php
if (!defined('ABSPATH')) {
    exit;
}

class SMSI_Meta_Boxes {
    public function register_meta_boxes() {
        $screen_post_types = array('smsi_student', 'smsi_teacher', 'smsi_notice', 'smsi_exam');

        foreach ($screen_post_types as $post_type) {
            add_meta_box(
                'smsi_' . $post_type . '_details',
                __('School Details', 'school-management-system-india'),
                array($this, 'render_meta_box'),
                $post_type,
                'normal',
                'default',
                array('post_type' => $post_type)
            );
        }
    }

    public function render_meta_box($post, $metabox) {
        $post_type = $metabox['args']['post_type'];
        wp_nonce_field('smsi_meta_box_nonce', 'smsi_meta_box_nonce');

        if ('smsi_student' === $post_type) {
            $fields = array(
                'smsi_student_class' => array('label' => __('Class', 'school-management-system-india'), 'type' => 'text'),
                'smsi_student_admission_no' => array('label' => __('Admission No.', 'school-management-system-india'), 'type' => 'text'),
                'smsi_student_guardian_name' => array('label' => __('Parent / Guardian', 'school-management-system-india'), 'type' => 'text'),
                'smsi_student_contact_number' => array('label' => __('Contact Number', 'school-management-system-india'), 'type' => 'text'),
                'smsi_student_dob' => array('label' => __('Date of Birth', 'school-management-system-india'), 'type' => 'date'),
            );
        } elseif ('smsi_teacher' === $post_type) {
            $fields = array(
                'smsi_teacher_department' => array('label' => __('Department', 'school-management-system-india'), 'type' => 'text'),
                'smsi_teacher_designation' => array('label' => __('Designation', 'school-management-system-india'), 'type' => 'text'),
                'smsi_teacher_phone' => array('label' => __('Phone Number', 'school-management-system-india'), 'type' => 'text'),
                'smsi_teacher_joining_date' => array('label' => __('Joining Date', 'school-management-system-india'), 'type' => 'date'),
            );
        } elseif ('smsi_notice' === $post_type) {
            $fields = array(
                'smsi_notice_target' => array('label' => __('Audience', 'school-management-system-india'), 'type' => 'text'),
                'smsi_notice_expiry_date' => array('label' => __('Expiry Date', 'school-management-system-india'), 'type' => 'date'),
            );
        } else {
            $fields = array(
                'smsi_exam_student_id' => array('label' => __('Student', 'school-management-system-india'), 'type' => 'number'),
                'smsi_exam_subject' => array('label' => __('Subject', 'school-management-system-india'), 'type' => 'text'),
                'smsi_exam_marks_obtained' => array('label' => __('Marks Obtained', 'school-management-system-india'), 'type' => 'number'),
                'smsi_exam_total_marks' => array('label' => __('Total Marks', 'school-management-system-india'), 'type' => 'number'),
                'smsi_exam_grade' => array('label' => __('Grade', 'school-management-system-india'), 'type' => 'text'),
                'smsi_exam_date' => array('label' => __('Exam Date', 'school-management-system-india'), 'type' => 'date'),
            );
        }

        foreach ($fields as $key => $field) {
            $value = get_post_meta($post->ID, $key, true);
            echo '<p>';
            echo '<label for="' . esc_attr($key) . '"><strong>' . esc_html($field['label']) . '</strong></label><br />';
            echo '<input type="' . esc_attr($field['type']) . '" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '" class="regular-text" />';
            echo '</p>';
        }
    }

    public function save_meta_boxes($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!isset($_POST['smsi_meta_box_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['smsi_meta_box_nonce'])), 'smsi_meta_box_nonce')) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $post_type = get_post_type($post_id);
        $fields = array();

        if ('smsi_student' === $post_type) {
            $fields = array(
                'smsi_student_class',
                'smsi_student_admission_no',
                'smsi_student_guardian_name',
                'smsi_student_contact_number',
                'smsi_student_dob',
            );
        } elseif ('smsi_teacher' === $post_type) {
            $fields = array(
                'smsi_teacher_department',
                'smsi_teacher_designation',
                'smsi_teacher_phone',
                'smsi_teacher_joining_date',
            );
        } elseif ('smsi_notice' === $post_type) {
            $fields = array(
                'smsi_notice_target',
                'smsi_notice_expiry_date',
            );
        } elseif ('smsi_exam' === $post_type) {
            $fields = array(
                'smsi_exam_student_id',
                'smsi_exam_subject',
                'smsi_exam_marks_obtained',
                'smsi_exam_total_marks',
                'smsi_exam_grade',
                'smsi_exam_date',
            );
        }

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $value = sanitize_text_field(wp_unslash($_POST[$field]));
                update_post_meta($post_id, $field, $value);
            }
        }
    }
}
