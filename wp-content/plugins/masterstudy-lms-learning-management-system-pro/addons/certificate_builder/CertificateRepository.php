<?php

namespace MasterStudy\Lms\Pro\addons\certificate_builder;

use MasterStudy\Lms\Repositories\AbstractRepository;

final class CertificateRepository extends AbstractRepository {
	const DEFAULT_CERTIFICATE = 'stm_default_certificate';

	protected static string $post_type = 'stm-certificates';

	protected static array $fields_post_map = array(
		'id'        => 'ID',
		'title'     => 'post_title',
		'author_id' => 'post_author',
	);

	protected static array $fields_meta_map = array(
		'orientation' => 'stm_orientation',
		'fields'      => 'stm_fields',
		'category'    => 'stm_category',
	);

	public function get_first_for_categories( array $categories ): int {
		global $wpdb;
		$categories_list = implode( ',', array_map( 'intval', $categories ) );

		$certificate_ids = $wpdb->get_col(
			$wpdb->prepare(
				"
				SELECT p.ID
				FROM {$wpdb->posts} AS p
				INNER JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id
				WHERE p.post_type = 'stm-certificates'
				AND pm.meta_key = 'stm_category'
				AND (pm.meta_value REGEXP CONCAT('(^|,)', %s, '(,|$)'))
				ORDER BY pm.meta_value ASC
				LIMIT 1
				",
				$categories_list
			)
		);

		if ( empty( $certificate_ids ) ) {
			$default_certificate = self::get_default_certificate();
			if ( ! empty( $default_certificate ) ) {
				$certificate_ids[] = $default_certificate;
			}
		}

		return $certificate_ids[0] ?? 0;
	}

	public function get_all(): array {
		$author_id  = get_current_user_id();
		$admin_page = is_admin();
		$pages      = $admin_page ? -1 : intval( $_GET['per_page'] ?? 10 );
		$args       = array(
			'post_type'      => 'stm-certificates',
			'posts_per_page' => $pages,
		);

		if ( ! $admin_page ) {
			$args['paged'] = intval( $_GET['page'] ?? ( get_query_var( 'page' ) ?? 1 ) );
			if ( ! empty( $_GET['s'] ) ) {
				$args['s'] = sanitize_text_field( wp_unslash( $_GET['s'] ) );
			}
			if ( ! empty( $_GET['by_category'] ) ) {
				$args['post__in'] = array( $this->get_first_for_categories( array( intval( $_GET['by_category'] ) ) ) );
			}
			if ( ! empty( $_GET['by_instructor'] ) ) {
				$args['author'] = intval( $_GET['by_instructor'] );
			}
		}

		if ( ! current_user_can( 'administrator' ) ) {
			$args['author'] = $author_id;
		}

		$query = new \WP_Query();

		$certificates        = array();
		$default_certificate = get_option( 'stm_default_certificate' );

		foreach ( $query->query( $args ) as $post ) {
			$certificate = $this->map_post( $post );

			foreach ( self::$fields_meta_map as $field => $meta ) {
				$certificate[ $field ] = $this->cast( $field, get_post_meta( $post->ID, $meta, true ) );

				if ( 'fields' !== $field && isset( $certificate['category'] ) ) {
					$category_ids                 = explode( ',', $certificate['category'] );
					$category                     = get_term_by( 'id', $category_ids[0], 'stm_lms_course_taxonomy' );
					$certificate['category_name'] = $category ? $category->name : '';
				}
			}

			if ( ! $admin_page ) {
				$certificate['image']      = get_post_meta( $post->ID, 'certificate_preview', true );
				$certificate['is_default'] = ! empty( $default_certificate ) && intval( $default_certificate ) === intval( $post->ID );
				$certificate['instructor'] = get_the_author_meta( 'display_name', $certificate['author_id'] );
				$certificate['edit_link']  = admin_url( 'admin.php?page=certificate_builder&certificate_id=' . $certificate['id'] );
			}

			$certificates[] = $certificate;
		}

		if ( ! $admin_page ) {
			return array(
				'certificates' => $certificates,
				'max_pages'    => $query->max_num_pages,
				'per_page'     => intval( $_GET['per_page'] ?? 10 ),
				'page'         => intval( $_GET['page'] ?? 1 ),
			);
		}

		return $certificates;
	}

	public static function get_default_certificate() {
		return get_option( self::DEFAULT_CERTIFICATE, '' );
	}

	public static function set_default_certificate( $certificate_id ): void {
		update_option( self::DEFAULT_CERTIFICATE, $certificate_id );
	}

	protected function update_meta( $id, $data ): void {
		parent::update_meta( $id, $data );

		if ( ! empty( $data['thumbnail_id'] ) ) {
			set_post_thumbnail( $id, intval( $data['thumbnail_id'] ) );
		}

		$code = get_post_meta( $id, 'code', true );
		if ( empty( $code ) ) {
			update_post_meta( $id, 'code', CodeGenerator::generate() );
		}
	}
}
