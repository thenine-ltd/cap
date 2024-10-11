<?php
$args = array(
	'post_type'      => 'stm-courses',
	'posts_per_page' => 5,
);

if ( ! empty( $query ) ) {
	$args = array_merge( $args, STM_LMS_Helpers::sort_query( esc_attr( $query ) ) );
}

if ( ! empty( $taxonomy ) ) {
	$args['tax_query'] = array(
		array(
			'taxonomy' => 'stm_lms_course_taxonomy',
			'terms'    => esc_attr( $taxonomy ),
		),
	);
}

$q = new WP_Query( $args );

stm_lms_register_style( 'course' );
wp_enqueue_script( 'imagesloaded' );
wp_enqueue_script( 'owl.carousel' );
wp_enqueue_style( 'owl.carousel' );
stm_lms_module_styles( 'single_course_carousel' );
stm_lms_module_scripts( 'single_course_carousel', 'style_1' );


if ( $q->have_posts() ) :
	?>
	<div class="stm_lms_single_course_carousel_wrapper <?php // phpcs:ignore Squiz.PHP.EmbeddedPhp
	echo esc_attr( $uniq ?? '' );

	if ( isset( $prev_next ) && 'disable' === $prev_next ) {
		echo esc_attr( 'no-nav' );
	}

	// phpcs:ignore Squiz.PHP.EmbeddedPhp
	?>"
		data-items="1"
		data-pagination="<?php echo esc_attr( $pagination ); ?>">
		<div class="stm_lms_single_course_carousel">
			<?php
			while ( $q->have_posts() ) :
				$q->the_post();
				$post_id  = get_the_ID();
				$level    = get_post_meta( $post_id, 'level', true );
				$duration = get_post_meta( $post_id, 'duration_info', true );
				$lectures = STM_LMS_Course::curriculum_info( $post_id );
				?>

				<div class="stm_lms_single_course_carousel_item stm_carousel_glitch">

					<a href="<?php the_permalink(); ?>" class="stm_lms_single_course_carousel_item__image">
						<?php
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo stm_lms_lazyload_image( stm_lms_get_VC_attachment_img_safe( get_post_thumbnail_id(), '504x335' ) );
						?>
					</a>

					<div class="stm_lms_single_course_carousel_item__content">
						<?php STM_LMS_Templates::show_lms_template( 'course/parts/panel_info', array( 'number' => 1 ) ); ?>

						<h2><a class="h2" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

<div class="masterstudy-single-course-instructor <?php echo esc_attr( $instructor_class ); ?>">
				<div class="masterstudy-single-course-instructor__avatar">
		<?php
		if ( $course->is_udemy_course ) {
			?>
			<img src="<?php echo esc_url( $course->udemy_instructor['image_100x100'] ); ?>">
			<?php
		} else {
			echo wp_kses_post( $instructor['avatar'] );
			if ( $co_instructor ) {
				echo wp_kses_post( $co_instructor['avatar'] );
			}
		}
		?>
	</div>
	<div class="masterstudy-single-course-instructor__info">
		<?php if ( ! $without_title ) { ?>
			<div class="masterstudy-single-course-instructor__title">
				<?php
				if ( $co_instructor ) {
					echo esc_html__( 'Instructors', 'masterstudy-lms-learning-management-system' );
				} else {
					echo esc_html__( 'Instructor', 'masterstudy-lms-learning-management-system' );
				}
				?>
			</div>
		<?php } ?>
		<a class="masterstudy-single-course-instructor__name" href="<?php echo $course->is_udemy_course ? esc_url( "https://www.udemy.com{$course->udemy_instructor['url']}" ) : esc_url( STM_LMS_User::user_public_page_url( $instructor['id'] ) ); ?>" target="_blank">
			<?php
			if ( $course->is_udemy_course ) {
				echo esc_html( $course->udemy_instructor['display_name'] );
			} else {
				echo esc_html( $instructor['login'] );
			}
			?>
		</a>
		<?php if ( ! $course->is_udemy_course && $co_instructor ) { ?>
			<a class="masterstudy-single-course-instructor__co-instructor" href="<?php echo esc_url( STM_LMS_User::user_public_page_url( $co_instructor['id'] ) ); ?>" target="_blank">
				<?php echo esc_html( $co_instructor['login'] ); ?>
			</a>
		<?php } ?>
	</div>
</div>

						<!-- End Course Detail -->

						<div class="stm_lms_courses__single__buttons">
							<div class="stm_lms_courses__single__buy">
								<?php
								STM_LMS_Templates::show_lms_template(
									'components/buy-button/buy-button',
									array(
										'post_id'   => $post_id,
										'item_id'   => '',
										'user_id'   => get_current_user_id(),
										'dark_mode' => false,
										'prerequisite_preview' => false,
										'hide_group_course' => false,
									)
								);
								?>
							</div>
							<?php STM_LMS_Templates::show_lms_template( 'global/wish-list', array( 'course_id' => $post_id ) ); ?>
						</div>

					</div>

				</div>

			<?php endwhile; ?>
		</div>

		<?php if ( 'disable' !== $prev_next ) : ?>
			<div class="stm_lms_courses_carousel__buttons">
				<div class="stm_lms_courses_carousel__button stm_lms_courses_carousel__button_prev sbc_h sbrc_h">
					<i class="fa fa-chevron-left"></i>
				</div>
				<div class="stm_lms_courses_carousel__button stm_lms_courses_carousel__button_next sbc_h sbrc_h">
					<i class="fa fa-chevron-right"></i>
				</div>
			</div>
		<?php endif; ?>

	</div>
	<?php
endif;

wp_reset_postdata();
