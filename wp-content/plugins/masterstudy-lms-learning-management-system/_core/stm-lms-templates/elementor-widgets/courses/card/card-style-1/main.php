<?php
foreach ( $courses as $course ) {
	$course = STM_LMS_Courses::get_course_submetas( $course, $course_image_size );
	?>
	<div class="ms_lms_courses_card_item <?php echo esc_attr( $featured ?? '' ); ?> <?php echo esc_attr( ( 'courses-carousel' === $widget_type ) ? 'swiper-slide' : '' ); ?>">
		<div class="ms_lms_courses_card_item_wrapper">
			<?php if ( ! empty( $course['featured'] ) ) { ?>
				<div class="ms_lms_courses_card_item_featured <?php echo esc_attr( $card_data['featured_position'] ?? '' ); ?>">
					<span><?php echo esc_html__( 'Featured', 'masterstudy-lms-learning-management-system' ); ?></span>
				</div>
				<?php
			}
			if ( ! empty( $course['current_status'] ) ) {
				?>
				<div class="ms_lms_courses_card_item_status <?php echo esc_attr( ( ! empty( $card_data['status_presets'] ) ) ? $card_data['status_presets'] : '' ); ?> <?php echo esc_attr( ( ! empty( $card_data['status_position'] ) ) ? $card_data['status_position'] : '' ); ?> <?php echo esc_attr( ( ! empty( $course['current_status']['status'] ) ) ? $course['current_status']['status'] : '' ); ?>">
					<span><?php echo esc_html( $course['current_status']['label'] ); ?></span>
				</div>
			<?php } ?>
			<a href="<?php echo esc_url( $course['url'] ); ?>" class="ms_lms_courses_card_item_image_link">
				<img src="<?php echo esc_url( $course['image'] ); ?>" class="ms_lms_courses_card_item_image">
			</a>
			<div class="ms_lms_courses_card_item_info">
				<?php if ( ! empty( $card_data['show_category'] ) && ! empty( $course['terms'] ) ) { ?>
					<span class="ms_lms_courses_card_item_info_category">
						<a href="<?php echo esc_url( STM_LMS_Course::courses_page_url() . '?terms[]=' . $course['terms']->term_id . '&category[]=' . $course['terms']->term_id ); ?>">
							<?php echo esc_html( $course['terms']->name ); ?>
						</a>
						
					</span>
				<?php } ?>
					<a href="<?php echo esc_url( $course['url'] ); ?>" class="ms_lms_courses_card_item_info_title">
						<h3><?php echo esc_html( $course['post_title'] ); ?></h3>
					</a>
				<?php
				if ( ! empty( $card_data['show_progress'] && isset( $course['progress'] ) && $course['progress'] > 0 ) ) {
					STM_LMS_Templates::show_lms_template(
						"elementor-widgets/courses/card/{$course_card_presets}/progress-bar",
						array(
							'course'    => $course,
							'card_data' => $card_data,
						)
					);
				}?>
<?php if ( ! ( empty( $popup_data['popup_show_author_image'] ) && empty( $popup_data['popup_show_author_name'] ) ) ) { ?>
		<div class="ms_lms_courses_card_item_popup_author">
			<?php
			if ( ! empty( $popup_data['popup_show_author_image'] ) ) {
				?>
				<img src="<?php echo esc_url( $course['author_info']['avatar_url'] ); ?>">
				<?php
			}
			if ( ! empty( $popup_data['popup_show_author_name'] ) ) {
				?>
				<span class="ms_lms_courses_card_item_popup_author_name"><label>Giảng viên</label><?php echo esc_html( $course['author_info']['login'] ); ?></span>
			<?php } ?>
		</div>
	<?php } ?>
				<?php
				if ( ! empty( $card_data['show_slots'] ) && ! ( 'empty' === $meta_slots['card_slot_1'] && 'empty' === $meta_slots['card_slot_2'] ) ) {
					?>
					<div class="ms_lms_courses_card_item_info_meta">
							
						<?php
						if ( 'empty' !== $meta_slots['card_slot_1'] ) {
							STM_LMS_Templates::show_lms_template(
								'elementor-widgets/courses/card/global/meta-slot/main',
								array(
									'meta_slot' => $meta_slots['card_slot_1'],
									'course'    => $course,
								)
							);
						}
						if ( 'empty' !== $meta_slots['card_slot_2'] ) {
							STM_LMS_Templates::show_lms_template(
								'elementor-widgets/courses/card/global/meta-slot/main',
								array(
									'meta_slot' => $meta_slots['card_slot_2'],
									'course'    => $course,
								)
							);
						}
						?>
					</div>
					<?php
				}
				if ( ! empty( $card_data['show_divider'] ) ) {
					?>
					<span class="ms_lms_courses_card_item_info_divider"></span>
					<?php
				}
				if ( $course['availability'] && is_ms_lms_addon_enabled( 'coming_soon' ) ) {
					STM_LMS_Templates::show_lms_template(
						'global/coming_soon',
						array(
							'course_id' => $course['id'],
							'mode'      => 'card',
						),
					);
				} elseif ( ! ( empty( $card_data['show_rating'] ) && empty( $card_data['show_price'] ) ) ) {
					?>
					<div class="ms_lms_courses_card_item_info_bottom_wrapper">
						<a href="<?php echo esc_url( $course['url'] ); ?>" class="ms_lms_courses_card_item_popup_button">
							<span><?php esc_html_e( 'Preview this course', 'masterstudy-lms-learning-management-system' ); ?></span>
							<?php if ( $course['is_trial'] ) : ?>
							<small><?php esc_html_e( 'Free Lesson(s) Offer', 'masterstudy-lms-learning-management-system' ); ?></small>
							<?php endif; ?>
						</a>						
						<?php
						if ( ! empty( $card_data['show_rating'] ) ) {
							STM_LMS_Templates::show_lms_template(
								'elementor-widgets/courses/card/global/rating',
								array(
									'card_data' => $card_data,
									'course'    => $course,
								)
							);
						}
						if ( ! empty( $card_data['show_price'] ) ) {
							STM_LMS_Templates::show_lms_template(
								'elementor-widgets/courses/card/global/price',
								array(
									'card_data' => $card_data,
									'course'    => $course,
								)
							);
						}
						?>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php
		if ( ! empty( $card_data['show_popup'] ) ) {
			STM_LMS_Templates::show_lms_template(
				'elementor-widgets/courses/card/global/popup',
				array(
					'course'              => $course,
					'meta_slots'          => $meta_slots,
					'popup_data'          => $popup_data,
					'course_card_presets' => $course_card_presets,
				)
			);
		}
		?>
	</div>
	<?php
}
