<?php
if (!defined('ABSPATH')) {
    exit;
}

class SMSI_Post_Types {
    public function register_post_types() {
        $this->register_student_post_type();
        $this->register_teacher_post_type();
        $this->register_classroom_post_type();
        $this->register_exam_post_type();
        $this->register_notice_post_type();
        $this->register_taxonomies();
    }

    private function register_student_post_type() {
        register_post_type(
            'smsi_student',
            array(
                'labels' => array(
                    'name' => __('Students', 'school-management-system-india'),
                    'singular_name' => __('Student', 'school-management-system-india'),
                    'add_new_item' => __('Add New Student', 'school-management-system-india'),
                    'edit_item' => __('Edit Student', 'school-management-system-india'),
                ),
                'public' => true,
                'has_archive' => true,
                'menu_icon' => 'dashicons-groups',
                'supports' => array('title', 'editor', 'thumbnail'),
                'show_in_rest' => true,
                'rewrite' => array('slug' => 'students'),
            )
        );
    }

    private function register_teacher_post_type() {
        register_post_type(
            'smsi_teacher',
            array(
                'labels' => array(
                    'name' => __('Teachers', 'school-management-system-india'),
                    'singular_name' => __('Teacher', 'school-management-system-india'),
                    'add_new_item' => __('Add New Teacher', 'school-management-system-india'),
                    'edit_item' => __('Edit Teacher', 'school-management-system-india'),
                ),
                'public' => true,
                'has_archive' => true,
                'menu_icon' => 'dashicons-businessperson',
                'supports' => array('title', 'editor', 'thumbnail'),
                'show_in_rest' => true,
                'rewrite' => array('slug' => 'teachers'),
            )
        );
    }

    private function register_classroom_post_type() {
        register_post_type(
            'smsi_classroom',
            array(
                'labels' => array(
                    'name' => __('Classrooms', 'school-management-system-india'),
                    'singular_name' => __('Classroom', 'school-management-system-india'),
                    'add_new_item' => __('Add New Classroom', 'school-management-system-india'),
                    'edit_item' => __('Edit Classroom', 'school-management-system-india'),
                ),
                'public' => true,
                'has_archive' => true,
                'menu_icon' => 'dashicons-building',
                'supports' => array('title', 'editor'),
                'show_in_rest' => true,
                'rewrite' => array('slug' => 'classrooms'),
            )
        );
    }

    private function register_exam_post_type() {
        register_post_type(
            'smsi_exam',
            array(
                'labels' => array(
                    'name' => __('Exams', 'school-management-system-india'),
                    'singular_name' => __('Exam', 'school-management-system-india'),
                    'add_new_item' => __('Add New Exam', 'school-management-system-india'),
                    'edit_item' => __('Edit Exam', 'school-management-system-india'),
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

    private function register_notice_post_type() {
        register_post_type(
            'smsi_notice',
            array(
                'labels' => array(
                    'name' => __('Notices', 'school-management-system-india'),
                    'singular_name' => __('Notice', 'school-management-system-india'),
                    'add_new_item' => __('Add New Notice', 'school-management-system-india'),
                    'edit_item' => __('Edit Notice', 'school-management-system-india'),
                ),
                'public' => true,
                'has_archive' => true,
                'menu_icon' => 'dashicons-megaphone',
                'supports' => array('title', 'editor'),
                'show_in_rest' => true,
                'rewrite' => array('slug' => 'notices'),
            )
        );
    }

    private function register_taxonomies() {
        register_taxonomy(
            'smsi_grade',
            array('smsi_student', 'smsi_exam'),
            array(
                'labels' => array(
                    'name' => __('Grades', 'school-management-system-india'),
                    'singular_name' => __('Grade', 'school-management-system-india'),
                ),
                'hierarchical' => true,
                'show_admin_column' => true,
                'rewrite' => array('slug' => 'grades'),
            )
        );
    }
}
