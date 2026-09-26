<?php
if (!defined('ABSPATH')) {
    exit;
}

class SMSI_Fees {
    public function register_post_type() {
        register_post_type(
            'smsi_fee',
            array(
                'labels' => array(
                    'name' => __('Fees', 'school-management-system-india'),
                    'singular_name' => __('Fee Record', 'school-management-system-india'),
                    'add_new_item' => __('Add Fee Record', 'school-management-system-india'),
                    'edit_item' => __('Edit Fee Record', 'school-management-system-india'),
                ),
                'public' => true,
                'has_archive' => true,
                'menu_icon' => 'dashicons-money-alt',
                'supports' => array('title', 'editor'),
                'show_in_rest' => true,
                'rewrite' => array('slug' => 'fees'),
            )
        );
    }

    public function register_menu_pages() {
        add_submenu_page('smsi-dashboard', __('Fees', 'school-management-system-india'), __('Fees', 'school-management-system-india'), 'manage_options', 'edit.php?post_type=smsi_fee');
    }

    public function calculate_student_fee($student_id, $base_amount, $discount = 0, $cycle = '') {
        $settings = get_option('smsi_school_settings', array());
        $base = floatval($base_amount);
        $discount_value = floatval($discount);
        $final_amount = max(0, $base - $discount_value);
        $due_day = !empty($settings['fee_due_day']) ? intval($settings['fee_due_day']) : 5;
        $cycle_name = !empty($cycle) ? $cycle : (!empty($settings['fee_cycle']) ? $settings['fee_cycle'] : 'Monthly');
        $due_date = date('Y-m-d', strtotime('+' . max(1, $due_day) . ' days'));

        return array(
            'amount' => round($final_amount, 2),
            'due_day' => $due_day,
            'due_date' => $due_date,
            'cycle' => $cycle_name,
            'student_id' => absint($student_id),
        );
    }

    public function get_student_fee_summary($student_id) {
        $fees = get_posts(array(
            'post_type' => 'smsi_fee',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_key' => 'smsi_fee_student_id',
            'meta_value' => (string) absint($student_id),
        ));

        $total = 0;
        $paid = 0;
        $pending = 0;

        foreach ($fees as $fee) {
            $amount = floatval(get_post_meta($fee->ID, 'smsi_fee_amount', true));
            $status = strtolower((string) get_post_meta($fee->ID, 'smsi_fee_status', true));
            $total += $amount;

            if (in_array($status, array('paid', 'cleared', 'complete', 'paid in full'), true)) {
                $paid += $amount;
            } else {
                $pending += $amount;
            }
        }

        return array('total' => $total, 'paid' => $paid, 'pending' => $pending);
    }

    public function render_fee_summary($atts = array()) {
        $atts = shortcode_atts(array('limit' => 10), $atts, 'smsi_fee_summary');
        $fees = get_posts(array('post_type' => 'smsi_fee', 'posts_per_page' => absint($atts['limit']), 'post_status' => 'publish'));

        if (empty($fees)) {
            return '<p>' . __('No fee records found.', 'school-management-system-india') . '</p>';
        }

        $output = '<div class="smsi-fee-summary"><table class="widefat"><thead><tr><th>' . __('Student', 'school-management-system-india') . '</th><th>' . __('Amount', 'school-management-system-india') . '</th><th>' . __('Status', 'school-management-system-india') . '</th></tr></thead><tbody>';
        foreach ($fees as $fee) {
            $amount = get_post_meta($fee->ID, 'smsi_fee_amount', true);
            $status = get_post_meta($fee->ID, 'smsi_fee_status', true);
            $student_name = get_post_meta($fee->ID, 'smsi_fee_student_name', true);

            $output .= '<tr>';
            $output .= '<td>' . esc_html($student_name ?: get_the_title($fee)) . '</td>';
            $output .= '<td>' . esc_html($amount ?: '0') . '</td>';
            $output .= '<td>' . esc_html($status ?: __('Pending', 'school-management-system-india')) . '</td>';
            $output .= '</tr>';
        }
        $output .= '</tbody></table></div>';

        return $output;
    }

    public function render_reminder_board($atts = array()) {
        $atts = shortcode_atts(array('limit' => 10), $atts, 'smsi_fee_reminders');
        $fees = get_posts(array('post_type' => 'smsi_fee', 'post_status' => 'publish', 'posts_per_page' => absint($atts['limit'])));

        if (empty($fees)) {
            return '<p>' . __('No fee reminders available.', 'school-management-system-india') . '</p>';
        }

        $output = '<div class="smsi-fee-reminders"><table class="widefat"><thead><tr><th>' . __('Student', 'school-management-system-india') . '</th><th>' . __('Due Date', 'school-management-system-india') . '</th><th>' . __('Amount', 'school-management-system-india') . '</th><th>' . __('Status', 'school-management-system-india') . '</th></tr></thead><tbody>';
        foreach ($fees as $fee) {
            $student_name = get_post_meta($fee->ID, 'smsi_fee_student_name', true);
            $due_date = get_post_meta($fee->ID, 'smsi_fee_due_date', true);
            $amount = get_post_meta($fee->ID, 'smsi_fee_amount', true);
            $status = get_post_meta($fee->ID, 'smsi_fee_status', true);

            $output .= '<tr>';
            $output .= '<td>' . esc_html($student_name ?: get_the_title($fee)) . '</td>';
            $output .= '<td>' . esc_html($due_date ?: __('Not set', 'school-management-system-india')) . '</td>';
            $output .= '<td>' . esc_html($amount ?: '0') . '</td>';
            $output .= '<td>' . esc_html($status ?: __('Pending', 'school-management-system-india')) . '</td>';
            $output .= '</tr>';
        }
        $output .= '</tbody></table></div>';

        return $output;
    }
}
