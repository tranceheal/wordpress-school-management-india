<?php
if (!defined('ABSPATH')) {
    exit;
}

class SMSI_Admin {
    public function register_menu_pages() {
        add_menu_page(
            __('School Management', 'school-management-system-india'),
            __('School Mgmt', 'school-management-system-india'),
            'manage_options',
            'smsi-dashboard',
            array($this, 'render_dashboard'),
            'dashicons-building',
            26
        );

        add_submenu_page(
            'smsi-dashboard',
            __('Dashboard', 'school-management-system-india'),
            __('Dashboard', 'school-management-system-india'),
            'manage_options',
            'smsi-dashboard',
            array($this, 'render_dashboard')
        );

        add_submenu_page(
            'smsi-dashboard',
            __('Students', 'school-management-system-india'),
            __('Students', 'school-management-system-india'),
            'manage_options',
            'edit.php?post_type=smsi_student'
        );

        add_submenu_page(
            'smsi-dashboard',
            __('Teachers', 'school-management-system-india'),
            __('Teachers', 'school-management-system-india'),
            'manage_options',
            'edit.php?post_type=smsi_teacher'
        );

        add_submenu_page(
            'smsi-dashboard',
            __('Classrooms', 'school-management-system-india'),
            __('Classrooms', 'school-management-system-india'),
            'manage_options',
            'edit.php?post_type=smsi_classroom'
        );

        add_submenu_page(
            'smsi-dashboard',
            __('Fees', 'school-management-system-india'),
            __('Fees', 'school-management-system-india'),
            'manage_options',
            'edit.php?post_type=smsi_fee'
        );

        add_submenu_page(
            'smsi-dashboard',
            __('Exams', 'school-management-system-india'),
            __('Exams', 'school-management-system-india'),
            'manage_options',
            'edit.php?post_type=smsi_exam'
        );

        add_submenu_page(
            'smsi-dashboard',
            __('Notices', 'school-management-system-india'),
            __('Notices', 'school-management-system-india'),
            'manage_options',
            'edit.php?post_type=smsi_notice'
        );

        add_submenu_page(
            'smsi-dashboard',
            __('Settings', 'school-management-system-india'),
            __('Settings', 'school-management-system-india'),
            'manage_options',
            'smsi-settings',
            array($this, 'render_settings_page')
        );
    }

    public function enqueue_assets($hook) {
        if (false !== strpos($hook, 'smsi') || false !== strpos($hook, 'school')) {
            wp_enqueue_style(
                'smsi-admin-css',
                SMSI_PLUGIN_URL . 'assets/css/admin.css',
                array(),
                SMSI_VERSION
            );
        }
    }

    public function register_settings() {
        register_setting('smsi_settings_group', 'smsi_school_settings', array(
            'type' => 'array',
            'default' => array(
                'school_name' => '',
                'school_address' => '',
                'board_name' => 'CBSE',
                'academic_session' => '2025-2026',
                'contact_email' => '',
                'class_prefix' => 'Class',
                'fee_due_day' => '5',
                'fee_cycle' => 'Monthly',
                'session_start' => '',
                'session_end' => '',
            ),
            'sanitize_callback' => array($this, 'sanitize_settings'),
        ));
    }

    public function sanitize_settings($input) {
        $output = array();
        $output['school_name'] = !empty($input['school_name']) ? sanitize_text_field($input['school_name']) : '';
        $output['school_address'] = !empty($input['school_address']) ? sanitize_textarea_field($input['school_address']) : '';
        $output['board_name'] = !empty($input['board_name']) ? sanitize_text_field($input['board_name']) : 'CBSE';
        $output['academic_session'] = !empty($input['academic_session']) ? sanitize_text_field($input['academic_session']) : '2025-2026';
        $output['contact_email'] = !empty($input['contact_email']) ? sanitize_email($input['contact_email']) : '';
        $output['class_prefix'] = !empty($input['class_prefix']) ? sanitize_text_field($input['class_prefix']) : 'Class';
        $output['fee_due_day'] = !empty($input['fee_due_day']) ? absint($input['fee_due_day']) : 5;
        $output['fee_cycle'] = !empty($input['fee_cycle']) ? sanitize_text_field($input['fee_cycle']) : 'Monthly';
        $output['session_start'] = !empty($input['session_start']) ? sanitize_text_field($input['session_start']) : '';
        $output['session_end'] = !empty($input['session_end']) ? sanitize_text_field($input['session_end']) : '';

        return $output;
    }

    public function render_dashboard() {
        $settings = get_option('smsi_school_settings', array());
        $school_name = !empty($settings['school_name']) ? $settings['school_name'] : __('Your School', 'school-management-system-india');

        $student_count = wp_count_posts('smsi_student')->publish;
        $teacher_count = wp_count_posts('smsi_teacher')->publish;
        $classroom_count = wp_count_posts('smsi_classroom')->publish;
        $notice_count = wp_count_posts('smsi_notice')->publish;
        $attendance_count = wp_count_posts('smsi_attendance')->publish;
        $fee_count = wp_count_posts('smsi_fee')->publish;
        $exam_count = wp_count_posts('smsi_exam')->publish;

        ?>
        <div class="wrap smsi-dashboard">
            <h1><?php echo esc_html($school_name); ?> - <?php esc_html_e('School Dashboard', 'school-management-system-india'); ?></h1>

            <div class="smsi-cards">
                <div class="smsi-card">
                    <h3><?php esc_html_e('Students', 'school-management-system-india'); ?></h3>
                    <p><?php echo esc_html($student_count); ?></p>
                </div>
                <div class="smsi-card">
                    <h3><?php esc_html_e('Teachers', 'school-management-system-india'); ?></h3>
                    <p><?php echo esc_html($teacher_count); ?></p>
                </div>
                <div class="smsi-card">
                    <h3><?php esc_html_e('Classrooms', 'school-management-system-india'); ?></h3>
                    <p><?php echo esc_html($classroom_count); ?></p>
                </div>
                <div class="smsi-card">
                    <h3><?php esc_html_e('Notices', 'school-management-system-india'); ?></h3>
                    <p><?php echo esc_html($notice_count); ?></p>
                </div>
                <div class="smsi-card">
                    <h3><?php esc_html_e('Attendance Records', 'school-management-system-india'); ?></h3>
                    <p><?php echo esc_html($attendance_count); ?></p>
                </div>
                <div class="smsi-card">
                    <h3><?php esc_html_e('Fee Records', 'school-management-system-india'); ?></h3>
                    <p><?php echo esc_html($fee_count); ?></p>
                </div>
                <div class="smsi-card">
                    <h3><?php esc_html_e('Exam Records', 'school-management-system-india'); ?></h3>
                    <p><?php echo esc_html($exam_count); ?></p>
                </div>
            </div>

            <div class="smsi-panel">
                <h2><?php esc_html_e('Priority modules', 'school-management-system-india'); ?></h2>
                <ul>
                    <li><?php esc_html_e('Parent and student login portal', 'school-management-system-india'); ?></li>
                    <li><?php esc_html_e('Fee reminders and auto-calculation', 'school-management-system-india'); ?></li>
                    <li><?php esc_html_e('Board/session/class-specific reporting', 'school-management-system-india'); ?></li>
                    <li><?php esc_html_e('Export and CSV reporting', 'school-management-system-india'); ?></li>
                </ul>
            </div>
        </div>
        <?php
    }

    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        $settings = get_option('smsi_school_settings', array());
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('School Management Settings', 'school-management-system-india'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('smsi_settings_group');
                do_settings_sections('smsi_settings_group');
                ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('School Name', 'school-management-system-india'); ?></th>
                        <td>
                            <input type="text" name="smsi_school_settings[school_name]" value="<?php echo esc_attr($settings['school_name'] ?? ''); ?>" class="regular-text" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('School Address', 'school-management-system-india'); ?></th>
                        <td>
                            <textarea name="smsi_school_settings[school_address]" rows="4" cols="50"><?php echo esc_textarea($settings['school_address'] ?? ''); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Board / Affiliation', 'school-management-system-india'); ?></th>
                        <td>
                            <select name="smsi_school_settings[board_name]">
                                <?php
                                $boards = array('CBSE', 'ICSE', 'State Board', 'IB', 'Cambridge', 'Other');
                                foreach ($boards as $board) {
                                    $selected = (($settings['board_name'] ?? 'CBSE') === $board) ? 'selected="selected"' : '';
                                    echo '<option value="' . esc_attr($board) . '" ' . $selected . '>' . esc_html($board) . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Academic Session', 'school-management-system-india'); ?></th>
                        <td>
                            <input type="text" name="smsi_school_settings[academic_session]" value="<?php echo esc_attr($settings['academic_session'] ?? '2025-2026'); ?>" class="regular-text" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Session Start', 'school-management-system-india'); ?></th>
                        <td>
                            <input type="text" name="smsi_school_settings[session_start]" value="<?php echo esc_attr($settings['session_start'] ?? ''); ?>" class="regular-text" placeholder="April 2025" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Session End', 'school-management-system-india'); ?></th>
                        <td>
                            <input type="text" name="smsi_school_settings[session_end]" value="<?php echo esc_attr($settings['session_end'] ?? ''); ?>" class="regular-text" placeholder="March 2026" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Class Prefix', 'school-management-system-india'); ?></th>
                        <td>
                            <input type="text" name="smsi_school_settings[class_prefix]" value="<?php echo esc_attr($settings['class_prefix'] ?? 'Class'); ?>" class="regular-text" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Fee Due Day', 'school-management-system-india'); ?></th>
                        <td>
                            <input type="number" min="1" max="31" name="smsi_school_settings[fee_due_day]" value="<?php echo esc_attr($settings['fee_due_day'] ?? '5'); ?>" class="small-text" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Fee Cycle', 'school-management-system-india'); ?></th>
                        <td>
                            <input type="text" name="smsi_school_settings[fee_cycle]" value="<?php echo esc_attr($settings['fee_cycle'] ?? 'Monthly'); ?>" class="regular-text" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Contact Email', 'school-management-system-india'); ?></th>
                        <td>
                            <input type="email" name="smsi_school_settings[contact_email]" value="<?php echo esc_attr($settings['contact_email'] ?? ''); ?>" class="regular-text" />
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}
