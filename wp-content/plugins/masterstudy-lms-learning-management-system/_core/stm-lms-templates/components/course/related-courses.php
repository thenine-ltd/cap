<?php
/**
 * @var object $course
 * @var integer $courses_per_page
 */

use MasterStudy\Lms\Repositories\CourseRepository;

$courses_per_page = isset( $courses_per_page ) ? $courses_per_page : 3;
$related_option   = STM_LMS_Options::get_option( 'related_option', 'by_category' );
$args             = array(
	'posts_per_page' => $courses_per_page,
	'exclude'        => array( $course->id ),
	'post_status'    => 'publish',
	'post_type'      => 'stm-courses',
	'fields'         => 'ids',
);

if ( 'by_category' === $related_option ) {
	$categories = wp_get_post_terms( $course->id, 'stm_lms_course_taxonomy' );
	$terms      = array();

	foreach ( $categories as $category ) {
		$terms[] = $category->term_id;
	}

	if ( ! empty( $terms ) ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'stm_lms_course_taxonomy',
				'field'    => 'term_id',
				'terms'    => $terms,
			),
		);
	}
} elseif ( 'by_author' === $related_option ) {
	$args['author'] = $course->owner;
} elseif ( 'by_level' === $related_option ) {
	$args['meta_query'] = array(
		array(
			'key'   => 'level',
			'value' => $course->level,
		),
	);
}

$course_ids = get_posts( $args );
$stars      = range( 1, 5 );

if ( ! empty( $course_ids ) ) { ?>
	<div class="masterstudy-related-courses">
		<span class="masterstudy-related-courses__title">
			<?php echo esc_html__( 'Related courses', 'masterstudy-lms-learning-management-system' ); ?>
		</span>
		<ul class="masterstudy-related-courses__list">
			<?php
			foreach ( $course_ids as $course_id ) {
				$related_course = ( new CourseRepository() )->find( $course_id, 'grid' );
				$course_status  = STM_LMS_Course::get_post_status( $course_id );
				$course_url     = STM_LMS_Course::courses_page_url() . $related_course->slug;
				$is_sale_active = STM_LMS_Helpers::is_sale_price_active( $course_id );
				$sale_price     = ! empty( $related_course->sale_price ) && $is_sale_active ? true : false;

				if ( $related_course->is_udemy_course ) {
					$author_name = $related_course->udemy_instructor['display_name'];
				} else {
					$author      = STM_LMS_User::get_current_user( $related_course->owner->ID );
					$author_name = $author['login'];
				}
				?>
				<li class="masterstudy-related-courses__item">
					<div class="masterstudy-related-courses__link">
						<a href="<?php echo esc_url( $course_url ); ?>" target="_blank" class="masterstudy-related-courses__image-wrapper">
							<?php if ( ! empty( $course_status ) ) { ?>
								<span class="masterstudy-related-courses__item-status <?php echo esc_attr( 'masterstudy-related-courses__item-status_' . $course_status['status'] ); ?>">
									<?php echo esc_html( $course_status['label'] ); ?>
								</span>
							<?php } ?>
							<img src="<?php echo esc_url( ! empty( $related_course->thumbnail['url'] ) ? $related_course->thumbnail['url'] : '#' ); ?>" alt="<?php echo esc_html( ! empty( $related_course->thumbnail['url'] ) ? $related_course->thumbnail['title'] : '' ); ?>" class="masterstudy-related-courses__image">
						</a>
						<div class="masterstudy-related-courses__item-meta">
							<a href="<?php echo esc_url( $course_url ); ?>" target="_blank" class="masterstudy-related-courses__item-title">
								<?php echo esc_html( stm_lms_minimize_word( $related_course->title, 40 ) ); ?>
							</a>
							<div class="masterstudy-related-courses__item-block">
								<?php if ( $related_course->not_single_sale && STM_LMS_Subscriptions::subscription_enabled() ) { ?>
									<div class="masterstudy-related-courses__subscription">
										<img class="masterstudy-related-courses__subscription-image" src="<?php echo esc_url( STM_LMS_URL . '/assets/img/members_only.svg' ); ?>" alt="<?php esc_attr_e( 'Members only', 'masterstudy-lms-learning-management-system' ); ?>"/>
										<div class="masterstudy-related-courses__subscription-title">
											<?php esc_html_e( 'Members only', 'masterstudy-lms-learning-management-system' ); ?>
										</div>
									</div>
								<?php } else { ?>
									<div class="masterstudy-related-courses__price <?php echo $sale_price ? 'masterstudy-related-courses__price_sale' : ''; ?>">
										<?php
										if ( $related_course->not_single_sale && ! $related_course->is_udemy_course ) {
											echo esc_html__( 'Free', 'masterstudy-lms-learning-management-system' );
										} else {
											if ( $related_course->is_udemy_course ) {
												echo esc_html( STM_LMS_Helpers::display_price( $related_course->price ) );
											} else {
												echo esc_html( ( ! empty( $related_course->price ) && 0 !== floatval( $related_course->price ) ) ? STM_LMS_Helpers::display_price( $related_course->price ) : __( 'Free', 'masterstudy-lms-learning-management-system' ) );
											}
										}
										?>
									</div>
									<?php
									if ( $sale_price ) {
										?>
										<div class="masterstudy-related-courses__price-sale">
											<?php echo esc_html( STM_LMS_Helpers::display_price( $related_course->sale_price ) ); ?>
										</div>
										<?php
									}
								}
								if ( ! empty( $related_course->rate ) && ! $related_course->is_udemy_course ) {
									?>
									<div class="masterstudy-related-courses__rating">
										<?php foreach ( $stars as $star ) { ?>
											<span class="masterstudy-related-courses__rating-star <?php echo esc_attr( ( $star <= floor( $related_course->rate['average'] ) ) ? 'masterstudy-related-courses__rating-star_filled' : '' ); ?>"></span>
										<?php } ?>
									</div>
								<?php } elseif ( ! empty( $related_course->rate ) && $related_course->is_udemy_course ) { ?>
									<div class="masterstudy-related-courses__rating">
										<?php foreach ( $stars as $star ) { ?>
											<span class="masterstudy-related-courses__rating-star <?php echo esc_attr( ( $star <= floor( $related_course->udemy_rate ) ) ? 'masterstudy-related-courses__rating-star_filled' : '' ); ?>"></span>
										<?php } ?>
									</div>
								<?php } ?>
							</div>
							<a class="masterstudy-related-courses__instructor" href="<?php echo esc_url( $related_course->is_udemy_course ? $course_url : STM_LMS_User::user_public_page_url( $related_course->owner->ID ) ); ?>" target="_blank">
								<?php
								printf(
									/* translators: %s Instructor */
									esc_html__( 'By %s', 'masterstudy-lms-learning-management-system' ),
									esc_html( $author_name )
								);
								?>
							</a>
						</div>
					</div>
				</li>
			<?php } ?>
		</ul>
	</div>
	<?php
}
