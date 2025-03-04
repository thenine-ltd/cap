<?php
/**
 * @var int $post_id
 * @var int $item_id
 * @var array $data
 */

$current_template   = $data['current_template'];
$assignment_content = $data['user_assignments'][ $current_template ]->post_content ?? '';

if ( empty( $data['theme_fonts'] ) ) {
	wp_enqueue_style( 'masterstudy-course-player-assignments-fonts' );
}
wp_enqueue_style( 'masterstudy-course-player-assignments' );
wp_enqueue_script( 'masterstudy-course-player-assignments' );
wp_localize_script(
	'masterstudy-course-player-assignments',
	'assignments_data',
	array(
		'submit_nonce' => wp_create_nonce( 'stm_lms_accept_draft_assignment' ),
		'course_id'    => $post_id,
		'editor_id'    => $data['editor_id'],
		'draft_id'     => $data['assignment_id'],
		'ajax_url'     => admin_url( 'admin-ajax.php' ),
	)
);

if ( 'passed' === $current_template || 'unpassed' === $current_template ) {
	STM_LMS_Assignments::student_view_update( $data['assignment_id'] );
}

STM_LMS_Templates::show_lms_template(
	'components/alert',
	array(
		'id'                  => 'assignment_submit_alert',
		'title'               => esc_html__( 'There are no text entry or uploads', 'masterstudy-lms-learning-management-system' ),
		'text'                => '',
		'cancel_button_text'  => esc_html__( 'Close', 'masterstudy-lms-learning-management-system' ),
		'cancel_button_style' => 'tertiary',
		'dark_mode'           => $data['dark_mode'],
	)
);
?>
<div class="masterstudy-course-player-assignments<?php echo esc_attr( 'draft' === $current_template ? ' masterstudy-course-player-assignments_draft' : '' ); ?>">
	<?php if ( 'draft' !== $current_template ) { ?>
		<div class="masterstudy-course-player-assignments__status masterstudy-course-player-assignments__status_<?php echo esc_attr( $current_template ); ?>">
			<?php if ( 'reviewing' === $current_template ) { ?>
				<img src="<?php echo esc_url( STM_LMS_URL . '/assets/icons/lessons/pending.gif' ); ?>" class="masterstudy-course-player-assignments__status-image">
				<?php
			} elseif ( $data['show_emoji'] && ! empty( $data['emoji_name'] ) ) {
				?>
					<p class="masterstudy-course-player-assignments__emoji"><?php echo esc_html( $data['emoji_name'] ); ?></p>
					<?php
			} else {
				?>
					<div class="masterstudy-course-player-assignments__status-icon"></div>
				<?php

			}
			?>
			<div class="masterstudy-course-player-assignments__status-wrapper">
				<div class="masterstudy-course-player-assignments__status-message">
					<?php echo esc_html( $data['status_messages'][ $current_template ] ); ?>
				</div>
				<?php
				if ( isset( $data['retake']['total'] ) && isset( $data['retake']['attempts'] ) && 'unpassed' === $current_template ) {
					?>
					<div class="masterstudy-course-player-assignments__status-attempts">
						<?php
						printf(
							/* translators: %s: number */
							esc_html__(
								'%1$s from %2$s attempts left.',
								'masterstudy-lms-learning-management-system-pro'
							),
							esc_html( $data['retake']['attempts'] ),
							esc_html( $data['retake']['total'] )
						);
						?>
					</div>
				<?php } ?>
			</div>
				<?php
				if ( $data['retake']['can_attempt'] && 'unpassed' === $current_template ) {
					$query_args = array(
						'start_assignment' => $item_id,
						'course_id'        => $post_id,
					);
					STM_LMS_Templates::show_lms_template(
						'components/button',
						array(
							'id'            => 'masterstudy-course-player-assignments-retake-button',
							'title'         => __( 'Retake', 'masterstudy-lms-learning-management-system-pro' ),
							'link'          => add_query_arg( $query_args, $data['actual_link'] ),
							'style'         => 'primary',
							'size'          => 'sm',
							'icon_position' => '',
							'icon_name'     => '',
						)
					);
				}
				?>
		</div>
	<?php } ?>
	<div class="masterstudy-course-player-assignments__task">
		<span class="masterstudy-course-player-assignments__accordion-button">
			<?php esc_html_e( 'Requirements', 'masterstudy-lms-learning-management-system-pro' ); ?>
		</span>
		<div class="masterstudy-course-player-assignments__accordion-content">
			<?php echo wp_kses_post( $data['content'] ); ?>
		</div>
	</div>
	<?php if ( 'draft' === $current_template ) { ?>
		<div class="masterstudy-course-player-assignments__edit" data-editor="<?php echo esc_attr( $data['editor_id'] ); ?>">
			<span class="masterstudy-course-player-assignments__edit-title">
				<?php esc_html_e( 'Assignment', 'masterstudy-lms-learning-management-system-pro' ); ?>
			</span>
			<?php
			STM_LMS_Templates::show_lms_template(
				'components/wp-editor',
				array(
					'id'          => $data['editor_id'],
					'content'     => $assignment_content,
					'settings'    => array(
						'quicktags'     => false,
						'media_buttons' => false,
						'textarea_rows' => 13,
					),
					'theme_fonts' => true,
					'dark_mode'   => $data['dark_mode'],
				)
			);
			?>
		</div>
		<?php
		STM_LMS_Templates::show_lms_template(
			'components/attachment-media',
			array(
				'assignment_id'     => $data['assignment_id'],
				'instructor_review' => false,
				'dark_mode'         => $data['dark_mode'],
			)
		);
	} else {
		?>
		<div class="masterstudy-course-player-assignments__user-answer">
			<span class="masterstudy-course-player-assignments__accordion-button masterstudy-course-player-assignments__accordion-button_rotate">
				<?php echo esc_html__( 'Your answer:', 'masterstudy-lms-learning-management-system-pro' ); ?>
			</span>
			<div class="masterstudy-course-player-assignments__accordion-content">
				<?php echo wp_kses_post( $assignment_content ); ?>
				<div class="masterstudy-course-player-assignments__user-answer-files">
					<?php
					if ( ! empty( $data['student_attachments'] ) ) {
						STM_LMS_Templates::show_lms_template(
							'components/file-attachment',
							array(
								'attachments' => $data['student_attachments'],
								'download'    => true,
								'deletable'   => false,
								'dark_mode'   => $data['dark_mode'],
							)
						);
					}
					?>
				</div>
			</div>
		</div>
		<?php
	}

	$editor_comment = get_post_meta( $data['assignment_id'], 'editor_comment', true );

	if ( ( 'passed' === $current_template || 'unpassed' === $current_template ) && ( ! empty( $editor_comment ) || ! empty( $data['instructor_attachments'] ) ) ) {
		STM_LMS_Templates::show_lms_template(
			'course-player/assignments/instructor-comment',
			array(
				'assignment_id' => $data['assignment_id'],
				'comment'       => $editor_comment,
				'attachments'   => $data['instructor_attachments'],
				'dark_mode'     => $data['dark_mode'],
			)
		);
	}

	do_action( 'stm_lms_after_assignment' );
	?>
</div>
