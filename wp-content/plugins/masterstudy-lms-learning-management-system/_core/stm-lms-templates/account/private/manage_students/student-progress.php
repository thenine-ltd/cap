<?php
stm_lms_register_style( 'manage_students/student-progress' );
stm_lms_register_script( 'manage_students/student-progress' );

$data     = STM_LMS_User_Manager_Course_User::_student_progress( $course_id, $student_id );
$sections = $data['sections'] ?? array();

wp_enqueue_style( 'masterstudy-student-progress-list' );
wp_enqueue_script( 'masterstudy-student-progress-list' );
?>

<div class="masterstudy-student-progress">
	<div class="masterstudy-student-progress__top">
		<div class="masterstudy-student-progress__top-left">
			<?php
			STM_LMS_Templates::show_lms_template(
				'components/back-link',
				array(
					'id'  => 'masterstudy-course-player-back',
					'url' => STM_LMS_Instructor::instructor_manage_students_url() . "/?course_id=$course_id",
				)
			);
			?>
			<div class="masterstudy-student-progress__top-info">
				<div class="masterstudy-student-progress__course-user">
					<?php
					printf(
						// translators: user progress.
						esc_html__( '%s progress for', 'masterstudy-lms-learning-management-system' ),
						esc_html( $data['user']['login'] ?? '' )
					);
					?>
					:
				</div>
				<div class="masterstudy-student-progress__course-title">
					<?php echo esc_html( $data['course_title'] ?? '' ); ?>
				</div>
			</div>
		</div>
		<div class="masterstudy-student-progress__top-right">
			<?php
			STM_LMS_Templates::show_lms_template(
				'components/progress',
				array(
					'progress' => $data['progress_percent'] ?? 0,
					'title'    => esc_html__( 'Course progress', 'masterstudy-lms-learning-management-system' ),
					'is_reset' => true,
				)
			);
			?>
		</div>
	</div>
	<div class="masterstudy-student-progress-list">
		<?php foreach ( $sections as $index => $section ) : ?>
		<div class="masterstudy-student-progress-list__wrapper<?php echo esc_attr( $index ? ' masterstudy-student-progress-lidst__wrapper_opened' : '' ); ?>">
			<div class="masterstudy-student-progress-list__section">
				<h4 class="masterstudy-student-progress-list__section-title">
					<?php echo esc_html( $section['title'] ?? '' ); ?>
				</h4>
				<span class="masterstudy-student-progress-list__toggler"></span>
			</div>
			<ul class="masterstudy-student-progress-list__materials">
			<?php foreach ( $data['materials'] as $material ) { ?>
				<?php
				if ( $section['id'] === $material['section_id'] ) {
					$icon = 'lesson' === $material['type'] ? 'text' : $material['type'];
					$icon = 'stm-google-meets' === $material['post_type'] ? 'google-meet' : $icon;
					$icon = 'assignment' === $icon ? 'assignments' : $icon;
					?>
					<li class="masterstudy-student-progress-list__item">
						<div class="masterstudy-student-progress-list__item-wrapper">
							<div class="masterstudy-student-progress-list__order">
								<?php echo esc_html( $material['order'] ); ?>
							</div>
							<img src="<?php echo esc_url( STM_LMS_URL . "/assets/icons/lessons/{$icon}.svg" ); ?>" class="masterstudy-student-progress-list__image">
							<div class="masterstudy-student-progress-list__container">
								<div class="masterstudy-student-progress-list__container-wrapper">
									<div class="masterstudy-student-progress-list__title">
										<?php echo esc_html( $material['title'] ); ?>
									</div>
									<div class="masterstudy-student-progress-list__meta-wrapper">
										<?php if ( in_array( $material['type'], array( 'assignment', 'quiz' ), true ) ) : ?>
										<span class="masterstudy-student-progress-list__content-toggler"></span>
										<?php endif; ?>
										<span class="masterstudy-student-progress-list__meta">
											<input type="checkbox" class="masterstudy-student-progress-list__meta-checkbox" value="1" <?php echo esc_attr( $material['completed'] ? 'checked' : '' ); ?> data-item-id="<?php echo esc_attr( $material['post_id'] ?? 0 ); ?>" data-type="<?php echo esc_attr( $material['type'] ?? '' ); ?>">
											<span class="masterstudy-student-progress-list__meta-checkbox__tooltip">
												<?php esc_html_e( 'Complete', 'masterstudy-lms-learning-management-system' ); ?>
											</span>
										</span>
									</div>
								</div>
								<?php if ( in_array( $material['type'], array( 'assignment', 'quiz' ), true ) ) : ?>
									<div class="masterstudy-student-progress-list__content">
										<div class="masterstudy-student-progress-list__content-wrapper">
											<?php
											if ( 'quiz' === $material['type'] ) {
												STM_LMS_Templates::show_lms_template( 'account/private/manage_students/quiz-progress', compact( 'material', 'course_id', 'student_id' ) );
											}
											if ( 'assignment' === $material['type'] ) {
												STM_LMS_Templates::show_lms_template( 'account/private/manage_students/assignment-progress', compact( 'material', 'course_id', 'student_id' ) );
											}
											?>
										</div>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</li>
					<?php
				}
			}
			?>
			</ul>
		</div>
		<?php endforeach; ?>
	</div>
</div>
<?php
STM_LMS_Templates::show_lms_template(
	'components/alert',
	array(
		'id'                  => 'masterstudy-manage-students-reset-progress',
		'title'               => esc_html__( 'Reset progress', 'masterstudy-lms-learning-management-system' ),
		'text'                => esc_html__( "Are you sure you want to reset this student's progress ?", 'masterstudy-lms-learning-management-system' ),
		'submit_button_text'  => esc_html__( 'Reset', 'masterstudy-lms-learning-management-system' ),
		'cancel_button_text'  => esc_html__( 'Cancel', 'masterstudy-lms-learning-management-system' ),
		'submit_button_style' => 'danger',
		'cancel_button_style' => 'tertiary',
		'dark_mode'           => false,
	)
);
