<?php
if (!defined('ABSPATH')) {
    exit;
}

class SMSI_Meta_Boxes {
    public function register_meta_boxes() {
        add_meta_box(
            'smsi_student_fields',
            __('Student Information', 'school-management-system-india'),
            array($this, 'render_student_fields'),
            'smsi_student',
            'normal',
            'default'
        );

        add_meta_box(
            'smsi_teacher_fields',
            __('Teacher Information', 'school-management-system-india'),
            array($this, 'render_teacher_fields'),
            'smsi_teacher',
            'normal',
            'default'
        );

        add_meta_box(
            'smsi_notice_fields',
            __('Notice Details', 'school-management-system-india'),
            array($this, 'render_notice_fields'),
            'smsi_notice',
            'normal',
            'default'
        );

        add_meta_box(
            'smsi_fee_fields',
            __('Fee Details', 'school-management-system-india'),
            array($this, 'render_fee_fields'),
            'smsi_fee',
            'normal',
            'default'
        );

        add_meta_box(
            'smsi_attendance_fields',
            __('Attendance Details', 'school-management-system-india'),
            array($this, 'render_attendance_fields'),
            'smsi_attendance',
            'normal',
            'default'
        );
    }

    public function render_student_fields($post) {
        wp_nonce_field('smsi_meta_boxes_nonce', 'smsi_meta_boxes_nonce');

        $admission_number = get_post_meta($post->ID, 'smsi_student_admission_number', true);
        $roll_number = get_post_meta($post->ID, 'smsi_student_roll_number', true);
        $class_name = get_post_meta($post->ID, 'smsi_student_class', true);
        $section = get_post_meta($post->ID, 'smsi_student_section', true);
        $parent_name = get_post_meta($post->ID, 'smsi_student_parent_name', true);
        $phone = get_post_meta($post->ID, 'smsi_student_phone', true);
        $email = get_post_meta($post->ID, 'smsi_student_email', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="smsi_student_admission_number"><?php esc_html_e('Admission Number', 'school-management-system-india'); ?></label></th>
                <td><input type="text" id="smsi_student_admission_number" name="smsi_student_admission_number" value="<?php echo esc_attr($admission_number); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="smsi_student_roll_number"><?php esc_html_e('Roll Number', 'school-management-system-india'); ?></label></th>
                <td><input type="text" id="smsi_student_roll_number" name="smsi_student_roll_number" value="<?php echo esc_attr($roll_number); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="smsi_student_class"><?php esc_html_e('Class', 'school-management-system-india'); ?></label></th>
                <td><input type="text" id="smsi_student_class" name="smsi_student_class" value="<?php echo esc_attr($class_name); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="smsi_student_section"><?php esc_html_e('Section', 'school-management-system-india'); ?></label></th>
                <td><input type="text" id="smsi_student_section" name="smsi_student_section" value="<?php echo esc_attr($section); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="smsi_student_parent_name"><?php esc_html_e('Parent Name', 'school-management-system-india'); ?></label></th>
                <td><input type="text" id="smsi_student_parent_name" name="smsi_student_parent_name" value="<?php echo esc_attr($parent_name); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="smsi_student_phone"><?php esc_html_e('Phone', 'school-management-system-india'); ?></label></th>
                <td><input type="text" id="smsi_student_phone" name="smsi_student_phone" value="<?php echo esc_attr($phone); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="smsi_student_email"><?php esc_html_e('Email', 'school-management-system-india'); ?></label></th>
                <td><input type="email" id="smsi_student_email" name="smsi_student_email" value="<?php echo esc_attr($email); ?>" class="regular-text" /></td>
            </tr>
        </table>
        <?php
    }

    public function render_teacher_fields($post) {
        wp_nonce_field('smsi_meta_boxes_nonce', 'smsi_meta_boxes_nonce');

        $department = get_post_meta($post->ID, 'smsi_teacher_department', true);
        $subject = get_post_meta($post->ID, 'smsi_teacher_subject', true);
        $employee_id = get_post_meta($post->ID, 'smsi_teacher_employee_id', true);
        $phone = get_post_meta($post->ID, 'smsi_teacher_phone', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="smsi_teacher_department"><?php esc_html_e('Department', 'school-management-system-india'); ?></label></th>
                <td><input type="text" id="smsi_teacher_department" name="smsi_teacher_department" value="<?php echo esc_attr($department); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="smsi_teacher_subject"><?php esc_html_e('Subject', 'school-management-system-india'); ?></label></th>
                <td><input type="text" id="smsi_teacher_subject" name="smsi_teacher_subject" value="<?php echo esc_attr($subject); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="smsi_teacher_employee_id"><?php esc_html_e('Employee ID', 'school-management-system-india'); ?></label></th>
                <td><input type="text" id="smsi_teacher_employee_id" name="smsi_teacher_employee_id" value="<?php echo esc_attr($employee_id); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="smsi_teacher_phone"><?php esc_html_e('Phone', 'school-management-system-india'); ?></label></th>
                <td><input type="text" id="smsi_teacher_phone" name="smsi_teacher_phone" value="<?php echo esc_attr($phone); ?>" class="regular-text" /></td>
            </tr>
        </table>
        <?php
    }

    public function render_notice_fields($post) {
        wp_nonce_field('smsi_meta_boxes_nonce', 'smsi_meta_boxes_nonce');

        $priority = get_post_meta($post->ID, 'smsi_notice_priority', true);
        $valid_until = get_post_meta($post->ID, 'smsi_notice_valid_until', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="smsi_notice_priority"><?php esc_html_e('Priority', 'school-management-system-india'); ?></label></th>
                <td>
                    <select id="smsi_notice_priority" name="smsi_notice_priority">
                        <option value="normal" <?php selected($priority, 'normal'); ?>><?php esc_html_e('Normal', 'school-management-system-india'); ?></option>
                        <option value="important" <?php selected($priority, 'important'); ?>><?php esc_html_e('Important', 'school-management-system-india'); ?></option>
                        <option value="urgent" <?php selected($priority, 'urgent'); ?>><?php esc_html_e('Urgent', 'school-management-system-india'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="smsi_notice_valid_until"><?php esc_html_e('Valid Until', 'school-management-system-india'); ?></label></th>
                <td><input type="date" id="smsi_notice_valid_until" name="smsi_notice_valid_until" value="<?php echo esc_attr($valid_until); ?>" /></td>
            </tr>
        </table>
        <?php
    }

    public function render_fee_fields($post) {
        wp_nonce_field('smsi_meta_boxes_nonce', 'smsi_meta_boxes_nonce');

        $student_name = get_post_meta($post->ID, 'smsi_fee_student_name', true);
        $amount_due = get_post_meta($post->ID, 'smsi_fee_amount_due', true);
        $amount_paid = get_post_meta($post->ID, 'smsi_fee_amount_paid', true);
        $fee_month = get_post_meta($post->ID, 'smsi_fee_month', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="smsi_fee_student_name"><?php esc_html_e('Student Name', 'school-management-system-india'); ?></label></th>
                <td><input type="text" id="smsi_fee_student_name" name="smsi_fee_student_name" value="<?php echo esc_attr($student_name); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="smsi_fee_amount_due"><?php esc_html_e('Amount Due', 'school-management-system-india'); ?></label></th>
                <td><input type="number" step="0.01" id="smsi_fee_amount_due" name="smsi_fee_amount_due" value="<?php echo esc_attr($amount_due); ?>" /></td>
            </tr>
            <tr>
                <th><label for="smsi_fee_amount_paid"><?php esc_html_e('Amount Paid', 'school-management-system-india'); ?></label></th>
                <td><input type="number" step="0.01" id="smsi_fee_amount_paid" name="smsi_fee_amount_paid" value="<?php echo esc_attr($amount_paid); ?>" /></td>
            </tr>
            <tr>
                <th><label for="smsi_fee_month"><?php esc_html_e('Fee Month', 'school-management-system-india'); ?></label></th>
                <td><input type="text" id="smsi_fee_month" name="smsi_fee_month" value="<?php echo esc_attr($fee_month); ?>" class="regular-text" /></td>
            </tr>
        </table>
        <?php
    }

    public function render_attendance_fields($post) {
        wp_nonce_field('smsi_meta_boxes_nonce', 'smsi_meta_boxes_nonce');

        $student = get_post_meta($post->ID, 'smsi_attendance_student', true);
        $date = get_post_meta($post->ID, 'smsi_attendance_date', true);
        $status = get_post_meta($post->ID, 'smsi_attendance_status', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="smsi_attendance_student"><?php esc_html_e('Student', 'school-management-system-india'); ?></label></th>
                <td><input type="text" id="smsi_attendance_student" name="smsi_attendance_student" value="<?php echo esc_attr($student); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="smsi_attendance_date"><?php esc_html_e('Date', 'school-management-system-india'); ?></label></th>
                <td><input type="date" id="smsi_attendance_date" name="smsi_attendance_date" value="<?php echo esc_attr($date); ?>" /></td>
            </tr>
            <tr>
                <th><label for="smsi_attendance_status"><?php esc_html_e('Status', 'school-management-system-india'); ?></label></th>
                <td>
                    <select id="smsi_attendance_status" name="smsi_attendance_status">
                        <option value="present" <?php selected($status, 'present'); ?>><?php esc_html_e('Present', 'school-management-system-india'); ?></option>
                        <option value="absent" <?php selected($status, 'absent'); ?>><?php esc_html_e('Absent', 'school-management-system-india'); ?></option>
                        <option value="late" <?php selected($status, 'late'); ?>><?php esc_html_e('Late', 'school-management-system-india'); ?></option>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }

    public function save_meta_boxes($post_id) {
        if (!isset($_POST['smsi_meta_boxes_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['smsi_meta_boxes_nonce'])), 'smsi_meta_boxes_nonce')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $fields = array(
            'smsi_student_admission_number',
            'smsi_student_roll_number',
            'smsi_student_class',
            'smsi_student_section',
            'smsi_student_parent_name',
            'smsi_student_phone',
            'smsi_student_email',
            'smsi_teacher_department',
            'smsi_teacher_subject',
            'smsi_teacher_employee_id',
            'smsi_teacher_phone',
            'smsi_notice_priority',
            'smsi_notice_valid_until',
            'smsi_fee_student_name',
            'smsi_fee_amount_due',
            'smsi_fee_amount_paid',
            'smsi_fee_month',
            'smsi_attendance_student',
            'smsi_attendance_date',
            'smsi_attendance_status',
        );

        foreach ($fields as $field) {
            if (!isset($_POST[$field])) {
                continue;
            }

            $value = sanitize_text_field(wp_unslash($_POST[$field]));
            update_post_meta($post_id, $field, $value);
        }
    }
}
