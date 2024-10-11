<?php
function masterstudy_enqueue() {
	/*Course player scripts registration*/
	wp_register_script( 'masterstudy-course-player-assignments', STM_LMS_PRO_URL . 'assets/js/course-player/assignments.js', array( 'jquery', 'masterstudy-audio-player' ), STM_LMS_PRO_VERSION, true );

	/*Course player styles registration*/
	wp_register_style( 'masterstudy-course-player-lesson-zoom', STM_LMS_PRO_URL . 'assets/css/course-player/zoom-conference.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-course-player-lesson-stream', STM_LMS_PRO_URL . 'assets/css/course-player/stream.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-course-player-lesson-google', STM_LMS_PRO_URL . 'assets/css/course-player/google-meet.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-course-player-drip-content', STM_LMS_PRO_URL . '/assets/css/course-player/drip-content.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-course-player-assignments', STM_LMS_PRO_URL . 'assets/css/course-player/assignments.css', null, STM_LMS_PRO_VERSION );

	/*Course player fonts styles registration*/
	wp_register_style( 'masterstudy-course-player-lesson-zoom-fonts', STM_LMS_PRO_URL . 'assets/css/course-player/fonts/zoom-conference.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-course-player-lesson-stream-fonts', STM_LMS_PRO_URL . 'assets/css/course-player/fonts/stream.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-course-player-lesson-google-fonts', STM_LMS_PRO_URL . 'assets/css/course-player/fonts/google-meet.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-course-player-drip-content-fonts', STM_LMS_PRO_URL . '/assets/css/course-player/fonts/drip-content.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-course-player-assignments-fonts', STM_LMS_PRO_URL . 'assets/css/course-player/fonts/assignments.css', null, STM_LMS_PRO_VERSION );

	/* Certificate page scripts & style registration */
	wp_register_style( 'masterstudy-instructor-certificates', STM_LMS_PRO_URL . 'assets/css/certificate-builder/instructor-certificates.css', null, STM_LMS_PRO_VERSION );
	wp_register_script( 'masterstudy-instructor-certificates', STM_LMS_PRO_URL . 'assets/js/certificate-builder/instructor-certificates.js', array( 'jquery' ), STM_LMS_PRO_VERSION, true );

	/*Single course page styles & scripts registration*/
	wp_register_script( 'masterstudy-single-course-main', STM_LMS_PRO_URL . 'assets/js/course/main.js', array( 'jquery', 'plyr' ), STM_LMS_PRO_VERSION, true );
	wp_register_style( 'masterstudy-single-course-classic', STM_LMS_PRO_URL . 'assets/css/course/classic.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-single-course-modern', STM_LMS_PRO_URL . 'assets/css/course/modern.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-single-course-timeless', STM_LMS_PRO_URL . 'assets/css/course/timeless.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-single-course-dynamic-sidebar', STM_LMS_PRO_URL . 'assets/css/course/dynamic-sidebar.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-single-course-sleek-sidebar', STM_LMS_PRO_URL . 'assets/css/course/sleek-sidebar.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-single-course-minimalistic', STM_LMS_PRO_URL . 'assets/css/course/minimalistic.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-single-course-dynamic', STM_LMS_PRO_URL . 'assets/css/course/dynamic.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-single-course-full-width', STM_LMS_PRO_URL . 'assets/css/course/full-width.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-single-course-modern-curriculum', STM_LMS_PRO_URL . 'assets/css/course/modern-curriculum.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-single-course-video-preview', STM_LMS_PRO_URL . 'assets/css/course/video-preview.css', null, STM_LMS_PRO_VERSION );

	/*Components scripts registration*/
	wp_register_script( 'masterstudy-form-builder-fields', STM_LMS_PRO_URL . 'assets/js/components/form-builder-fields.js', array( 'jquery' ), STM_LMS_PRO_VERSION, true );
	wp_localize_script(
		'masterstudy-form-builder-fields',
		'masterstudy_form_builder_data',
		array(
			'ajax_url'          => admin_url( 'admin-ajax.php' ),
			'file_upload_nonce' => wp_create_nonce( 'stm_lms_upload_form_file' ),
			'file_delete_nonce' => wp_create_nonce( 'stm_lms_delete_form_file' ),
			'icon_url'          => STM_LMS_PRO_URL . '/assets/icons/files/',
			'only_one_file'     => __( 'Only one file allowed', 'masterstudy-lms-learning-management-system' ),
		)
	);
	wp_register_script( 'masterstudy-buy-button-points', STM_LMS_PRO_URL . 'assets/js/components/buy-button/points.js', array( 'jquery' ), STM_LMS_PRO_VERSION, true );
	wp_register_script( 'masterstudy-buy-button-prerequisites', STM_LMS_PRO_URL . 'assets/js/components/buy-button/prerequisites.js', array( 'jquery' ), STM_LMS_PRO_VERSION, true );
	wp_register_script( 'masterstudy-group-course-trigger', STM_LMS_PRO_URL . 'assets/js/components/modals/group-courses-trigger.js', array( 'jquery' ), STM_LMS_PRO_VERSION, true );
	wp_register_script( 'masterstudy-group-course-add-group', STM_LMS_PRO_URL . 'assets/js/components/modals/group-courses-add-group.js', array( 'jquery' ), STM_LMS_PRO_VERSION, true );
	wp_register_script( 'masterstudy-group-course-add-to-cart', STM_LMS_PRO_URL . 'assets/js/components/modals/group-courses-add-to-cart.js', array( 'jquery' ), STM_LMS_PRO_VERSION, true );
	wp_register_script( 'masterstudy-bundle-button', STM_LMS_PRO_URL . 'assets/js/components/bundle-button.js', array( 'jquery' ), STM_LMS_PRO_VERSION, true );

	/*Components styles registration*/
	wp_register_style( 'masterstudy-buy-button-points', STM_LMS_PRO_URL . 'assets/css/components/buy-button/points.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-buy-button-group-courses', STM_LMS_PRO_URL . '/assets/css/components/buy-button/group-courses.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-buy-button-affiliate', STM_LMS_PRO_URL . '/assets/css/components/buy-button/affiliate.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-buy-button-prerequisites', STM_LMS_PRO_URL . 'assets/css/components/buy-button/prerequisite-button.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-prerequisites-info', STM_LMS_PRO_URL . 'assets/css/components/buy-button/prerequisite-info.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-group-course', STM_LMS_PRO_URL . 'assets/css/components/group-courses.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-form-builder-fields', STM_LMS_PRO_URL . 'assets/css/components/form-builder-fields.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-bundle-button', STM_LMS_PRO_URL . 'assets/css/components/bundle-button.css', null, STM_LMS_PRO_VERSION );

	/*Components fonts styles registration*/
	wp_register_style( 'masterstudy-buy-button-points-fonts', STM_LMS_PRO_URL . 'assets/css/components/fonts/buy-button/points.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-buy-button-group-courses-fonts', STM_LMS_PRO_URL . '/assets/css/components/fonts/buy-button/group-courses.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-buy-button-affiliate-fonts', STM_LMS_PRO_URL . '/assets/css/components/fonts/buy-button/affiliate.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-buy-button-prerequisites-fonts', STM_LMS_PRO_URL . 'assets/css/components/fonts/buy-button/prerequisite-button.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-prerequisites-info-fonts', STM_LMS_PRO_URL . 'assets/css/components/fonts/buy-button/prerequisite-info.css', null, STM_LMS_PRO_VERSION );
}
add_action( 'wp_enqueue_scripts', 'masterstudy_enqueue' );

function masterstudy_admin_enqueue() {
	wp_enqueue_style( 'fonts', STM_LMS_PRO_URL . 'assets/css/variables/fonts.css', null, STM_LMS_PRO_VERSION );
	wp_enqueue_style( 'masterstudy-admin-certificate', STM_LMS_PRO_URL . 'assets/css/certificate-builder/admin.css', null, STM_LMS_PRO_VERSION );
}
add_action( 'admin_enqueue_scripts', 'masterstudy_admin_enqueue' );
