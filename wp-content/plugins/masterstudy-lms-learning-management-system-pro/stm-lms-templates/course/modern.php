<?php
$course_data = apply_filters( 'masterstudy_course_page_header', 'modern' );
$full  = isset( $full ) ? $full : false;
$image = $full ? $course->full_image : $course->thumbnail;
if ( ! is_array( $course_data ) ) {
	return;
}
?>

<div class="masterstudy-single-course-modern">
	<div class="cap-single-course-top">
		<div class="masterstudy-single-course-modern__topbar">
			<div class="cap-single-course-topbar-top">
				<?php if ( ! $course_data['is_coming_soon'] || $course_data['course']->coming_soon_details ) { ?>
					<div class="masterstudy-single-course-modern__row">
						<?php if (function_exists('rank_math_the_breadcrumbs')) rank_math_the_breadcrumbs(); ?>
						<?php
						/*
						if ( ! empty( $course_data['course']->rate ) || ! empty( $course_data['course']->udemy_rate ) ) {
							STM_LMS_Templates::show_lms_template( 'components/course/rating', array( 'course' => $course_data['course'] ) );
						}
						STM_LMS_Templates::show_lms_template( 'components/course/status', array( 'course' => $course_data['course'] ) );
						*/
						?>
					</div>
				<?php } ?>
				<div class="masterstudy-single-course-modern__heading">
					<?php STM_LMS_Templates::show_lms_template( 'components/course/title', array( 'title' => $course_data['course']->title ) ); ?>
				</div>
				<?php
				if ( ! empty( $course_data['course']->excerpt ) || ! empty( $course_data['course']->udemy_headline ) ) {
					?>
					<div class="masterstudy-single-course-modern__desc">
						<?php STM_LMS_Templates::show_lms_template( 'components/course/excerpt', array( 'course' => $course_data['course'] ) ); ?>
					</div>
					<?php
				}
				if ( ! $course_data['is_coming_soon'] || $course_data['course']->coming_soon_details ) {
					?>
					<div class="masterstudy-single-course-modern__info">
						<div class="masterstudy-single-course-modern__info-block">
							<?php
							// Lecturer
							STM_LMS_Templates::show_lms_template(
								'components/course/instructor',
								array(
									'instructor' => $course_data['instructor'],
									'course'     => $course_data['course'],
								)
							);
							
							?>
						</div>
						<?php
						if ( ! empty( $course_data['course']->category ) ) {
							?>
							<div class="masterstudy-single-course-modern__info-block">
								<?php
								// Progress
								STM_LMS_Templates::show_lms_template(
									'components/course/complete',
									array(
										'course_id'     => $course_data['course']->id,
										'user_id'       => $course_data['current_user_id'],
										'settings'      => $course_data['settings'],
										'block_enabled' => true,
									)
								);
								
								?>
							</div>
							<?php
						}
						?>
					</div>
			</div>		
			<?php } ?>
			<?php

		if ( ! $course_data['is_coming_soon'] || $course_data['course']->coming_soon_preorder ) {
			?>
			<div class="masterstudy-single-course-modern__cta">
				<?php
				STM_LMS_Templates::show_lms_template(
					'components/buy-button/buy-button',
					array(
						'post_id'              => $course_data['course']->id,
						'item_id'              => '',
						'user_id'              => $course_data['current_user_id'],
						'dark_mode'            => false,
						'prerequisite_preview' => false,
						'hide_group_course'    => false,
					)
				);
				?>
			</div><!-- End TOPBar TOP -->
			<?php
		}
		if ( $course_data['is_coming_soon'] && $course_data['course']->coming_soon_price && ! $course_data['course']->coming_soon_preorder ) {
			?>
			
			<div class="masterstudy-single-course-modern__cta">
				<?php STM_LMS_Templates::show_lms_template( 'components/course/coming-button' ); ?>
			</div>
			<?php
		}
		?>
		</div>
		<?php 

		?>
		<div class="cap-single-course-img" style="background:url('<?php echo esc_url( $image['url'] ); ?>')">
		<?php
		STM_LMS_Templates::show_lms_template(
			'components/course/thumbnail',
			array(
				'course'         => $course_data['course'],
				'course_preview' => $course_data['course_preview'] ?? '',
			)
		);
		?>								
		</div>
	</div>
	<div class="masterstudy-single-course-modern__main">
		<div class="masterstudy-single-course-modern__bottombar">
			<?php
			STM_LMS_Templates::show_lms_template(
				'global/coming_soon',
				array(
					'course_id' => $course_data['course']->id,
					'mode'      => 'course',
				),
			);
			STM_LMS_Templates::show_lms_template(
				'components/course/tabs',
				array(
					'course'         => $course_data['course'],
					'course_preview' => $course_data['course_preview'] ?? '',
					'user_id'        => $course_data['current_user_id'],
					'style'          => 'underline',
				)
			);
			if ( $course_data['settings']['enable_related_courses'] ) {
				STM_LMS_Templates::show_lms_template( 'components/course/related-courses', array( 'course' => $course_data['course'] ) );
			}
			?>
		</div>
	</div>
	<div class="masterstudy-single-course-modern__sidebar">
		<?php
			STM_LMS_Templates::show_lms_template(
				'global/coming_soon',
				array(
					'course_id' => $course_data['course']->id,
					'mode'      => 'course',
				),
			);
			STM_LMS_Templates::show_lms_template(
				'components/course/tabs',
				array(
					'course'         => $course_data['course'],
					'course_preview' => $course_data['course_preview'] ?? '',
					'user_id'        => $course_data['current_user_id'],
					'style'          => 'underline',
				)
			);
			?>
			<?php
		STM_LMS_Templates::show_lms_template(
			'components/course/expired',
			array(
				'course'         => $course_data['course'],
				'user_id'        => $course_data['current_user_id'],
				'is_coming_soon' => $course_data['is_coming_soon'],
			)
		);
		?>

	</div>
</div>
