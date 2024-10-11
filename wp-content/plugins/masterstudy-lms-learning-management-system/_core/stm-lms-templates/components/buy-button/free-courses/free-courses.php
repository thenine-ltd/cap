<?php
/**
 * @var int $post_id
 * @var int $user_id
 * @var boolean $is_course_coming_soon
 * @var array $button_classes
 */
?>
<div class="<?php echo esc_attr( implode( ' ', $button_classes ) ); ?>">
	<?php
	if ( empty( $user_id ) ) {
		?>
		<a class="masterstudy-buy-button__link masterstudy-buy-button__link_centered" href="#" data-authorization-modal="login">
			<span class="masterstudy-buy-button__title"><?php echo esc_html__( 'Enroll course', 'masterstudy-lms-learning-management-system' ); ?></span>
		</a>
		<?php
	} else {
		$course         = STM_LMS_Helpers::simplify_db_array( stm_lms_get_user_course( $user_id, $post_id, array( 'current_lesson_id', 'progress_percent' ) ) );
		$current_lesson = $course['current_lesson_id'] ?? '0';
		$progress       = intval( $course['progress_percent'] ?? 0 );
		$lesson_url     = STM_LMS_Lesson::get_lesson_url( $post_id, $current_lesson );
		$btn_label      = esc_html__( 'Start course', 'masterstudy-lms-learning-management-system' );

		if ( $progress > 0 ) {
			$btn_label = esc_html__( 'Continue', 'masterstudy-lms-learning-management-system' );
		}

		if ( $is_course_coming_soon ) {
			?>
			<a href="#" class="masterstudy-buy-button__link masterstudy-buy-button__link_centered masterstudy-buy-button__link_disabled">
				<span class="masterstudy-buy-button__title"><?php echo esc_html__( 'Coming soon', 'masterstudy-lms-learning-management-system' ); ?></span>
			</a>
			<?php
		} else {
			?>
			<a class="masterstudy-buy-button__link masterstudy-buy-button__link_centered" href="<?php echo esc_url( $lesson_url ); ?>">
				<span class="masterstudy-buy-button__title"><?php echo esc_html( sanitize_text_field( $btn_label ) ); ?></span>
			</a>
			<?php
		}
	}
	?>
</div>
