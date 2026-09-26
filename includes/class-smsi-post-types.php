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
        add_submenu_page(
            'smsi-dashboard',
            __('Fees', 'school-management-system-india'),
            __('Fees', 'school-management-system-india'),
            'manage_options',
            'edit.php?post_type=smsi_fee'
        );
    }

    public function render_fee_summary($atts = array()) {
        $atts = shortcode_atts(array(
            'limit' => 10,
        ), $atts, 'smsi_fee_summary');

        $fees = get_posts(array(
            'post_type' => 'smsi_fee',
            'posts_per_page' => absint($atts['limit']),
            'post_status' => 'publish',
        ));

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
}
