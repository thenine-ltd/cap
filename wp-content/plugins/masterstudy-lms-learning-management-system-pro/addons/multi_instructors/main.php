<?php

new STM_LMS_Multi_Instructors();

class STM_LMS_Multi_Instructors {

	public function __construct() {
		add_action( 'stm_lms_after_teacher_end', array( $this, 'front_co_instructor' ) );
		add_action( 'stm_lms_instructor_courses_end', array( $this, 'co_courses' ) );
	}

	public static function front_co_instructor() {
		STM_LMS_Templates::show_lms_template( 'multi_instructor/front/main' );
	}

	public function co_courses() {
		STM_LMS_Templates::show_lms_template( 'multi_instructor/co_courses/main' );
	}


	/*Co Courses*/
	public static function per_page() {
		return 6;
	}

	public static function getCoCourses( $user_id = '', $return_args = false ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}
		$r        = array( 'posts' => array() );
		$per_page = self::per_page();

		$page   = ( ! empty( $_GET['page'] ) ) ? intval( $_GET['page'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$offset = ( ! empty( $page ) ) ? ( $page * $per_page ) - $per_page : 0;

		$args = array(
			'post_type'      => 'stm-courses',
			'posts_per_page' => $per_page,
			'post_status'    => array( 'any' ),
			'meta_query'     => array(
				array(
					'key'     => 'co_instructor',
					'value'   => $user_id,
					'compare' => '=',
				),
			),
		);

		if ( ! empty( $offset ) ) {
			$args['offset'] = $offset;
		}

		if ( $return_args ) {
			return $args;
		}

		$q = new WP_Query( $args );

		if ( $q->have_posts() ) {
			while ( $q->have_posts() ) {
				$q->the_post();
				$id = get_the_ID();

				$rating  = get_post_meta( $id, 'course_marks', true );
				$rates   = STM_LMS_Course::course_average_rate( $rating );
				$average = $rates['average'];
				$percent = $rates['percent'];

				$status = get_post_status( $id );

				$price      = get_post_meta( $id, 'price', true );
				$sale_price = get_post_meta( $id, 'sale_price', true );

				switch ( $status ) {
					case 'publish':
						$status_label = esc_html__( 'Published', 'masterstudy-lms-learning-management-system-pro' );
						break;
					case 'pending':
						$status_label = esc_html__( 'Pending', 'masterstudy-lms-learning-management-system-pro' );
						break;
					default:
						$status_label = esc_html__( 'Draft', 'masterstudy-lms-learning-management-system-pro' );
						break;
				}

				$post_status = STM_LMS_Course::get_post_status( $id );

				$image          = ( function_exists( 'stm_get_VC_img' ) ) ? html_entity_decode( stm_get_VC_img( get_post_thumbnail_id(), '272x161' ) ) : get_the_post_thumbnail( $id, 'img-300-225' );
				$image_small    = ( function_exists( 'stm_get_VC_img' ) ) ? html_entity_decode( stm_get_VC_img( get_post_thumbnail_id(), '50x50' ) ) : get_the_post_thumbnail( $id, 'img-300-225' );
				$is_featured    = get_post_meta( $id, 'featured', true );
				$authors        = array( get_the_author_meta( 'ID' ) );
				$co_instructors = (array) get_post_meta( $id, 'co_instructor', false );
				$authors        = array_merge( $authors, $co_instructors );

				$post = array(
					'id'           => $id,
					'time'         => get_post_time( 'U', true ),
					'title'        => get_the_title(),
					'link'         => get_the_permalink(),
					'image'        => $image,
					'image_small'  => $image_small,
					'terms'        => stm_lms_get_terms_array( $id, 'stm_lms_course_taxonomy', false, true ),
					'status'       => $status,
					'status_label' => $status_label,
					'percent'      => $percent,
					'is_featured'  => $is_featured,
					'average'      => $average,
					'total'        => ( ! empty( $rating ) ) ? count( $rating ) : 0,
					'views'        => STM_LMS_Course::get_course_views( $id ),
					'simple_price' => $sale_price ? $sale_price : $price,
					'sale_price'   => $sale_price ? STM_LMS_Helpers::display_price( $sale_price ) : 0,
					'price'        => STM_LMS_Helpers::display_price( $price ),
					'edit_link'    => ms_plugin_manage_course_url( $id ),
					'post_status'  => $post_status,
					'authors'      => array_map( 'intval', $authors ),
					'current_user' => get_current_user_id(),
				);

				$post['sale_price'] = ( ! empty( $sale_price ) ) ? STM_LMS_Helpers::display_price( $sale_price ) : '';

				$r['posts'][] = $post;
			}
		}

		$r['pages'] = ceil( $q->found_posts / $per_page );

		return $r;
	}
}
