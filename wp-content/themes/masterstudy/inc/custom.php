<?php
// Add svg support
function stm_svg_mime( $mimes ) {
	$mimes['ico'] = 'image/icon';

	return $mimes;
}

add_filter( 'upload_mimes', 'stm_svg_mime' );

// Comments
if ( ! function_exists( 'stm_comment' ) ) {
	function stm_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		extract( $args, EXTR_SKIP );

		if ( 'div' === $args['style'] ) {
			$tag       = 'div';
			$add_below = 'comment';
		} else {
			$tag       = 'li';
			$add_below = 'div-comment';
		}
		?>
		<<?php echo esc_attr( $tag . ' ' ); ?><?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID(); ?>">
		<?php if ( 'div' !== $args['style'] ) { ?>
			<div id="div-comment-<?php comment_ID(); ?>" class="comment-body clearfix">
		<?php } ?>
		<?php if ( 0 !== $args['avatar_size'] ) { ?>
			<div class="vcard">
				<?php echo get_avatar( $comment, 75 ); ?>
			</div>
		<?php } ?>
		<div class="comment-info clearfix">
			<div class="comment-author pull-left"><span class="h4"><?php echo get_comment_author_link(); ?></span></div>
			<div class="comment-meta commentmetadata pull-right">
				<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
				<?php
					printf(
					/* translators: %s: date and time */
						esc_attr__( '%1$s at %2$s', 'masterstudy' ),
						esc_attr( get_comment_date() ),
						esc_attr( get_comment_time() )
					);
				?>
				</a>
				<span class="h6">
				<?php
				comment_reply_link(
					array_merge(
						$args,
						array(
							'reply_text' => __( '<span class="vertical_divider"></span>Reply <i class="fa fa-reply"></i>', 'masterstudy' ),
							'add_below'  => $add_below,
							'depth'      => $depth,
							'max_depth'  => $args['max_depth'],
						)
					)
				);
				?>
									</span>
				<span class="h6"><?php edit_comment_link( __( '<span class="vertical_divider"></span>Edit <i class="fa fa-pencil-square-o"></i>', 'masterstudy' ), '  ', '' ); ?></span>
			</div>
			<?php if ( '0' === $comment->comment_approved ) { ?>
				<em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'masterstudy' ); ?></em>
			<?php } ?>
		</div>
		<div class="comment-text">
			<?php comment_text(); ?>
		</div>

		<?php if ( 'div' !== $args['style'] ) { ?>
			</div>
		<?php } ?>
		<?php
	}
}

add_filter( 'comment_form_default_fields', 'bootstrap3_comment_form_fields' );

if ( ! function_exists( 'bootstrap3_comment_form_fields' ) ) {
	function bootstrap3_comment_form_fields( $fields ) {
		$commenter = wp_get_current_commenter();
		$req       = get_option( 'require_name_email' );
		$aria_req  = ( $req ? " aria-required='true'" : '' );
		$html5     = current_theme_supports( 'html5', 'comment-form' ) ? 1 : 0;
		$fields    = array(
			'author' => '<div class="row">
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<div class="form-group comment-form-author">
			            			<input placeholder="' . esc_attr__( 'Name', 'masterstudy' ) . ( $req ? ' *' : '' ) . '" class="form-control" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' />
		                        </div>
		                    </div>',
			'email'  => '<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<div class="form-group comment-form-email">
								<input placeholder="' . esc_attr__( 'E-mail', 'masterstudy' ) . ( $req ? ' *' : '' ) . '" class="form-control" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' />
							</div>
						</div>',
			'url'    => '</div>',
		);

		return $fields;
	}
}

add_filter( 'comment_form_defaults', 'bootstrap3_comment_form' );

if ( ! function_exists( 'bootstrap3_comment_form' ) ) {
	function bootstrap3_comment_form( $args ) {
		$args['comment_field'] = '<div class="form-group comment-form-comment">
							        <textarea placeholder="' . esc_attr_x( 'Message', 'noun', 'masterstudy' ) . ' *" class="form-control" name="comment" rows="9" aria-required="true"></textarea>
								  </div>';

		return $args;
	}
}

add_action( 'wp_head', 'stm_ajaxurl' );
add_action( 'admin_head', 'stm_ajaxurl' );

function stm_ajaxurl() {
	$stm_install_plugin    = wp_create_nonce( 'stm_install_plugin' );
	$stm_buddypress_groups = wp_create_nonce( 'stm_buddypress_groups' );
	$stm_ajax_add_review   = wp_create_nonce( 'stm_ajax_add_review' );
	?>
	<script>
		var ajaxurl = '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>';
		var stm_install_plugin = '<?php echo esc_js( $stm_install_plugin ); ?>';
		var stm_buddypress_groups = '<?php echo esc_js( $stm_buddypress_groups ); ?>';
		var stm_ajax_add_review = '<?php echo esc_js( $stm_ajax_add_review ); ?>';
	</script>
	<?php
}

function set_html_content_type() {
	return 'text/html';
}

/* Custom ajax loader */
add_filter( 'wpcf7_ajax_loader', 'my_wpcf7_ajax_loader' );
function my_wpcf7_ajax_loader() {
	return get_stylesheet_directory_uri() . '/assets/img/ajax-loader.gif';
}

function stm_wp_head() {
	$favicon = stm_option( 'favicon', false, 'url' );
	if ( $favicon ) {
		echo '<link rel="shortcut icon" type="image/x-icon" href="' . esc_url( $favicon ) . '" />' . "\n";
	} else {
		echo '<link rel="shortcut icon" type="image/x-icon" href="' . esc_url( get_template_directory_uri() ) . '/favicon.ico" />' . "\n";
	}
}

add_action( 'wp_head', 'stm_wp_head' );

if ( ! function_exists( '_wp_render_title_tag' ) ) {
	function theme_slug_render_title() {
		?>
		<title><?php wp_title( '|', true, 'right' ); ?></title>
		<?php
	}

	add_action( 'wp_head', 'theme_slug_render_title' );
}

function is_stm() {
	return false;
}

function stm_gallery_posts_per_page( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( is_post_type_archive( 'gallery' ) ) {
		$query->set( 'posts_per_page', - 1 );

		return;
	}

	if ( is_category() || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {
		$query->set( 'post_type', array( 'events', 'post' ) );

		return $query;
	}

}

add_action( 'pre_get_posts', 'stm_gallery_posts_per_page', 1 );

function stm_body_class( $classes ) {
	$classes[] = stm_option( 'color_skin' );
	$classes[] = get_option( 'stm_lms_layout', 'default' );
	$classes[] = 'masterstudy-theme stm_preloader_' . stm_option( 'preloader', false );

	return $classes;
}

add_filter( 'body_class', 'stm_body_class' );

function stm_print_styles() {
	$site_css = stm_option( 'site_css' );
	if ( $site_css ) {
		$site_css .= preg_replace( '/\s+/', ' ', $site_css );
	}

	wp_add_inline_style( 'stm_theme_custom_styles', $site_css );
}

add_action( 'wp_enqueue_scripts', 'stm_print_styles' );

function stm_move_comment_field_to_bottom( $fields ) {
	$comment_field = $fields['comment'];
	unset( $fields['comment'] );
	$fields['comment'] = $comment_field;

	return $fields;
}

add_filter( 'comment_form_fields', 'stm_move_comment_field_to_bottom' );

function stm_can_view_lesson( $productId, $user = null ) {
	// Get current user if null is passed
	if ( is_null( $user ) ) {
		$_user = wp_get_current_user();
	} else {
		$_user = new WP_User( $user );
	}

	$can = false;

	if ( $_user instanceof WP_User && $_user->ID ) {
		$can = wc_customer_bought_product( $_user->user_email, $_user->ID, $productId );
	}

	return apply_filters( 'stm_can_view_lesson', $can, $productId, $user );
}

if ( ! function_exists( 'stm_module_styles' ) ) {
	function stm_module_styles( $handle, $style = 'style_1', $deps = array(), $inline_styles = '' ) {
		if ( empty( $handle ) || empty( $style ) ) {
			return;
		}

		$path   = get_template_directory_uri() . '/assets/css/vc_modules/' . $handle . '/' . $style . '.css';
		$handle = 'stm-' . $handle . '-' . $style;

		wp_enqueue_style( $handle, $path, $deps, STM_THEME_VERSION, 'all' );

		if ( ! empty( $inline_styles ) ) {
			wp_add_inline_style( $handle, $inline_styles );
		}
	}
}

function stm_module_scripts( $handle, $style = 'style_1', $deps = array( 'jquery' ), $folder = 'js', $unique_handle = false ) {
	$path = get_template_directory_uri() . '/assets/' . $folder . '/vc_modules/' . $handle . '/' . $style . '.js';
	if ( $unique_handle ) {
		$handle .= "_{$style}";
	}
	wp_enqueue_script( 'stm-' . $handle, $path, $deps, STM_THEME_VERSION, 'all' );
}

if ( ! function_exists( 'stm_pa' ) ) {
	function stm_pa( $arr ) {
		echo '<pre>';
		print_r( $arr ); //phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
		echo '</pre>';
	}
}

function stm_create_unique_id( $atts ) {
	return 'module__' . md5( serialize( $atts ) ); //phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
}

function stm_mime_types( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';

	return $mimes;
}

add_filter( 'upload_mimes', 'stm_mime_types' );

function stm_autocomplete_terms( $taxonomy = '', $include_all = true, $extended_label = false ) {
	$r = array();
	if ( is_admin() ) {
		$args = array(
			'hide_empty' => false,
		);
		if ( ! empty( $taxonomy ) ) {
			$args['taxonomy'] = $taxonomy;
		}
		$terms = get_terms( $args );

		if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$name = ! $extended_label ? $term->name . '(Taxonomy: ' . $term->taxonomy . ')' : $term->name . ' - ' . $term->slug;
				$r[]  = array(
					'label' => $name,
					'value' => $term->term_id,
				);
			}
		}
	}

	return apply_filters( 'stm_autocomplete_terms', $r );
}

function stm_server_variable_safe() {
	return ${'_SERVER'};
}

function stm_get_VC_attachment_img_safe( $attachment_id, $size_1, $size_2 = 'large', $url = false, $retina = true ) {
	if ( function_exists( 'stm_get_VC_img' ) && ! empty( $size_1 ) ) {
		$image = stm_get_VC_img( $attachment_id, $size_1, $url );
	} else {
		if ( $url ) {
			$image = stm_get_image_url( $attachment_id, $size_2 );
		} else {
			$image = get_the_post_thumbnail( $attachment_id, $size_2 );
		}
	}

	if ( false === $retina && strpos( $image, 'srcset' ) !== false ) {
		$image = str_replace( 'srcset', 'data-retina', $image );
	}

	return $image;
}


function stm_check_plugin_active_by_path( $slug ) {
	return in_array( $slug, (array) get_option( 'active_plugins', array() ), true ) || is_plugin_active_for_network( $slug );
}

function stm_get_VC_img( $img_id, $img_size, $url = false ) {
	$image = '';

	$img_size = ( ! empty( $img_size ) ) ? $img_size : '272x161';

	if ( function_exists( 'wpb_getImageBySize' ) ) {

		if ( ! empty( $img_id ) && ! empty( $img_size ) ) {
			$img = wpb_getImageBySize(
				array(
					'attach_id'  => $img_id,
					'thumb_size' => $img_size,
				)
			);

			if ( ! empty( $img['thumbnail'] ) ) {
				$image = $img['thumbnail'];

				if ( $url ) {
					$datas = array();
					preg_match( '/src="([^"]*)"/i', $image, $datas );
					if ( ! empty( $datas[1] ) ) {
						$image = $datas[1];
					} else {
						$image = '';
					}
				}
			}
		}
	} elseif ( class_exists( 'Masterstudy_Elementor_Widgets' ) && ! function_exists( 'wpb_getImageBySize' ) ) {

		if ( $url ) {
			$image = Masterstudy_Elementor_Widgets::get_image_url( $img_id, $img_size );
			$image = ( is_array( $image ) ) ? $image[0] : $image;
		} else {
			$image = Masterstudy_Elementor_Widgets::get_cropped_image( $img_id, $img_size );
		}
	} else {

		$image = wp_get_attachment_image( $img_id, $img_size );

	}

	return apply_filters( 'stm_get_vc_img', $image );
}

add_filter( 'vc_wpb_getimagesize', 'stm_vc_wpb_getimagesize', 100, 3 );

function stm_vc_wpb_getimagesize( $attachment, $id, $params ) {
	/*Already cropped*/
	if ( ! empty( $params['retined'] ) && $params['retined'] ) {
		return $attachment;
	}
	/*Empty thumbnail*/
	if ( empty( $attachment['thumbnail'] ) || empty( $params['thumb_size'] ) ) {
		return $attachment;
	}

	/*Get size as array width - height*/
	$img_size    = $params['thumb_size'];
	$retina_size = explode( 'x', $img_size );

	/*If size is in wrong format*/
	if ( ! is_array( $retina_size ) || 2 !== count( $retina_size ) || ! ctype_digit( $retina_size[0] ) ) {
		return $attachment;
	}
	$retina_width  = $retina_size[0] * 2;
	$retina_height = $retina_size[1] * 2;

	$image_matadata = wp_get_attachment_metadata( $id );
	if ( empty( $image_matadata ) ) {
		return $attachment;
	}
	$original_image_width  = $image_matadata['width'];
	$original_image_height = $image_matadata['height'];

	$retina_size_available = $original_image_width > $retina_width && $original_image_height > $retina_height;

	$retina_size = $retina_width . 'x' . $retina_height;

	$retina_img = wpb_getImageBySize(
		array(
			'attach_id'  => $id,
			'thumb_size' => $retina_size,
			'retined'    => true,
		)
	);

	if ( ! empty( $retina_img['thumbnail'] ) && $retina_size_available ) {
		$retina = explode( ' ', $retina_img['thumbnail'] );
		$retina = ( is_array( $retina ) && ! empty( $retina[2] ) ) ? str_replace( 'src', 'srcset', $retina[2] ) : '';
	}

	if ( ! empty( $retina ) && $retina_size_available ) {
		$retina                  = substr( $retina, 0, - 1 ) . ' 2x"';
		$attachment['thumbnail'] = str_replace( '<img', '<img ' . $retina, $attachment['thumbnail'] );
	}

	return $attachment;
}

function stm_echo_safe_output( $var ) {
	return $var;
}

function stm_get_image_url( $id, $size = 'full' ) {
	$url = '';
	if ( ! empty( $id ) ) {
		$image = wp_get_attachment_image_src( $id, $size, false );
		if ( ! empty( $image[0] ) ) {
			$url = $image[0];
		}
	}

	return $url;
}

function stm_vc_create_button( $link ) {
	$link = vc_build_link( $link );

	if ( ! empty( $link ) && ! empty( $link['title'] ) && ! empty( $link['url'] ) ) {
		?>
		<a href="<?php echo esc_url( $link['url'] ); ?>" class="btn btn-default">
			<?php echo esc_attr( $link['title'] ); ?>
		</a>
		<?php
	}

}

function stm_load_vc_element( $__template, $__vars = array(), $__template_name = '', $custom_path = '' ) {
	extract( $__vars );
	$element = stm_locate_vc_element( $__template, $custom_path, $__template_name );
	if ( ! file_exists( $element ) && strpos( $__template_name, 'style_' ) !== false ) {
		$element = str_replace( $__template_name, 'style_1', $element );
	}
	if ( file_exists( $element ) ) {
		include $element;
	} else {
		echo esc_html__( 'Element not found', 'masterstudy' );
	}
}

function stm_locate_vc_element( $templates, $custom_path, $template_name = '' ) {
	$located = false;

	foreach ( (array) $templates as $template ) {

		$folder = $template;

		if ( ! empty( $template_name ) ) {
			$template = $template_name;
		}

		if ( substr( $template, - 4 ) !== '.php' ) {
			$template .= '.php';
		}

		if ( empty( $custom_path ) ) {
			$located = locate_template( 'partials/vc_parts/' . $folder . '/' . $template );
			if ( ! $located ) {
				$located = get_template_directory() . '/partials/vc_parts/' . $folder . '/' . $template;
			}
		} else {
			$located = locate_template( $custom_path );
			if ( ! $located ) {
				$located = get_template_directory() . '/' . $custom_path . '.php';
			}
		}

		if ( file_exists( $template_name ) ) {
			break;
		}
	}

	return apply_filters( 'stm_locate_vc_element', $located, $templates );
}

add_action( 'pmpro_format_price', 'stm_lms_pmpro_format_price', 100, 4 );

function stm_lms_pmpro_format_price( $formatted, $price, $pmpro_currency, $pmpro_currency_symbol ) {
	return str_replace( $pmpro_currency_symbol, "<sup>{$pmpro_currency_symbol}</sup>", $formatted );
}

function stm_lms_generate_uniq_id( $atts ) {
	$atts = ( 'object' === gettype( $atts ) ) ? json_decode( wp_json_encode( $atts ), true ) : $atts;

	return 'a' . md5( implode( $atts ) );
}

function stm_new_fa_icons() {
	return array(
		0    => array( 'fab fa-500px' => '500px' ),
		1    => array( 'fab fa-accessible-icon' => 'Accessible Icon' ),
		2    => array( 'fab fa-accusoft' => 'Accusoft' ),
		3    => array( 'fa fa-address-book' => 'Address Book' ),
		4    => array( 'far fa-fa fa-address-book' => 'Address Book' ),
		5    => array( 'fa fa-address-card' => 'Address Card' ),
		6    => array( 'far fa-fa fa-address-card' => 'Address Card' ),
		7    => array( 'fa fa-adjust' => 'adjust' ),
		8    => array( 'fab fa-adn' => 'App.net' ),
		9    => array( 'fab fa-adversal' => 'Adversal' ),
		10   => array( 'fab fa-affiliatetheme' => 'affiliatetheme' ),
		11   => array( 'fa fa-air-freshener' => 'Air Freshener' ),
		12   => array( 'fab fa-algolia' => 'Algolia' ),
		13   => array( 'fa fa-align-center' => 'align-center' ),
		14   => array( 'fa fa-align-justify' => 'align-justify' ),
		15   => array( 'fa fa-align-left' => 'align-left' ),
		16   => array( 'fa fa-align-right' => 'align-right' ),
		17   => array( 'fa fa-allergies' => 'Allergies' ),
		18   => array( 'fab fa-amazon' => 'Amazon' ),
		19   => array( 'fab fa-amazon-pay' => 'Amazon Pay' ),
		20   => array( 'fa fa-ambulance' => 'ambulance' ),
		21   => array( 'fa fa-american-sign-language-interpreting' => 'American Sign Language Interpreting' ),
		22   => array( 'fab fa-amilia' => 'Amilia' ),
		23   => array( 'fa fa-anchor' => 'Anchor' ),
		24   => array( 'fab fa-android' => 'Android' ),
		25   => array( 'fab fa-angellist' => 'AngelList' ),
		26   => array( 'fa fa-angle-double-down' => 'Angle Double Down' ),
		27   => array( 'fa fa-angle-double-left' => 'Angle Double Left' ),
		28   => array( 'fa fa-angle-double-right' => 'Angle Double Right' ),
		29   => array( 'fa fa-angle-double-up' => 'Angle Double Up' ),
		30   => array( 'fa fa-angle-down' => 'angle-down' ),
		31   => array( 'fa fa-angle-left' => 'angle-left' ),
		32   => array( 'fa fa-angle-right' => 'angle-right' ),
		33   => array( 'fa fa-angle-up' => 'angle-up' ),
		34   => array( 'fa fa-angry' => 'Angry Face' ),
		35   => array( 'far fa-fa fa-angry' => 'Angry Face' ),
		36   => array( 'fab fa-angrycreative' => 'Angry Creative' ),
		37   => array( 'fab fa-angular' => 'Angular' ),
		38   => array( 'fab fa-app-store' => 'App Store' ),
		39   => array( 'fab fa-app-store-ios' => 'iOS App Store' ),
		40   => array( 'fab fa-apper' => 'Apper Systems AB' ),
		41   => array( 'fab fa-apple' => 'Apple' ),
		42   => array( 'fa fa-apple-alt' => 'Fruit Apple' ),
		43   => array( 'fab fa-apple-pay' => 'Apple Pay' ),
		44   => array( 'fa fa-archive' => 'Archive' ),
		45   => array( 'fa fa-archway' => 'Archway' ),
		46   => array( 'fa fa-arrow-alt-circle-down' => 'Alternate Arrow Circle Down' ),
		47   => array( 'far fa-fa fa-arrow-alt-circle-down' => 'Alternate Arrow Circle Down' ),
		48   => array( 'fa fa-arrow-alt-circle-left' => 'Alternate Arrow Circle Left' ),
		49   => array( 'far fa-fa fa-arrow-alt-circle-left' => 'Alternate Arrow Circle Left' ),
		50   => array( 'fa fa-arrow-alt-circle-right' => 'Alternate Arrow Circle Right' ),
		51   => array( 'far fa-fa fa-arrow-alt-circle-right' => 'Alternate Arrow Circle Right' ),
		52   => array( 'fa fa-arrow-alt-circle-up' => 'Alternate Arrow Circle Up' ),
		53   => array( 'far fa-fa fa-arrow-alt-circle-up' => 'Alternate Arrow Circle Up' ),
		54   => array( 'fa fa-arrow-circle-down' => 'Arrow Circle Down' ),
		55   => array( 'fa fa-arrow-circle-left' => 'Arrow Circle Left' ),
		56   => array( 'fa fa-arrow-circle-right' => 'Arrow Circle Right' ),
		57   => array( 'fa fa-arrow-circle-up' => 'Arrow Circle Up' ),
		58   => array( 'fa fa-arrow-down' => 'arrow-down' ),
		59   => array( 'fa fa-arrow-left' => 'arrow-left' ),
		60   => array( 'fa fa-arrow-right' => 'arrow-right' ),
		61   => array( 'fa fa-arrow-up' => 'arrow-up' ),
		62   => array( 'fa fa-arrows-alt' => 'Alternate Arrows' ),
		63   => array( 'fa fa-arrows-alt-h' => 'Alternate Arrows Horizontal' ),
		64   => array( 'fa fa-arrows-alt-v' => 'Alternate Arrows Vertical' ),
		65   => array( 'fa fa-assistive-listening-systems' => 'Assistive Listening Systems' ),
		66   => array( 'fa fa-asterisk' => 'asterisk' ),
		67   => array( 'fab fa-asymmetrik' => 'Asymmetrik, Ltd.' ),
		68   => array( 'fa fa-at' => 'At' ),
		69   => array( 'fa fa-atlas' => 'Atlas' ),
		70   => array( 'fa fa-atom' => 'Atom' ),
		71   => array( 'fab fa-audible' => 'Audible' ),
		72   => array( 'fa fa-audio-description' => 'Audio Description' ),
		73   => array( 'fab fa-autoprefixer' => 'Autoprefixer' ),
		74   => array( 'fab fa-avianex' => 'avianex' ),
		75   => array( 'fab fa-aviato' => 'Aviato' ),
		76   => array( 'fa fa-award' => 'Award' ),
		77   => array( 'fab fa-aws' => 'Amazon Web Services (AWS)' ),
		78   => array( 'fa fa-backspace' => 'Backspace' ),
		79   => array( 'fa fa-backward' => 'backward' ),
		80   => array( 'fa fa-balance-scale' => 'Balance Scale' ),
		81   => array( 'fa fa-ban' => 'ban' ),
		82   => array( 'fa fa-band-aid' => 'Band-Aid' ),
		83   => array( 'fab fa-bandcamp' => 'Bandcamp' ),
		84   => array( 'fa fa-barcode' => 'barcode' ),
		85   => array( 'fa fa-bars' => 'Bars' ),
		86   => array( 'fa fa-baseball-ball' => 'Baseball Ball' ),
		87   => array( 'fa fa-basketball-ball' => 'Basketball Ball' ),
		88   => array( 'fa fa-bath' => 'Bath' ),
		89   => array( 'fa fa-battery-empty' => 'Battery Empty' ),
		90   => array( 'fa fa-battery-full' => 'Battery Full' ),
		91   => array( 'fa fa-battery-half' => 'Battery 1/2 Full' ),
		92   => array( 'fa fa-battery-quarter' => 'Battery 1/4 Full' ),
		93   => array( 'fa fa-battery-three-quarters' => 'Battery 3/4 Full' ),
		94   => array( 'fa fa-bed' => 'Bed' ),
		95   => array( 'fa fa-beer' => 'beer' ),
		96   => array( 'fab fa-behance' => 'Behance' ),
		97   => array( 'fab fa-behance-square' => 'Behance Square' ),
		98   => array( 'fa fa-bell' => 'bell' ),
		99   => array( 'far fa-fa fa-bell' => 'bell' ),
		100  => array( 'fa fa-bell-slash' => 'Bell Slash' ),
		101  => array( 'far fa-fa fa-bell-slash' => 'Bell Slash' ),
		102  => array( 'fa fa-bezier-curve' => 'Bezier Curve' ),
		103  => array( 'fa fa-bicycle' => 'Bicycle' ),
		104  => array( 'fab fa-bimobject' => 'BIMobject' ),
		105  => array( 'fa fa-binoculars' => 'Binoculars' ),
		106  => array( 'fa fa-birthday-cake' => 'Birthday Cake' ),
		107  => array( 'fab fa-bitbucket' => 'Bitbucket' ),
		108  => array( 'fab fa-bitcoin' => 'Bitcoin' ),
		109  => array( 'fab fa-bity' => 'Bity' ),
		110  => array( 'fab fa-black-tie' => 'Font Awesome Black Tie' ),
		111  => array( 'fab fa-blackberry' => 'BlackBerry' ),
		112  => array( 'fa fa-blender' => 'Blender' ),
		113  => array( 'fa fa-blind' => 'Blind' ),
		114  => array( 'fab fa-blogger' => 'Blogger' ),
		115  => array( 'fab fa-blogger-b' => 'Blogger B' ),
		116  => array( 'fab fa-bluetooth' => 'Bluetooth' ),
		117  => array( 'fab fa-bluetooth-b' => 'Bluetooth' ),
		118  => array( 'fa fa-bold' => 'bold' ),
		119  => array( 'fa fa-bolt' => 'Lightning Bolt' ),
		120  => array( 'fa fa-bomb' => 'Bomb' ),
		121  => array( 'fa fa-bone' => 'Bone' ),
		122  => array( 'fa fa-bong' => 'Bong' ),
		123  => array( 'fa fa-book' => 'book' ),
		124  => array( 'fa fa-book-open' => 'Book Open' ),
		125  => array( 'fa fa-book-reader' => 'Book Reader' ),
		126  => array( 'fa fa-bookmark' => 'bookmark' ),
		127  => array( 'far fa-fa fa-bookmark' => 'bookmark' ),
		128  => array( 'fa fa-bowling-ball' => 'Bowling Ball' ),
		129  => array( 'fa fa-box' => 'Box' ),
		130  => array( 'fa fa-box-open' => 'Box Open' ),
		131  => array( 'fa fa-boxes' => 'Boxes' ),
		132  => array( 'fa fa-braille' => 'Braille' ),
		133  => array( 'fa fa-brain' => 'Brain' ),
		134  => array( 'fa fa-briefcase' => 'Briefcase' ),
		135  => array( 'fa fa-briefcase-medical' => 'Medical Briefcase' ),
		136  => array( 'fa fa-broadcast-tower' => 'Broadcast Tower' ),
		137  => array( 'fa fa-broom' => 'Broom' ),
		138  => array( 'fa fa-brush' => 'Brush' ),
		139  => array( 'fab fa-btc' => 'BTC' ),
		140  => array( 'fa fa-bug' => 'Bug' ),
		141  => array( 'fa fa-building' => 'Building' ),
		142  => array( 'far fa-fa fa-building' => 'Building' ),
		143  => array( 'fa fa-bullhorn' => 'bullhorn' ),
		144  => array( 'fa fa-bullseye' => 'Bullseye' ),
		145  => array( 'fa fa-burn' => 'Burn' ),
		146  => array( 'fab fa-buromobelexperte' => 'Brombel-Experte GmbH & Co. KG.' ),
		147  => array( 'fa fa-bus' => 'Bus' ),
		148  => array( 'fa fa-bus-alt' => 'Bus Alt' ),
		149  => array( 'fab fa-buysellads' => 'BuySellAds' ),
		150  => array( 'fa fa-calculator' => 'Calculator' ),
		151  => array( 'fa fa-calendar' => 'Calendar' ),
		152  => array( 'far fa-fa fa-calendar' => 'Calendar' ),
		153  => array( 'fa fa-calendar-alt' => 'Alternate Calendar' ),
		154  => array( 'far fa-fa fa-calendar-alt' => 'Alternate Calendar' ),
		155  => array( 'fa fa-calendar-check' => 'Calendar Check' ),
		156  => array( 'far fa-fa fa-calendar-check' => 'Calendar Check' ),
		157  => array( 'fa fa-calendar-minus' => 'Calendar Minus' ),
		158  => array( 'far fa-fa fa-calendar-minus' => 'Calendar Minus' ),
		159  => array( 'fa fa-calendar-plus' => 'Calendar Plus' ),
		160  => array( 'far fa-fa fa-calendar-plus' => 'Calendar Plus' ),
		161  => array( 'fa fa-calendar-times' => 'Calendar Times' ),
		162  => array( 'far fa-fa fa-calendar-times' => 'Calendar Times' ),
		163  => array( 'fa fa-camera' => 'camera' ),
		164  => array( 'fa fa-camera-retro' => 'Retro Camera' ),
		165  => array( 'fa fa-cannabis' => 'Cannabis' ),
		166  => array( 'fa fa-capsules' => 'Capsules' ),
		167  => array( 'fa fa-car' => 'Car' ),
		168  => array( 'fa fa-car-alt' => 'Car Alt' ),
		169  => array( 'fa fa-car-battery' => 'Car Battery' ),
		170  => array( 'fa fa-car-crash' => 'Car Crash' ),
		171  => array( 'fa fa-car-side' => 'Car Side' ),
		172  => array( 'fa fa-caret-down' => 'Caret Down' ),
		173  => array( 'fa fa-caret-left' => 'Caret Left' ),
		174  => array( 'fa fa-caret-right' => 'Caret Right' ),
		175  => array( 'fa fa-caret-square-down' => 'Caret Square Down' ),
		176  => array( 'far fa-fa fa-caret-square-down' => 'Caret Square Down' ),
		177  => array( 'fa fa-caret-square-left' => 'Caret Square Left' ),
		178  => array( 'far fa-fa fa-caret-square-left' => 'Caret Square Left' ),
		179  => array( 'fa fa-caret-square-right' => 'Caret Square Right' ),
		180  => array( 'far fa-fa fa-caret-square-right' => 'Caret Square Right' ),
		181  => array( 'fa fa-caret-square-up' => 'Caret Square Up' ),
		182  => array( 'far fa-fa fa-caret-square-up' => 'Caret Square Up' ),
		183  => array( 'fa fa-caret-up' => 'Caret Up' ),
		184  => array( 'fa fa-cart-arrow-down' => 'Shopping Cart Arrow Down' ),
		185  => array( 'fa fa-cart-plus' => 'Add to Shopping Cart' ),
		186  => array( 'fab fa-cc-amazon-pay' => 'Amazon Pay Credit Card' ),
		187  => array( 'fab fa-cc-amex' => 'American Express Credit Card' ),
		188  => array( 'fab fa-cc-apple-pay' => 'Apple Pay Credit Card' ),
		189  => array( 'fab fa-cc-diners-club' => 'Diner\'s Club Credit Card' ),
		190  => array( 'fab fa-cc-discover' => 'Discover Credit Card' ),
		191  => array( 'fab fa-cc-jcb' => 'JCB Credit Card' ),
		192  => array( 'fab fa-cc-mastercard' => 'MasterCard Credit Card' ),
		193  => array( 'fab fa-cc-paypal' => 'Paypal Credit Card' ),
		194  => array( 'fab fa-cc-stripe' => 'Stripe Credit Card' ),
		195  => array( 'fab fa-cc-visa' => 'Visa Credit Card' ),
		196  => array( 'fab fa-centercode' => 'Centercode' ),
		197  => array( 'fa fa-certificate' => 'certificate' ),
		198  => array( 'fa fa-chalkboard' => 'Chalkboard' ),
		199  => array( 'fa fa-chalkboard-teacher' => 'Chalkboard Teacher' ),
		200  => array( 'fa fa-charging-station' => 'Charging Station' ),
		201  => array( 'fa fa-chart-area' => 'Area Chart' ),
		202  => array( 'fa fa-chart-bar' => 'Bar Chart' ),
		203  => array( 'far fa-fa fa-chart-bar' => 'Bar Chart' ),
		204  => array( 'fa fa-chart-line' => 'Line Chart' ),
		205  => array( 'fa fa-chart-pie' => 'Pie Chart' ),
		206  => array( 'fa fa-check' => 'Check' ),
		207  => array( 'fa fa-check-circle' => 'Check Circle' ),
		208  => array( 'far fa-fa fa-check-circle' => 'Check Circle' ),
		209  => array( 'fa fa-check-double' => 'Check Double' ),
		210  => array( 'fa fa-check-square' => 'Check Square' ),
		211  => array( 'far fa-fa fa-check-square' => 'Check Square' ),
		212  => array( 'fa fa-chess' => 'Chess' ),
		213  => array( 'fa fa-chess-bishop' => 'Chess Bishop' ),
		214  => array( 'fa fa-chess-board' => 'Chess Board' ),
		215  => array( 'fa fa-chess-king' => 'Chess King' ),
		216  => array( 'fa fa-chess-knight' => 'Chess Knight' ),
		217  => array( 'fa fa-chess-pawn' => 'Chess Pawn' ),
		218  => array( 'fa fa-chess-queen' => 'Chess Queen' ),
		219  => array( 'fa fa-chess-rook' => 'Chess Rook' ),
		220  => array( 'fa fa-chevron-circle-down' => 'Chevron Circle Down' ),
		221  => array( 'fa fa-chevron-circle-left' => 'Chevron Circle Left' ),
		222  => array( 'fa fa-chevron-circle-right' => 'Chevron Circle Right' ),
		223  => array( 'fa fa-chevron-circle-up' => 'Chevron Circle Up' ),
		224  => array( 'fa fa-chevron-down' => 'chevron-down' ),
		225  => array( 'fa fa-chevron-left' => 'chevron-left' ),
		226  => array( 'fa fa-chevron-right' => 'chevron-right' ),
		227  => array( 'fa fa-chevron-up' => 'chevron-up' ),
		228  => array( 'fa fa-child' => 'Child' ),
		229  => array( 'fab fa-chrome' => 'Chrome' ),
		230  => array( 'fa fa-church' => 'Church' ),
		231  => array( 'fa fa-circle' => 'Circle' ),
		232  => array( 'far fa-fa fa-circle' => 'Circle' ),
		233  => array( 'fa fa-circle-notch' => 'Circle Notched' ),
		234  => array( 'fa fa-clipboard' => 'Clipboard' ),
		235  => array( 'far fa-fa fa-clipboard' => 'Clipboard' ),
		236  => array( 'fa fa-clipboard-check' => 'Clipboard Check' ),
		237  => array( 'fa fa-clipboard-list' => 'Clipboard List' ),
		238  => array( 'fa fa-clock' => 'Clock' ),
		239  => array( 'far fa-fa fa-clock' => 'Clock' ),
		240  => array( 'fa fa-clone' => 'Clone' ),
		241  => array( 'far fa-fa fa-clone' => 'Clone' ),
		242  => array( 'fa fa-closed-captioning' => 'Closed Captioning' ),
		243  => array( 'far fa-fa fa-closed-captioning' => 'Closed Captioning' ),
		244  => array( 'fa fa-cloud' => 'Cloud' ),
		245  => array( 'fa fa-cloud-download-alt' => 'Alternate Cloud Download' ),
		246  => array( 'fa fa-cloud-upload-alt' => 'Alternate Cloud Upload' ),
		247  => array( 'fab fa-cloudscale' => 'cloudscale.ch' ),
		248  => array( 'fab fa-cloudsmith' => 'Cloudsmith' ),
		249  => array( 'fab fa-cloudversify' => 'cloudversify' ),
		250  => array( 'fa fa-cocktail' => 'Cocktail' ),
		251  => array( 'fa fa-code' => 'Code' ),
		252  => array( 'fa fa-code-branch' => 'Code Branch' ),
		253  => array( 'fab fa-codepen' => 'Codepen' ),
		254  => array( 'fab fa-codiepie' => 'Codie Pie' ),
		255  => array( 'fa fa-coffee' => 'Coffee' ),
		256  => array( 'fa fa-cog' => 'cog' ),
		257  => array( 'fa fa-cogs' => 'cogs' ),
		258  => array( 'fa fa-coins' => 'Coins' ),
		259  => array( 'fa fa-columns' => 'Columns' ),
		260  => array( 'fa fa-comment' => 'comment' ),
		261  => array( 'far fa-fa fa-comment' => 'comment' ),
		262  => array( 'fa fa-comment-alt' => 'Alternate Comment' ),
		263  => array( 'far fa-fa fa-comment-alt' => 'Alternate Comment' ),
		264  => array( 'fa fa-comment-dots' => 'Comment Dots' ),
		265  => array( 'far fa-fa fa-comment-dots' => 'Comment Dots' ),
		266  => array( 'fa fa-comment-slash' => 'Comment Slash' ),
		267  => array( 'fa fa-comments' => 'comments' ),
		268  => array( 'far fa-fa fa-comments' => 'comments' ),
		269  => array( 'fa fa-compact-disc' => 'Compact Disc' ),
		270  => array( 'fa fa-compass' => 'Compass' ),
		271  => array( 'far fa-fa fa-compass' => 'Compass' ),
		272  => array( 'fa fa-compress' => 'Compress' ),
		273  => array( 'fa fa-concierge-bell' => 'Concierge Bell' ),
		274  => array( 'fab fa-connectdevelop' => 'Connect Develop' ),
		275  => array( 'fab fa-contao' => 'Contao' ),
		276  => array( 'fa fa-cookie' => 'Cookie' ),
		277  => array( 'fa fa-cookie-bite' => 'Cookie Bite' ),
		278  => array( 'fa fa-copy' => 'Copy' ),
		279  => array( 'far fa-fa fa-copy' => 'Copy' ),
		280  => array( 'fa fa-copyright' => 'Copyright' ),
		281  => array( 'far fa-fa fa-copyright' => 'Copyright' ),
		282  => array( 'fa fa-couch' => 'Couch' ),
		283  => array( 'fab fa-cpanel' => 'cPanel' ),
		284  => array( 'fab fa-creative-commons' => 'Creative Commons' ),
		285  => array( 'fab fa-creative-commons-by' => 'Creative Commons Attribution' ),
		286  => array( 'fab fa-creative-commons-nc' => 'Creative Commons Noncommercial' ),
		287  => array( 'fab fa-creative-commons-nc-eu' => 'Creative Commons Noncommercial (Euro Sign)' ),
		288  => array( 'fab fa-creative-commons-nc-jp' => 'Creative Commons Noncommercial (Yen Sign)' ),
		289  => array( 'fab fa-creative-commons-nd' => 'Creative Commons No Derivative Works' ),
		290  => array( 'fab fa-creative-commons-pd' => 'Creative Commons Public Domain' ),
		291  => array( 'fab fa-creative-commons-pd-alt' => 'Creative Commons Public Domain Alternate' ),
		292  => array( 'fab fa-creative-commons-remix' => 'Creative Commons Remix' ),
		293  => array( 'fab fa-creative-commons-sa' => 'Creative Commons Share Alike' ),
		294  => array( 'fab fa-creative-commons-sampling' => 'Creative Commons Sampling' ),
		295  => array( 'fab fa-creative-commons-sampling-plus' => 'Creative Commons Sampling +' ),
		296  => array( 'fab fa-creative-commons-share' => 'Creative Commons Share' ),
		297  => array( 'fa fa-credit-card' => 'Credit Card' ),
		298  => array( 'far fa-fa fa-credit-card' => 'Credit Card' ),
		299  => array( 'fa fa-crop' => 'crop' ),
		300  => array( 'fa fa-crop-alt' => 'Alternate Crop' ),
		301  => array( 'fa fa-crosshairs' => 'Crosshairs' ),
		302  => array( 'fa fa-crow' => 'Crow' ),
		303  => array( 'fa fa-crown' => 'Crown' ),
		304  => array( 'fab fa-css3' => 'CSS 3 Logo' ),
		305  => array( 'fab fa-css3-alt' => 'Alternate CSS3 Logo' ),
		306  => array( 'fa fa-cube' => 'Cube' ),
		307  => array( 'fa fa-cubes' => 'Cubes' ),
		308  => array( 'fa fa-cut' => 'Cut' ),
		309  => array( 'fab fa-cuttlefish' => 'Cuttlefish' ),
		310  => array( 'fab fa-d-and-d' => 'Dungeons & Dragons' ),
		311  => array( 'fab fa-dashcube' => 'DashCube' ),
		312  => array( 'fa fa-database' => 'Database' ),
		313  => array( 'fa fa-deaf' => 'Deaf' ),
		314  => array( 'fab fa-delicious' => 'Delicious Logo' ),
		315  => array( 'fab fa-deploydog' => 'deploy.dog' ),
		316  => array( 'fab fa-deskpro' => 'Deskpro' ),
		317  => array( 'fa fa-desktop' => 'Desktop' ),
		318  => array( 'fab fa-deviantart' => 'deviantART' ),
		319  => array( 'fa fa-diagnoses' => 'Diagnoses' ),
		320  => array( 'fa fa-dice' => 'Dice' ),
		321  => array( 'fa fa-dice-five' => 'Dice Five' ),
		322  => array( 'fa fa-dice-four' => 'Dice Four' ),
		323  => array( 'fa fa-dice-one' => 'Dice One' ),
		324  => array( 'fa fa-dice-six' => 'Dice Six' ),
		325  => array( 'fa fa-dice-three' => 'Dice Three' ),
		326  => array( 'fa fa-dice-two' => 'Dice Two' ),
		327  => array( 'fab fa-digg' => 'Digg Logo' ),
		328  => array( 'fab fa-digital-ocean' => 'Digital Ocean' ),
		329  => array( 'fa fa-digital-tachograph' => 'Digital Tachograph' ),
		330  => array( 'fa fa-directions' => 'Directions' ),
		331  => array( 'fab fa-discord' => 'Discord' ),
		332  => array( 'fab fa-discourse' => 'Discourse' ),
		333  => array( 'fa fa-divide' => 'Divide' ),
		334  => array( 'fa fa-dizzy' => 'Dizzy Face' ),
		335  => array( 'far fa-fa fa-dizzy' => 'Dizzy Face' ),
		336  => array( 'fa fa-dna' => 'DNA' ),
		337  => array( 'fab fa-dochub' => 'DocHub' ),
		338  => array( 'fab fa-docker' => 'Docker' ),
		339  => array( 'fa fa-dollar-sign' => 'Dollar Sign' ),
		340  => array( 'fa fa-dolly' => 'Dolly' ),
		341  => array( 'fa fa-dolly-flatbed' => 'Dolly Flatbed' ),
		342  => array( 'fa fa-donate' => 'Donate' ),
		343  => array( 'fa fa-door-closed' => 'Door Closed' ),
		344  => array( 'fa fa-door-open' => 'Door Open' ),
		345  => array( 'fa fa-dot-circle' => 'Dot Circle' ),
		346  => array( 'far fa-fa fa-dot-circle' => 'Dot Circle' ),
		347  => array( 'fa fa-dove' => 'Dove' ),
		348  => array( 'fa fa-download' => 'Download' ),
		349  => array( 'fab fa-draft2digital' => 'Draft2digital' ),
		350  => array( 'fa fa-drafting-compass' => 'Drafting Compass' ),
		351  => array( 'fa fa-draw-polygon' => 'Draw Polygon' ),
		352  => array( 'fab fa-dribbble' => 'Dribbble' ),
		353  => array( 'fab fa-dribbble-square' => 'Dribbble Square' ),
		354  => array( 'fab fa-dropbox' => 'Dropbox' ),
		355  => array( 'fa fa-drum' => 'Drum' ),
		356  => array( 'fa fa-drum-steelpan' => 'Drum Steelpan' ),
		357  => array( 'fab fa-drupal' => 'Drupal Logo' ),
		358  => array( 'fa fa-dumbbell' => 'Dumbbell' ),
		359  => array( 'fab fa-dyalog' => 'Dyalog' ),
		360  => array( 'fab fa-earlybirds' => 'Earlybirds' ),
		361  => array( 'fab fa-ebay' => 'eBay' ),
		362  => array( 'fab fa-edge' => 'Edge Browser' ),
		363  => array( 'fa fa-edit' => 'Edit' ),
		364  => array( 'far fa-fa fa-edit' => 'Edit' ),
		365  => array( 'fa fa-eject' => 'eject' ),
		366  => array( 'fab fa-elementor' => 'Elementor' ),
		367  => array( 'fa fa-ellipsis-h' => 'Horizontal Ellipsis' ),
		368  => array( 'fa fa-ellipsis-v' => 'Vertical Ellipsis' ),
		369  => array( 'fab fa-ello' => 'Ello' ),
		370  => array( 'fab fa-ember' => 'Ember' ),
		371  => array( 'fab fa-empire' => 'Galactic Empire' ),
		372  => array( 'fa fa-envelope' => 'Envelope' ),
		373  => array( 'far fa-fa fa-envelope' => 'Envelope' ),
		374  => array( 'fa fa-envelope-open' => 'Envelope Open' ),
		375  => array( 'far fa-fa fa-envelope-open' => 'Envelope Open' ),
		376  => array( 'fa fa-envelope-square' => 'Envelope Square' ),
		377  => array( 'fab fa-envira' => 'Envira Gallery' ),
		378  => array( 'fa fa-equals' => 'Equals' ),
		379  => array( 'fa fa-eraser' => 'eraser' ),
		380  => array( 'fab fa-erlang' => 'Erlang' ),
		381  => array( 'fab fa-ethereum' => 'Ethereum' ),
		382  => array( 'fab fa-etsy' => 'Etsy' ),
		383  => array( 'fa fa-euro-sign' => 'Euro Sign' ),
		384  => array( 'fa fa-exchange-alt' => 'Alternate Exchange' ),
		385  => array( 'fa fa-exclamation' => 'exclamation' ),
		386  => array( 'fa fa-exclamation-circle' => 'Exclamation Circle' ),
		387  => array( 'fa fa-exclamation-triangle' => 'Exclamation Triangle' ),
		388  => array( 'fa fa-expand' => 'Expand' ),
		389  => array( 'fa fa-expand-arrows-alt' => 'Alternate Expand Arrows' ),
		390  => array( 'fab fa-expeditedssl' => 'ExpeditedSSL' ),
		391  => array( 'fa fa-external-link-alt' => 'Alternate External Link' ),
		392  => array( 'fa fa-external-link-square-alt' => 'Alternate External Link Square' ),
		393  => array( 'fa fa-eye' => 'Eye' ),
		394  => array( 'far fa-fa fa-eye' => 'Eye' ),
		395  => array( 'fa fa-eye-dropper' => 'Eye Dropper' ),
		396  => array( 'fa fa-eye-slash' => 'Eye Slash' ),
		397  => array( 'far fa-fa fa-eye-slash' => 'Eye Slash' ),
		398  => array( 'fab fa-facebook' => 'Facebook' ),
		399  => array( 'fab fa-facebook-f' => 'Facebook F' ),
		400  => array( 'fab fa-facebook-messenger' => 'Facebook Messenger' ),
		401  => array( 'fab fa-facebook-square' => 'Facebook Square' ),
		402  => array( 'fa fa-fast-backward' => 'fast-backward' ),
		403  => array( 'fa fa-fast-forward' => 'fast-forward' ),
		404  => array( 'fa fa-fax' => 'Fax' ),
		405  => array( 'fa fa-feather' => 'Feather' ),
		406  => array( 'fa fa-feather-alt' => 'Feather Alt' ),
		407  => array( 'fa fa-female' => 'Female' ),
		408  => array( 'fa fa-fighter-jet' => 'fighter-jet' ),
		409  => array( 'fa fa-file' => 'File' ),
		410  => array( 'far fa-fa fa-file' => 'File' ),
		411  => array( 'fa fa-file-alt' => 'Alternate File' ),
		412  => array( 'far fa-fa fa-file-alt' => 'Alternate File' ),
		413  => array( 'fa fa-file-archive' => 'Archive File' ),
		414  => array( 'far fa-fa fa-file-archive' => 'Archive File' ),
		415  => array( 'fa fa-file-audio' => 'Audio File' ),
		416  => array( 'far fa-fa fa-file-audio' => 'Audio File' ),
		417  => array( 'fa fa-file-code' => 'Code File' ),
		418  => array( 'far fa-fa fa-file-code' => 'Code File' ),
		419  => array( 'fa fa-file-contract' => 'File Contract' ),
		420  => array( 'fa fa-file-download' => 'File Download' ),
		421  => array( 'fa fa-file-excel' => 'Excel File' ),
		422  => array( 'far fa-fa fa-file-excel' => 'Excel File' ),
		423  => array( 'fa fa-file-export' => 'File Export' ),
		424  => array( 'fa fa-file-image' => 'Image File' ),
		425  => array( 'far fa-fa fa-file-image' => 'Image File' ),
		426  => array( 'fa fa-file-import' => 'File Import' ),
		427  => array( 'fa fa-file-invoice' => 'File Invoice' ),
		428  => array( 'fa fa-file-invoice-dollar' => 'File Invoice with US Dollar' ),
		429  => array( 'fa fa-file-medical' => 'Medical File' ),
		430  => array( 'fa fa-file-medical-alt' => 'Alternate Medical File' ),
		431  => array( 'fa fa-file-pdf' => 'PDF File' ),
		432  => array( 'far fa-fa fa-file-pdf' => 'PDF File' ),
		433  => array( 'fa fa-file-powerpoint' => 'Powerpoint File' ),
		434  => array( 'far fa-fa fa-file-powerpoint' => 'Powerpoint File' ),
		435  => array( 'fa fa-file-prescription' => 'File Prescription' ),
		436  => array( 'fa fa-file-signature' => 'File Signature' ),
		437  => array( 'fa fa-file-upload' => 'File Upload' ),
		438  => array( 'fa fa-file-video' => 'Video File' ),
		439  => array( 'far fa-fa fa-file-video' => 'Video File' ),
		440  => array( 'fa fa-file-word' => 'Word File' ),
		441  => array( 'far fa-fa fa-file-word' => 'Word File' ),
		442  => array( 'fa fa-fill' => 'Fill' ),
		443  => array( 'fa fa-fill-drip' => 'Fill Drip' ),
		444  => array( 'fa fa-film' => 'Film' ),
		445  => array( 'fa fa-filter' => 'Filter' ),
		446  => array( 'fa fa-fingerprint' => 'Fingerprint' ),
		447  => array( 'fa fa-fire' => 'fire' ),
		448  => array( 'fa fa-fire-extinguisher' => 'fire-extinguisher' ),
		449  => array( 'fab fa-firefox' => 'Firefox' ),
		450  => array( 'fa fa-first-aid' => 'First Aid' ),
		451  => array( 'fab fa-first-order' => 'First Order' ),
		452  => array( 'fab fa-first-order-alt' => 'Alternate First Order' ),
		453  => array( 'fab fa-firstdraft' => 'firstdraft' ),
		454  => array( 'fa fa-fish' => 'Fish' ),
		455  => array( 'fa fa-flag' => 'flag' ),
		456  => array( 'far fa-fa fa-flag' => 'flag' ),
		457  => array( 'fa fa-flag-checkered' => 'flag-checkered' ),
		458  => array( 'fa fa-flask' => 'Flask' ),
		459  => array( 'fab fa-flickr' => 'Flickr' ),
		460  => array( 'fab fa-flipboard' => 'Flipboard' ),
		461  => array( 'fa fa-flushed' => 'Flushed Face' ),
		462  => array( 'far fa-fa fa-flushed' => 'Flushed Face' ),
		463  => array( 'fab fa-fly' => 'Fly' ),
		464  => array( 'fa fa-folder' => 'Folder' ),
		465  => array( 'far fa-fa fa-folder' => 'Folder' ),
		466  => array( 'fa fa-folder-open' => 'Folder Open' ),
		467  => array( 'far fa-fa fa-folder-open' => 'Folder Open' ),
		468  => array( 'fa fa-font' => 'font' ),
		469  => array( 'fab fa-font-awesome' => 'Font Awesome' ),
		470  => array( 'fab fa-font-awesome-alt' => 'Alternate Font Awesome' ),
		471  => array( 'fab fa-font-awesome-flag' => 'Font Awesome Flag' ),
		472  => array( 'far fa-font-awesome-logo-full' => 'Font Awesome Full Logo' ),
		473  => array( 'fa fa-far fa-font-awesome-logo-full' => 'Font Awesome Full Logo' ),
		474  => array( 'fab fa-fa fa-far fa-font-awesome-logo-full' => 'Font Awesome Full Logo' ),
		475  => array( 'fab fa-fonticons' => 'Fonticons' ),
		476  => array( 'fab fa-fonticons-fi' => 'Fonticons Fi' ),
		477  => array( 'fa fa-football-ball' => 'Football Ball' ),
		478  => array( 'fab fa-fort-awesome' => 'Fort Awesome' ),
		479  => array( 'fab fa-fort-awesome-alt' => 'Alternate Fort Awesome' ),
		480  => array( 'fab fa-forumbee' => 'Forumbee' ),
		481  => array( 'fa fa-forward' => 'forward' ),
		482  => array( 'fab fa-foursquare' => 'Foursquare' ),
		483  => array( 'fab fa-free-code-camp' => 'Free Code Camp' ),
		484  => array( 'fab fa-freebsd' => 'FreeBSD' ),
		485  => array( 'fa fa-frog' => 'Frog' ),
		486  => array( 'fa fa-frown' => 'Frowning Face' ),
		487  => array( 'far fa-fa fa-frown' => 'Frowning Face' ),
		488  => array( 'fa fa-frown-open' => 'Frowning Face With Open Mouth' ),
		489  => array( 'far fa-fa fa-frown-open' => 'Frowning Face With Open Mouth' ),
		490  => array( 'fab fa-fulcrum' => 'Fulcrum' ),
		491  => array( 'fa fa-futbol' => 'Futbol' ),
		492  => array( 'far fa-fa fa-futbol' => 'Futbol' ),
		493  => array( 'fab fa-galactic-republic' => 'Galactic Republic' ),
		494  => array( 'fab fa-galactic-senate' => 'Galactic Senate' ),
		495  => array( 'fa fa-gamepad' => 'Gamepad' ),
		496  => array( 'fa fa-gas-pump' => 'Gas Pump' ),
		497  => array( 'fa fa-gavel' => 'Gavel' ),
		498  => array( 'fa fa-gem' => 'Gem' ),
		499  => array( 'far fa-fa fa-gem' => 'Gem' ),
		500  => array( 'fa fa-genderless' => 'Genderless' ),
		501  => array( 'fab fa-get-pocket' => 'Get Pocket' ),
		502  => array( 'fab fa-gg' => 'GG Currency' ),
		503  => array( 'fab fa-gg-circle' => 'GG Currency Circle' ),
		504  => array( 'fa fa-gift' => 'gift' ),
		505  => array( 'fab fa-git' => 'Git' ),
		506  => array( 'fab fa-git-square' => 'Git Square' ),
		507  => array( 'fab fa-github' => 'GitHub' ),
		508  => array( 'fab fa-github-alt' => 'Alternate GitHub' ),
		509  => array( 'fab fa-github-square' => 'GitHub Square' ),
		510  => array( 'fab fa-gitkraken' => 'GitKraken' ),
		511  => array( 'fab fa-gitlab' => 'GitLab' ),
		512  => array( 'fab fa-gitter' => 'Gitter' ),
		513  => array( 'fa fa-glass-martini' => 'Martini Glass' ),
		514  => array( 'fa fa-glass-martini-alt' => 'Glass Martini-alt' ),
		515  => array( 'fa fa-glasses' => 'Glasses' ),
		516  => array( 'fab fa-glide' => 'Glide' ),
		517  => array( 'fab fa-glide-g' => 'Glide G' ),
		518  => array( 'fa fa-globe' => 'Globe' ),
		519  => array( 'fa fa-globe-africa' => 'Globe with Africa shown' ),
		520  => array( 'fa fa-globe-americas' => 'Globe with Americas shown' ),
		521  => array( 'fa fa-globe-asia' => 'Globe with Asia shown' ),
		522  => array( 'fab fa-gofore' => 'Gofore' ),
		523  => array( 'fa fa-golf-ball' => 'Golf Ball' ),
		524  => array( 'fab fa-goodreads' => 'Goodreads' ),
		525  => array( 'fab fa-goodreads-g' => 'Goodreads G' ),
		526  => array( 'fab fa-google' => 'Google Logo' ),
		527  => array( 'fab fa-google-drive' => 'Google Drive' ),
		528  => array( 'fab fa-google-play' => 'Google Play' ),
		529  => array( 'fab fa-google-plus' => 'Google Plus' ),
		530  => array( 'fab fa-google-plus-g' => 'Google Plus G' ),
		531  => array( 'fab fa-google-plus-square' => 'Google Plus Square' ),
		532  => array( 'fab fa-google-wallet' => 'Google Wallet' ),
		533  => array( 'fa fa-graduation-cap' => 'Graduation Cap' ),
		534  => array( 'fab fa-gratipay' => 'Gratipay (Gittip)' ),
		535  => array( 'fab fa-grav' => 'Grav' ),
		536  => array( 'fa fa-greater-than' => 'Greater Than' ),
		537  => array( 'fa fa-greater-than-equal' => 'Greater Than Equal To' ),
		538  => array( 'fa fa-grimace' => 'Grimacing Face' ),
		539  => array( 'far fa-fa fa-grimace' => 'Grimacing Face' ),
		540  => array( 'fa fa-grin' => 'Grinning Face' ),
		541  => array( 'far fa-fa fa-grin' => 'Grinning Face' ),
		542  => array( 'fa fa-grin-alt' => 'Alternate Grinning Face' ),
		543  => array( 'far fa-fa fa-grin-alt' => 'Alternate Grinning Face' ),
		544  => array( 'fa fa-grin-beam' => 'Grinning Face With Smiling Eyes' ),
		545  => array( 'far fa-fa fa-grin-beam' => 'Grinning Face With Smiling Eyes' ),
		546  => array( 'fa fa-grin-beam-sweat' => 'Grinning Face With Sweat' ),
		547  => array( 'far fa-fa fa-grin-beam-sweat' => 'Grinning Face With Sweat' ),
		548  => array( 'fa fa-grin-hearts' => 'Smiling Face With Heart-Eyes' ),
		549  => array( 'far fa-fa fa-grin-hearts' => 'Smiling Face With Heart-Eyes' ),
		550  => array( 'fa fa-grin-squint' => 'Grinning Squinting Face' ),
		551  => array( 'far fa-fa fa-grin-squint' => 'Grinning Squinting Face' ),
		552  => array( 'fa fa-grin-squint-tears' => 'Rolling on the Floor Laughing' ),
		553  => array( 'far fa-fa fa-grin-squint-tears' => 'Rolling on the Floor Laughing' ),
		554  => array( 'fa fa-grin-stars' => 'Star-Struck' ),
		555  => array( 'far fa-fa fa-grin-stars' => 'Star-Struck' ),
		556  => array( 'fa fa-grin-tears' => 'Face With Tears of Joy' ),
		557  => array( 'far fa-fa fa-grin-tears' => 'Face With Tears of Joy' ),
		558  => array( 'fa fa-grin-tongue' => 'Face With Tongue' ),
		559  => array( 'far fa-fa fa-grin-tongue' => 'Face With Tongue' ),
		560  => array( 'fa fa-grin-tongue-squint' => 'Squinting Face With Tongue' ),
		561  => array( 'far fa-fa fa-grin-tongue-squint' => 'Squinting Face With Tongue' ),
		562  => array( 'fa fa-grin-tongue-wink' => 'Winking Face With Tongue' ),
		563  => array( 'far fa-fa fa-grin-tongue-wink' => 'Winking Face With Tongue' ),
		564  => array( 'fa fa-grin-wink' => 'Grinning Winking Face' ),
		565  => array( 'far fa-fa fa-grin-wink' => 'Grinning Winking Face' ),
		566  => array( 'fa fa-grip-horizontal' => 'Grip Horizontal' ),
		567  => array( 'fa fa-grip-vertical' => 'Grip Vertical' ),
		568  => array( 'fab fa-gripfire' => 'Gripfire, Inc.' ),
		569  => array( 'fab fa-grunt' => 'Grunt' ),
		570  => array( 'fab fa-gulp' => 'Gulp' ),
		571  => array( 'fa fa-h-square' => 'H Square' ),
		572  => array( 'fab fa-hacker-news' => 'Hacker News' ),
		573  => array( 'fab fa-hacker-news-square' => 'Hacker News Square' ),
		574  => array( 'fab fa-hackerrank' => 'Hackerrank' ),
		575  => array( 'fa fa-hand-holding' => 'Hand Holding' ),
		576  => array( 'fa fa-hand-holding-heart' => 'Hand Holding Heart' ),
		577  => array( 'fa fa-hand-holding-usd' => 'Hand Holding US Dollar' ),
		578  => array( 'fa fa-hand-lizard' => 'Lizard (Hand)' ),
		579  => array( 'far fa-fa fa-hand-lizard' => 'Lizard (Hand)' ),
		580  => array( 'fa fa-hand-paper' => 'Paper (Hand)' ),
		581  => array( 'far fa-fa fa-hand-paper' => 'Paper (Hand)' ),
		582  => array( 'fa fa-hand-peace' => 'Peace (Hand)' ),
		583  => array( 'far fa-fa fa-hand-peace' => 'Peace (Hand)' ),
		584  => array( 'fa fa-hand-point-down' => 'Hand Pointing Down' ),
		585  => array( 'far fa-fa fa-hand-point-down' => 'Hand Pointing Down' ),
		586  => array( 'fa fa-hand-point-left' => 'Hand Pointing Left' ),
		587  => array( 'far fa-fa fa-hand-point-left' => 'Hand Pointing Left' ),
		588  => array( 'fa fa-hand-point-right' => 'Hand Pointing Right' ),
		589  => array( 'far fa-fa fa-hand-point-right' => 'Hand Pointing Right' ),
		590  => array( 'fa fa-hand-point-up' => 'Hand Pointing Up' ),
		591  => array( 'far fa-fa fa-hand-point-up' => 'Hand Pointing Up' ),
		592  => array( 'fa fa-hand-pointer' => 'Pointer (Hand)' ),
		593  => array( 'far fa-fa fa-hand-pointer' => 'Pointer (Hand)' ),
		594  => array( 'fa fa-hand-rock' => 'Rock (Hand)' ),
		595  => array( 'far fa-fa fa-hand-rock' => 'Rock (Hand)' ),
		596  => array( 'fa fa-hand-scissors' => 'Scissors (Hand)' ),
		597  => array( 'far fa-fa fa-hand-scissors' => 'Scissors (Hand)' ),
		598  => array( 'fa fa-hand-spock' => 'Spock (Hand)' ),
		599  => array( 'far fa-fa fa-hand-spock' => 'Spock (Hand)' ),
		600  => array( 'fa fa-hands' => 'Hands' ),
		601  => array( 'fa fa-hands-helping' => 'Helping Hands' ),
		602  => array( 'fa fa-handshake' => 'Handshake' ),
		603  => array( 'far fa-fa fa-handshake' => 'Handshake' ),
		604  => array( 'fa fa-hashtag' => 'Hashtag' ),
		605  => array( 'fa fa-hdd' => 'HDD' ),
		606  => array( 'far fa-fa fa-hdd' => 'HDD' ),
		607  => array( 'fa fa-heading' => 'heading' ),
		608  => array( 'fa fa-headphones' => 'headphones' ),
		609  => array( 'fa fa-headphones-alt' => 'Headphones Alt' ),
		610  => array( 'fa fa-headset' => 'Headset' ),
		611  => array( 'fa fa-heart' => 'Heart' ),
		612  => array( 'far fa-fa fa-heart' => 'Heart' ),
		613  => array( 'fa fa-heartbeat' => 'Heartbeat' ),
		614  => array( 'fa fa-helicopter' => 'Helicopter' ),
		615  => array( 'fa fa-highlighter' => 'Highlighter' ),
		616  => array( 'fab fa-hips' => 'Hips' ),
		617  => array( 'fab fa-hire-a-helper' => 'HireAHelper' ),
		618  => array( 'fa fa-history' => 'History' ),
		619  => array( 'fa fa-hockey-puck' => 'Hockey Puck' ),
		620  => array( 'fa fa-home' => 'home' ),
		621  => array( 'fab fa-hooli' => 'Hooli' ),
		622  => array( 'fab fa-hornbill' => 'Hornbill' ),
		623  => array( 'fa fa-hospital' => 'hospital' ),
		624  => array( 'far fa-fa fa-hospital' => 'hospital' ),
		625  => array( 'fa fa-hospital-alt' => 'Alternate Hospital' ),
		626  => array( 'fa fa-hospital-symbol' => 'Hospital Symbol' ),
		627  => array( 'fa fa-hot-tub' => 'Hot Tub' ),
		628  => array( 'fa fa-hotel' => 'Hotel' ),
		629  => array( 'fab fa-hotjar' => 'Hotjar' ),
		630  => array( 'fa fa-hourglass' => 'Hourglass' ),
		631  => array( 'far fa-fa fa-hourglass' => 'Hourglass' ),
		632  => array( 'fa fa-hourglass-end' => 'Hourglass End' ),
		633  => array( 'fa fa-hourglass-half' => 'Hourglass Half' ),
		634  => array( 'fa fa-hourglass-start' => 'Hourglass Start' ),
		635  => array( 'fab fa-houzz' => 'Houzz' ),
		636  => array( 'fab fa-html5' => 'HTML 5 Logo' ),
		637  => array( 'fab fa-hubspot' => 'HubSpot' ),
		638  => array( 'fa fa-i-cursor' => 'I Beam Cursor' ),
		639  => array( 'fa fa-id-badge' => 'Identification Badge' ),
		640  => array( 'far fa-fa fa-id-badge' => 'Identification Badge' ),
		641  => array( 'fa fa-id-card' => 'Identification Card' ),
		642  => array( 'far fa-fa fa-id-card' => 'Identification Card' ),
		643  => array( 'fa fa-id-card-alt' => 'Alternate Identification Card' ),
		644  => array( 'fa fa-image' => 'Image' ),
		645  => array( 'far fa-fa fa-image' => 'Image' ),
		646  => array( 'fa fa-images' => 'Images' ),
		647  => array( 'far fa-fa fa-images' => 'Images' ),
		648  => array( 'fab fa-imdb' => 'IMDB' ),
		649  => array( 'fa fa-inbox' => 'inbox' ),
		650  => array( 'fa fa-indent' => 'Indent' ),
		651  => array( 'fa fa-industry' => 'Industry' ),
		652  => array( 'fa fa-infinity' => 'Infinity' ),
		653  => array( 'fa fa-info' => 'Info' ),
		654  => array( 'fa fa-info-circle' => 'Info Circle' ),
		655  => array( 'fab fa-instagram' => 'Instagram' ),
		656  => array( 'fab fa-internet-explorer' => 'Internet-explorer' ),
		657  => array( 'fab fa-ioxhost' => 'ioxhost' ),
		658  => array( 'fa fa-italic' => 'italic' ),
		659  => array( 'fab fa-itunes' => 'iTunes' ),
		660  => array( 'fab fa-itunes-note' => 'Itunes Note' ),
		661  => array( 'fab fa-java' => 'Java' ),
		662  => array( 'fab fa-jedi-order' => 'Jedi Order' ),
		663  => array( 'fab fa-jenkins' => 'Jenkis' ),
		664  => array( 'fab fa-joget' => 'Joget' ),
		665  => array( 'fa fa-joint' => 'Joint' ),
		666  => array( 'fab fa-joomla' => 'Joomla Logo' ),
		667  => array( 'fab fa-js' => 'JavaScript (JS)' ),
		668  => array( 'fab fa-js-square' => 'JavaScript (JS) Square' ),
		669  => array( 'fab fa-jsfiddle' => 'jsFiddle' ),
		670  => array( 'fab fa-kaggle' => 'Kaggle' ),
		671  => array( 'fa fa-key' => 'key' ),
		672  => array( 'fab fa-keybase' => 'Keybase' ),
		673  => array( 'fa fa-keyboard' => 'Keyboard' ),
		674  => array( 'far fa-fa fa-keyboard' => 'Keyboard' ),
		675  => array( 'fab fa-keycdn' => 'KeyCDN' ),
		676  => array( 'fab fa-kickstarter' => 'Kickstarter' ),
		677  => array( 'fab fa-kickstarter-k' => 'Kickstarter K' ),
		678  => array( 'fa fa-kiss' => 'Kissing Face' ),
		679  => array( 'far fa-fa fa-kiss' => 'Kissing Face' ),
		680  => array( 'fa fa-kiss-beam' => 'Kissing Face With Smiling Eyes' ),
		681  => array( 'far fa-fa fa-kiss-beam' => 'Kissing Face With Smiling Eyes' ),
		682  => array( 'fa fa-kiss-wink-heart' => 'Face Blowing a Kiss' ),
		683  => array( 'far fa-fa fa-kiss-wink-heart' => 'Face Blowing a Kiss' ),
		684  => array( 'fa fa-kiwi-bird' => 'Kiwi Bird' ),
		685  => array( 'fab fa-korvue' => 'KORVUE' ),
		686  => array( 'fa fa-language' => 'Language' ),
		687  => array( 'fa fa-laptop' => 'Laptop' ),
		688  => array( 'fa fa-laptop-code' => 'Laptop Code' ),
		689  => array( 'fab fa-laravel' => 'Laravel' ),
		690  => array( 'fab fa-lastfm' => 'last.fm' ),
		691  => array( 'fab fa-lastfm-square' => 'last.fm Square' ),
		692  => array( 'fa fa-laugh' => 'Grinning Face With Big Eyes' ),
		693  => array( 'far fa-fa fa-laugh' => 'Grinning Face With Big Eyes' ),
		694  => array( 'fa fa-laugh-beam' => 'Laugh Face with Beaming Eyes' ),
		695  => array( 'far fa-fa fa-laugh-beam' => 'Laugh Face with Beaming Eyes' ),
		696  => array( 'fa fa-laugh-squint' => 'Laughing Squinting Face' ),
		697  => array( 'far fa-fa fa-laugh-squint' => 'Laughing Squinting Face' ),
		698  => array( 'fa fa-laugh-wink' => 'Laughing Winking Face' ),
		699  => array( 'far fa-fa fa-laugh-wink' => 'Laughing Winking Face' ),
		700  => array( 'fa fa-layer-group' => 'Layer Group' ),
		701  => array( 'fa fa-leaf' => 'leaf' ),
		702  => array( 'fab fa-leanpub' => 'Leanpub' ),
		703  => array( 'fa fa-lemon' => 'Lemon' ),
		704  => array( 'far fa-fa fa-lemon' => 'Lemon' ),
		705  => array( 'fab fa-less' => 'Less' ),
		706  => array( 'fa fa-less-than' => 'Less Than' ),
		707  => array( 'fa fa-less-than-equal' => 'Less Than Equal To' ),
		708  => array( 'fa fa-level-down-alt' => 'Alternate Level Down' ),
		709  => array( 'fa fa-level-up-alt' => 'Alternate Level Up' ),
		710  => array( 'fa fa-life-ring' => 'Life Ring' ),
		711  => array( 'far fa-fa fa-life-ring' => 'Life Ring' ),
		712  => array( 'fa fa-lightbulb' => 'Lightbulb' ),
		713  => array( 'far fa-fa fa-lightbulb' => 'Lightbulb' ),
		714  => array( 'fab fa-line' => 'Line' ),
		715  => array( 'fa fa-link' => 'Link' ),
		716  => array( 'fab fa-linkedin' => 'LinkedIn' ),
		717  => array( 'fab fa-linkedin-in' => 'LinkedIn In' ),
		718  => array( 'fab fa-linode' => 'Linode' ),
		719  => array( 'fab fa-linux' => 'Linux' ),
		720  => array( 'fa fa-lira-sign' => 'Turkish Lira Sign' ),
		721  => array( 'fa fa-list' => 'List' ),
		722  => array( 'fa fa-list-alt' => 'Alternate List' ),
		723  => array( 'far fa-fa fa-list-alt' => 'Alternate List' ),
		724  => array( 'fa fa-list-ol' => 'list-ol' ),
		725  => array( 'fa fa-list-ul' => 'list-ul' ),
		726  => array( 'fa fa-location-arrow' => 'location-arrow' ),
		727  => array( 'fa fa-lock' => 'lock' ),
		728  => array( 'fa fa-lock-open' => 'Lock Open' ),
		729  => array( 'fa fa-long-arrow-alt-down' => 'Alternate Long Arrow Down' ),
		730  => array( 'fa fa-long-arrow-alt-left' => 'Alternate Long Arrow Left' ),
		731  => array( 'fa fa-long-arrow-alt-right' => 'Alternate Long Arrow Right' ),
		732  => array( 'fa fa-long-arrow-alt-up' => 'Alternate Long Arrow Up' ),
		733  => array( 'fa fa-low-vision' => 'Low Vision' ),
		734  => array( 'fa fa-luggage-cart' => 'Luggage Cart' ),
		735  => array( 'fab fa-lyft' => 'lyft' ),
		736  => array( 'fab fa-magento' => 'Magento' ),
		737  => array( 'fa fa-magic' => 'magic' ),
		738  => array( 'fa fa-magnet' => 'magnet' ),
		739  => array( 'fab fa-mailchimp' => 'Mailchimp' ),
		740  => array( 'fa fa-male' => 'Male' ),
		741  => array( 'fab fa-mandalorian' => 'Mandalorian' ),
		742  => array( 'fa fa-map' => 'Map' ),
		743  => array( 'far fa-fa fa-map' => 'Map' ),
		744  => array( 'fa fa-map-marked' => 'Map Marked' ),
		745  => array( 'fa fa-map-marked-alt' => 'Map Marked-alt' ),
		746  => array( 'fa fa-map-marker' => 'map-marker' ),
		747  => array( 'fa fa-map-marker-alt' => 'Alternate Map Marker' ),
		748  => array( 'fa fa-map-pin' => 'Map Pin' ),
		749  => array( 'fa fa-map-signs' => 'Map Signs' ),
		750  => array( 'fab fa-markdown' => 'Markdown' ),
		751  => array( 'fa fa-marker' => 'Marker' ),
		752  => array( 'fa fa-mars' => 'Mars' ),
		753  => array( 'fa fa-mars-double' => 'Mars Double' ),
		754  => array( 'fa fa-mars-stroke' => 'Mars Stroke' ),
		755  => array( 'fa fa-mars-stroke-h' => 'Mars Stroke Horizontal' ),
		756  => array( 'fa fa-mars-stroke-v' => 'Mars Stroke Vertical' ),
		757  => array( 'fab fa-mastodon' => 'Mastodon' ),
		758  => array( 'fab fa-maxcdn' => 'MaxCDN' ),
		759  => array( 'fa fa-medal' => 'Medal' ),
		760  => array( 'fab fa-medapps' => 'MedApps' ),
		761  => array( 'fab fa-medium' => 'Medium' ),
		762  => array( 'fab fa-medium-m' => 'Medium M' ),
		763  => array( 'fa fa-medkit' => 'medkit' ),
		764  => array( 'fab fa-medrt' => 'MRT' ),
		765  => array( 'fab fa-meetup' => 'Meetup' ),
		766  => array( 'fab fa-megaport' => 'Megaport' ),
		767  => array( 'fa fa-meh' => 'Neutral Face' ),
		768  => array( 'far fa-fa fa-meh' => 'Neutral Face' ),
		769  => array( 'fa fa-meh-blank' => 'Face Without Mouth' ),
		770  => array( 'far fa-fa fa-meh-blank' => 'Face Without Mouth' ),
		771  => array( 'fa fa-meh-rolling-eyes' => 'Face With Rolling Eyes' ),
		772  => array( 'far fa-fa fa-meh-rolling-eyes' => 'Face With Rolling Eyes' ),
		773  => array( 'fa fa-memory' => 'Memory' ),
		774  => array( 'fa fa-mercury' => 'Mercury' ),
		775  => array( 'fa fa-microchip' => 'Microchip' ),
		776  => array( 'fa fa-microphone' => 'microphone' ),
		777  => array( 'fa fa-microphone-alt' => 'Alternate Microphone' ),
		778  => array( 'fa fa-microphone-alt-slash' => 'Alternate Microphone Slash' ),
		779  => array( 'fa fa-microphone-slash' => 'Microphone Slash' ),
		780  => array( 'fa fa-microscope' => 'Microscope' ),
		781  => array( 'fab fa-microsoft' => 'Microsoft' ),
		782  => array( 'fa fa-minus' => 'minus' ),
		783  => array( 'fa fa-minus-circle' => 'Minus Circle' ),
		784  => array( 'fa fa-minus-square' => 'Minus Square' ),
		785  => array( 'far fa-fa fa-minus-square' => 'Minus Square' ),
		786  => array( 'fab fa-mix' => 'Mix' ),
		787  => array( 'fab fa-mixcloud' => 'Mixcloud' ),
		788  => array( 'fab fa-mizuni' => 'Mizuni' ),
		789  => array( 'fa fa-mobile' => 'Mobile Phone' ),
		790  => array( 'fa fa-mobile-alt' => 'Alternate Mobile' ),
		791  => array( 'fab fa-modx' => 'MODX' ),
		792  => array( 'fab fa-monero' => 'Monero' ),
		793  => array( 'fa fa-money-bill' => 'Money Bill' ),
		794  => array( 'fa fa-money-bill-alt' => 'Alternate Money Bill' ),
		795  => array( 'far fa-fa fa-money-bill-alt' => 'Alternate Money Bill' ),
		796  => array( 'fa fa-money-bill-wave' => 'Wavy Money Bill' ),
		797  => array( 'fa fa-money-bill-wave-alt' => 'Alternate Wavy Money Bill' ),
		798  => array( 'fa fa-money-check' => 'Money Check' ),
		799  => array( 'fa fa-money-check-alt' => 'Alternate Money Check' ),
		800  => array( 'fa fa-monument' => 'Monument' ),
		801  => array( 'fa fa-moon' => 'Moon' ),
		802  => array( 'far fa-fa fa-moon' => 'Moon' ),
		803  => array( 'fa fa-mortar-pestle' => 'Mortar Pestle' ),
		804  => array( 'fa fa-motorcycle' => 'Motorcycle' ),
		805  => array( 'fa fa-mouse-pointer' => 'Mouse Pointer' ),
		806  => array( 'fa fa-music' => 'Music' ),
		807  => array( 'fab fa-napster' => 'Napster' ),
		808  => array( 'fab fa-neos' => 'Neos' ),
		809  => array( 'fa fa-neuter' => 'Neuter' ),
		810  => array( 'fa fa-newspaper' => 'Newspaper' ),
		811  => array( 'far fa-fa fa-newspaper' => 'Newspaper' ),
		812  => array( 'fab fa-nimblr' => 'Nimblr' ),
		813  => array( 'fab fa-nintendo-switch' => 'Nintendo Switch' ),
		814  => array( 'fab fa-node' => 'Node.js' ),
		815  => array( 'fab fa-node-js' => 'Node.js JS' ),
		816  => array( 'fa fa-not-equal' => 'Not Equal' ),
		817  => array( 'fa fa-notes-medical' => 'Medical Notes' ),
		818  => array( 'fab fa-npm' => 'npm' ),
		819  => array( 'fab fa-ns8' => 'NS8' ),
		820  => array( 'fab fa-nutritionix' => 'Nutritionix' ),
		821  => array( 'fa fa-object-group' => 'Object Group' ),
		822  => array( 'far fa-fa fa-object-group' => 'Object Group' ),
		823  => array( 'fa fa-object-ungroup' => 'Object Ungroup' ),
		824  => array( 'far fa-fa fa-object-ungroup' => 'Object Ungroup' ),
		825  => array( 'fab fa-odnoklassniki' => 'Odnoklassniki' ),
		826  => array( 'fab fa-odnoklassniki-square' => 'Odnoklassniki Square' ),
		827  => array( 'fa fa-oil-can' => 'Oil Can' ),
		828  => array( 'fab fa-old-republic' => 'Old Republic' ),
		829  => array( 'fab fa-opencart' => 'OpenCart' ),
		830  => array( 'fab fa-openid' => 'OpenID' ),
		831  => array( 'fab fa-opera' => 'Opera' ),
		832  => array( 'fab fa-optin-monster' => 'Optin Monster' ),
		833  => array( 'fab fa-osi' => 'Open Source Initiative' ),
		834  => array( 'fa fa-outdent' => 'Outdent' ),
		835  => array( 'fab fa-page4' => 'page4 Corporation' ),
		836  => array( 'fab fa-pagelines' => 'Pagelines' ),
		837  => array( 'fa fa-paint-brush' => 'Paint Brush' ),
		838  => array( 'fa fa-paint-roller' => 'Paint Roller' ),
		839  => array( 'fa fa-palette' => 'Palette' ),
		840  => array( 'fab fa-palfed' => 'Palfed' ),
		841  => array( 'fa fa-pallet' => 'Pallet' ),
		842  => array( 'fa fa-paper-plane' => 'Paper Plane' ),
		843  => array( 'far fa-fa fa-paper-plane' => 'Paper Plane' ),
		844  => array( 'fa fa-paperclip' => 'Paperclip' ),
		845  => array( 'fa fa-parachute-box' => 'Parachute Box' ),
		846  => array( 'fa fa-paragraph' => 'paragraph' ),
		847  => array( 'fa fa-parking' => 'Parking' ),
		848  => array( 'fa fa-passport' => 'Passport' ),
		849  => array( 'fa fa-paste' => 'Paste' ),
		850  => array( 'fab fa-patreon' => 'Patreon' ),
		851  => array( 'fa fa-pause' => 'pause' ),
		852  => array( 'fa fa-pause-circle' => 'Pause Circle' ),
		853  => array( 'far fa-fa fa-pause-circle' => 'Pause Circle' ),
		854  => array( 'fa fa-paw' => 'Paw' ),
		855  => array( 'fab fa-paypal' => 'Paypal' ),
		856  => array( 'fa fa-pen' => 'Pen' ),
		857  => array( 'fa fa-pen-alt' => 'Alternate Pen' ),
		858  => array( 'fa fa-pen-fancy' => 'Pen Fancy' ),
		859  => array( 'fa fa-pen-nib' => 'Pen Nib' ),
		860  => array( 'fa fa-pen-square' => 'Pen Square' ),
		861  => array( 'fa fa-pencil-alt' => 'Alternate Pencil' ),
		862  => array( 'fa fa-pencil-ruler' => 'Pencil Ruler' ),
		863  => array( 'fa fa-people-carry' => 'People Carry' ),
		864  => array( 'fa fa-percent' => 'Percent' ),
		865  => array( 'fa fa-percentage' => 'Percentage' ),
		866  => array( 'fab fa-periscope' => 'Periscope' ),
		867  => array( 'fab fa-phabricator' => 'Phabricator' ),
		868  => array( 'fab fa-phoenix-framework' => 'Phoenix Framework' ),
		869  => array( 'fab fa-phoenix-squadron' => 'Phoenix Squadron' ),
		870  => array( 'fa fa-phone' => 'Phone' ),
		871  => array( 'fa fa-phone-slash' => 'Phone Slash' ),
		872  => array( 'fa fa-phone-square' => 'Phone Square' ),
		873  => array( 'fa fa-phone-volume' => 'Phone Volume' ),
		874  => array( 'fab fa-php' => 'PHP' ),
		875  => array( 'fab fa-pied-piper' => 'Pied Piper Logo' ),
		876  => array( 'fab fa-pied-piper-alt' => 'Alternate Pied Piper Logo' ),
		877  => array( 'fab fa-pied-piper-hat' => 'Pied Piper-hat' ),
		878  => array( 'fab fa-pied-piper-pp' => 'Pied Piper PP Logo (Old)' ),
		879  => array( 'fa fa-piggy-bank' => 'Piggy Bank' ),
		880  => array( 'fa fa-pills' => 'Pills' ),
		881  => array( 'fab fa-pinterest' => 'Pinterest' ),
		882  => array( 'fab fa-pinterest-p' => 'Pinterest P' ),
		883  => array( 'fab fa-pinterest-square' => 'Pinterest Square' ),
		884  => array( 'fa fa-plane' => 'plane' ),
		885  => array( 'fa fa-plane-arrival' => 'Plane Arrival' ),
		886  => array( 'fa fa-plane-departure' => 'Plane Departure' ),
		887  => array( 'fa fa-play' => 'play' ),
		888  => array( 'fa fa-play-circle' => 'Play Circle' ),
		889  => array( 'far fa-fa fa-play-circle' => 'Play Circle' ),
		890  => array( 'fab fa-playstation' => 'PlayStation' ),
		891  => array( 'fa fa-plug' => 'Plug' ),
		892  => array( 'fa fa-plus' => 'plus' ),
		893  => array( 'fa fa-plus-circle' => 'Plus Circle' ),
		894  => array( 'fa fa-plus-square' => 'Plus Square' ),
		895  => array( 'far fa-fa fa-plus-square' => 'Plus Square' ),
		896  => array( 'fa fa-podcast' => 'Podcast' ),
		897  => array( 'fa fa-poo' => 'Poo' ),
		898  => array( 'fa fa-poop' => 'Poop' ),
		899  => array( 'fa fa-portrait' => 'Portrait' ),
		900  => array( 'fa fa-pound-sign' => 'Pound Sign' ),
		901  => array( 'fa fa-power-off' => 'Power Off' ),
		902  => array( 'fa fa-prescription' => 'Prescription' ),
		903  => array( 'fa fa-prescription-bottle' => 'Prescription Bottle' ),
		904  => array( 'fa fa-prescription-bottle-alt' => 'Alternate Prescription Bottle' ),
		905  => array( 'fa fa-print' => 'print' ),
		906  => array( 'fa fa-procedures' => 'Procedures' ),
		907  => array( 'fab fa-product-hunt' => 'Product Hunt' ),
		908  => array( 'fa fa-project-diagram' => 'Project Diagram' ),
		909  => array( 'fab fa-pushed' => 'Pushed' ),
		910  => array( 'fa fa-puzzle-piece' => 'Puzzle Piece' ),
		911  => array( 'fab fa-python' => 'Python' ),
		912  => array( 'fab fa-qq' => 'QQ' ),
		913  => array( 'fa fa-qrcode' => 'qrcode' ),
		914  => array( 'fa fa-question' => 'Question' ),
		915  => array( 'fa fa-question-circle' => 'Question Circle' ),
		916  => array( 'far fa-fa fa-question-circle' => 'Question Circle' ),
		917  => array( 'fa fa-quidditch' => 'Quidditch' ),
		918  => array( 'fab fa-quinscape' => 'QuinScape' ),
		919  => array( 'fab fa-quora' => 'Quora' ),
		920  => array( 'fa fa-quote-left' => 'quote-left' ),
		921  => array( 'fa fa-quote-right' => 'quote-right' ),
		922  => array( 'fab fa-r-project' => 'R Project' ),
		923  => array( 'fa fa-random' => 'random' ),
		924  => array( 'fab fa-ravelry' => 'Ravelry' ),
		925  => array( 'fab fa-react' => 'React' ),
		926  => array( 'fab fa-readme' => 'ReadMe' ),
		927  => array( 'fab fa-rebel' => 'Rebel Alliance' ),
		928  => array( 'fa fa-receipt' => 'Receipt' ),
		929  => array( 'fa fa-recycle' => 'Recycle' ),
		930  => array( 'fab fa-red-river' => 'red river' ),
		931  => array( 'fab fa-reddit' => 'reddit Logo' ),
		932  => array( 'fab fa-reddit-alien' => 'reddit Alien' ),
		933  => array( 'fab fa-reddit-square' => 'reddit Square' ),
		934  => array( 'fa fa-redo' => 'Redo' ),
		935  => array( 'fa fa-redo-alt' => 'Alternate Redo' ),
		936  => array( 'fa fa-registered' => 'Registered Trademark' ),
		937  => array( 'far fa-fa fa-registered' => 'Registered Trademark' ),
		938  => array( 'fab fa-rendact' => 'Rendact' ),
		939  => array( 'fab fa-renren' => 'Renren' ),
		940  => array( 'fa fa-reply' => 'Reply' ),
		941  => array( 'fa fa-reply-all' => 'reply-all' ),
		942  => array( 'fab fa-replyd' => 'replyd' ),
		943  => array( 'fab fa-researchgate' => 'Researchgate' ),
		944  => array( 'fab fa-resolving' => 'Resolving' ),
		945  => array( 'fa fa-retweet' => 'Retweet' ),
		946  => array( 'fab fa-rev' => 'Rev.io' ),
		947  => array( 'fa fa-ribbon' => 'Ribbon' ),
		948  => array( 'fa fa-road' => 'road' ),
		949  => array( 'fa fa-robot' => 'Robot' ),
		950  => array( 'fa fa-rocket' => 'rocket' ),
		951  => array( 'fab fa-rocketchat' => 'Rocket.Chat' ),
		952  => array( 'fab fa-rockrms' => 'Rockrms' ),
		953  => array( 'fa fa-route' => 'Route' ),
		954  => array( 'fa fa-rss' => 'rss' ),
		955  => array( 'fa fa-rss-square' => 'RSS Square' ),
		956  => array( 'fa fa-ruble-sign' => 'Ruble Sign' ),
		957  => array( 'fa fa-ruler' => 'Ruler' ),
		958  => array( 'fa fa-ruler-combined' => 'Ruler Combined' ),
		959  => array( 'fa fa-ruler-horizontal' => 'Ruler Horizontal' ),
		960  => array( 'fa fa-ruler-vertical' => 'Ruler Vertical' ),
		961  => array( 'fa fa-rupee-sign' => 'Indian Rupee Sign' ),
		962  => array( 'fa fa-sad-cry' => 'Crying Face' ),
		963  => array( 'far fa-fa fa-sad-cry' => 'Crying Face' ),
		964  => array( 'fa fa-sad-tear' => 'Loudly Crying Face' ),
		965  => array( 'far fa-fa fa-sad-tear' => 'Loudly Crying Face' ),
		966  => array( 'fab fa-safari' => 'Safari' ),
		967  => array( 'fab fa-sass' => 'Sass' ),
		968  => array( 'fa fa-save' => 'Save' ),
		969  => array( 'far fa-fa fa-save' => 'Save' ),
		970  => array( 'fab fa-schlix' => 'SCHLIX' ),
		971  => array( 'fa fa-school' => 'School' ),
		972  => array( 'fa fa-screwdriver' => 'Screwdriver' ),
		973  => array( 'fab fa-scribd' => 'Scribd' ),
		974  => array( 'fa fa-search' => 'Search' ),
		975  => array( 'fa fa-search-minus' => 'Search Minus' ),
		976  => array( 'fa fa-search-plus' => 'Search Plus' ),
		977  => array( 'fab fa-searchengin' => 'Searchengin' ),
		978  => array( 'fa fa-seedling' => 'Seedling' ),
		979  => array( 'fab fa-sellcast' => 'Sellcast' ),
		980  => array( 'fab fa-sellsy' => 'Sellsy' ),
		981  => array( 'fa fa-server' => 'Server' ),
		982  => array( 'fab fa-servicestack' => 'Servicestack' ),
		983  => array( 'fa fa-shapes' => 'Shapes' ),
		984  => array( 'fa fa-share' => 'Share' ),
		985  => array( 'fa fa-share-alt' => 'Alternate Share' ),
		986  => array( 'fa fa-share-alt-square' => 'Alternate Share Square' ),
		987  => array( 'fa fa-share-square' => 'Share Square' ),
		988  => array( 'far fa-fa fa-share-square' => 'Share Square' ),
		989  => array( 'fa fa-shekel-sign' => 'Shekel Sign' ),
		990  => array( 'fa fa-shield-alt' => 'Alternate Shield' ),
		991  => array( 'fa fa-ship' => 'Ship' ),
		992  => array( 'fa fa-shipping-fast' => 'Shipping Fast' ),
		993  => array( 'fab fa-shirtsinbulk' => 'Shirts in Bulk' ),
		994  => array( 'fa fa-shoe-prints' => 'Shoe Prints' ),
		995  => array( 'fa fa-shopping-bag' => 'Shopping Bag' ),
		996  => array( 'fa fa-shopping-basket' => 'Shopping Basket' ),
		997  => array( 'fa fa-shopping-cart' => 'shopping-cart' ),
		998  => array( 'fab fa-shopware' => 'Shopware' ),
		999  => array( 'fa fa-shower' => 'Shower' ),
		1000 => array( 'fa fa-shuttle-van' => 'Shuttle Van' ),
		1001 => array( 'fa fa-sign' => 'Sign' ),
		1002 => array( 'fa fa-sign-in-alt' => 'Alternate Sign In' ),
		1003 => array( 'fa fa-sign-language' => 'Sign Language' ),
		1004 => array( 'fa fa-sign-out-alt' => 'Alternate Sign Out' ),
		1005 => array( 'fa fa-signal' => 'signal' ),
		1006 => array( 'fa fa-signature' => 'Signature' ),
		1007 => array( 'fab fa-simplybuilt' => 'SimplyBuilt' ),
		1008 => array( 'fab fa-sistrix' => 'SISTRIX' ),
		1009 => array( 'fa fa-sitemap' => 'Sitemap' ),
		1010 => array( 'fab fa-sith' => 'Sith' ),
		1011 => array( 'fa fa-skull' => 'Skull' ),
		1012 => array( 'fab fa-skyatlas' => 'skyatlas' ),
		1013 => array( 'fab fa-skype' => 'Skype' ),
		1014 => array( 'fab fa-slack' => 'Slack Logo' ),
		1015 => array( 'fab fa-slack-hash' => 'Slack Hashtag' ),
		1016 => array( 'fa fa-sliders-h' => 'Horizontal Sliders' ),
		1017 => array( 'fab fa-slideshare' => 'Slideshare' ),
		1018 => array( 'fa fa-smile' => 'Smiling Face' ),
		1019 => array( 'far fa-fa fa-smile' => 'Smiling Face' ),
		1020 => array( 'fa fa-smile-beam' => 'Beaming Face With Smiling Eyes' ),
		1021 => array( 'far fa-fa fa-smile-beam' => 'Beaming Face With Smiling Eyes' ),
		1022 => array( 'fa fa-smile-wink' => 'Winking Face' ),
		1023 => array( 'far fa-fa fa-smile-wink' => 'Winking Face' ),
		1024 => array( 'fa fa-smoking' => 'Smoking' ),
		1025 => array( 'fa fa-smoking-ban' => 'Smoking Ban' ),
		1026 => array( 'fab fa-snapchat' => 'Snapchat' ),
		1027 => array( 'fab fa-snapchat-ghost' => 'Snapchat Ghost' ),
		1028 => array( 'fab fa-snapchat-square' => 'Snapchat Square' ),
		1029 => array( 'fa fa-snowflake' => 'Snowflake' ),
		1030 => array( 'far fa-fa fa-snowflake' => 'Snowflake' ),
		1031 => array( 'fa fa-solar-panel' => 'Solar Panel' ),
		1032 => array( 'fa fa-sort' => 'Sort' ),
		1033 => array( 'fa fa-sort-alpha-down' => 'Sort Alpha Down' ),
		1034 => array( 'fa fa-sort-alpha-up' => 'Sort Alpha Up' ),
		1035 => array( 'fa fa-sort-amount-down' => 'Sort Amount Down' ),
		1036 => array( 'fa fa-sort-amount-up' => 'Sort Amount Up' ),
		1037 => array( 'fa fa-sort-down' => 'Sort Down (Descending)' ),
		1038 => array( 'fa fa-sort-numeric-down' => 'Sort Numeric Down' ),
		1039 => array( 'fa fa-sort-numeric-up' => 'Sort Numeric Up' ),
		1040 => array( 'fa fa-sort-up' => 'Sort Up (Ascending)' ),
		1041 => array( 'fab fa-soundcloud' => 'SoundCloud' ),
		1042 => array( 'fa fa-spa' => 'Spa' ),
		1043 => array( 'fa fa-space-shuttle' => 'Space Shuttle' ),
		1044 => array( 'fab fa-speakap' => 'Speakap' ),
		1045 => array( 'fa fa-spinner' => 'Spinner' ),
		1046 => array( 'fa fa-splotch' => 'Splotch' ),
		1047 => array( 'fab fa-spotify' => 'Spotify' ),
		1048 => array( 'fa fa-spray-can' => 'Spray Can' ),
		1049 => array( 'fa fa-square' => 'Square' ),
		1050 => array( 'far fa-fa fa-square' => 'Square' ),
		1051 => array( 'fa fa-square-full' => 'Square Full' ),
		1052 => array( 'fab fa-squarespace' => 'Squarespace' ),
		1053 => array( 'fab fa-stack-exchange' => 'Stack Exchange' ),
		1054 => array( 'fab fa-stack-overflow' => 'Stack Overflow' ),
		1055 => array( 'fa fa-stamp' => 'Stamp' ),
		1056 => array( 'fa fa-star' => 'Star' ),
		1057 => array( 'far fa-fa fa-star' => 'Star' ),
		1058 => array( 'fa fa-star-half' => 'star-half' ),
		1059 => array( 'far fa-fa fa-star-half' => 'star-half' ),
		1060 => array( 'fa fa-star-half-alt' => 'Alternate Star Half' ),
		1061 => array( 'fa fa-star-of-life' => 'Star Of-life' ),
		1062 => array( 'fab fa-staylinked' => 'StayLinked' ),
		1063 => array( 'fab fa-steam' => 'Steam' ),
		1064 => array( 'fab fa-steam-square' => 'Steam Square' ),
		1065 => array( 'fab fa-steam-symbol' => 'Steam Symbol' ),
		1066 => array( 'fa fa-step-backward' => 'step-backward' ),
		1067 => array( 'fa fa-step-forward' => 'step-forward' ),
		1068 => array( 'fa fa-stethoscope' => 'Stethoscope' ),
		1069 => array( 'fab fa-sticker-mule' => 'Sticker Mule' ),
		1070 => array( 'fa fa-sticky-note' => 'Sticky Note' ),
		1071 => array( 'far fa-fa fa-sticky-note' => 'Sticky Note' ),
		1072 => array( 'fa fa-stop' => 'stop' ),
		1073 => array( 'fa fa-stop-circle' => 'Stop Circle' ),
		1074 => array( 'far fa-fa fa-stop-circle' => 'Stop Circle' ),
		1075 => array( 'fa fa-stopwatch' => 'Stopwatch' ),
		1076 => array( 'fa fa-store' => 'Store' ),
		1077 => array( 'fa fa-store-alt' => 'Alternate Store' ),
		1078 => array( 'fab fa-strava' => 'Strava' ),
		1079 => array( 'fa fa-stream' => 'Stream' ),
		1080 => array( 'fa fa-street-view' => 'Street View' ),
		1081 => array( 'fa fa-strikethrough' => 'Strikethrough' ),
		1082 => array( 'fab fa-stripe' => 'Stripe' ),
		1083 => array( 'fab fa-stripe-s' => 'Stripe S' ),
		1084 => array( 'fa fa-stroopwafel' => 'Stroopwafel' ),
		1085 => array( 'fab fa-studiovinari' => 'Studio Vinari' ),
		1086 => array( 'fab fa-stumbleupon' => 'StumbleUpon Logo' ),
		1087 => array( 'fab fa-stumbleupon-circle' => 'StumbleUpon Circle' ),
		1088 => array( 'fa fa-subscript' => 'subscript' ),
		1089 => array( 'fa fa-subway' => 'Subway' ),
		1090 => array( 'fa fa-suitcase' => 'Suitcase' ),
		1091 => array( 'fa fa-suitcase-rolling' => 'Suitcase Rolling' ),
		1092 => array( 'fa fa-sun' => 'Sun' ),
		1093 => array( 'far fa-fa fa-sun' => 'Sun' ),
		1094 => array( 'fab fa-superpowers' => 'Superpowers' ),
		1095 => array( 'fa fa-superscript' => 'superscript' ),
		1096 => array( 'fab fa-supple' => 'Supple' ),
		1097 => array( 'fa fa-surprise' => 'Hushed Face' ),
		1098 => array( 'far fa-fa fa-surprise' => 'Hushed Face' ),
		1099 => array( 'fa fa-swatchbook' => 'Swatchbook' ),
		1100 => array( 'fa fa-swimmer' => 'Swimmer' ),
		1101 => array( 'fa fa-swimming-pool' => 'Swimming Pool' ),
		1102 => array( 'fa fa-sync' => 'Sync' ),
		1103 => array( 'fa fa-sync-alt' => 'Alternate Sync' ),
		1104 => array( 'fa fa-syringe' => 'Syringe' ),
		1105 => array( 'fa fa-table' => 'table' ),
		1106 => array( 'fa fa-table-tennis' => 'Table Tennis' ),
		1107 => array( 'fa fa-tablet' => 'tablet' ),
		1108 => array( 'fa fa-tablet-alt' => 'Alternate Tablet' ),
		1109 => array( 'fa fa-tablets' => 'Tablets' ),
		1110 => array( 'fa fa-tachometer-alt' => 'Alternate Tachometer' ),
		1111 => array( 'fa fa-tag' => 'tag' ),
		1112 => array( 'fa fa-tags' => 'tags' ),
		1113 => array( 'fa fa-tape' => 'Tape' ),
		1114 => array( 'fa fa-tasks' => 'Tasks' ),
		1115 => array( 'fa fa-taxi' => 'Taxi' ),
		1116 => array( 'fab fa-teamspeak' => 'TeamSpeak' ),
		1117 => array( 'fa fa-teeth' => 'Teeth' ),
		1118 => array( 'fa fa-teeth-open' => 'Teeth Open' ),
		1119 => array( 'fab fa-telegram' => 'Telegram' ),
		1120 => array( 'fab fa-telegram-plane' => 'Telegram Plane' ),
		1121 => array( 'fab fa-tencent-weibo' => 'Tencent Weibo' ),
		1122 => array( 'fa fa-terminal' => 'Terminal' ),
		1123 => array( 'fa fa-text-height' => 'text-height' ),
		1124 => array( 'fa fa-text-width' => 'text-width' ),
		1125 => array( 'fa fa-th' => 'th' ),
		1126 => array( 'fa fa-th-large' => 'th-large' ),
		1127 => array( 'fa fa-th-list' => 'th-list' ),
		1128 => array( 'fa fa-theater-masks' => 'Theater Masks' ),
		1129 => array( 'fab fa-themeco' => 'Themeco' ),
		1130 => array( 'fab fa-themeisle' => 'ThemeIsle' ),
		1131 => array( 'fa fa-thermometer' => 'Thermometer' ),
		1132 => array( 'fa fa-thermometer-empty' => 'Thermometer Empty' ),
		1133 => array( 'fa fa-thermometer-full' => 'Thermometer Full' ),
		1134 => array( 'fa fa-thermometer-half' => 'Thermometer 1/2 Full' ),
		1135 => array( 'fa fa-thermometer-quarter' => 'Thermometer 1/4 Full' ),
		1136 => array( 'fa fa-thermometer-three-quarters' => 'Thermometer 3/4 Full' ),
		1137 => array( 'fa fa-thumbs-down' => 'thumbs-down' ),
		1138 => array( 'far fa-fa fa-thumbs-down' => 'thumbs-down' ),
		1139 => array( 'fa fa-thumbs-up' => 'thumbs-up' ),
		1140 => array( 'far fa-fa fa-thumbs-up' => 'thumbs-up' ),
		1141 => array( 'fa fa-thumbtack' => 'Thumbtack' ),
		1142 => array( 'fa fa-ticket-alt' => 'Alternate Ticket' ),
		1143 => array( 'fa fa-times' => 'Times' ),
		1144 => array( 'fa fa-times-circle' => 'Times Circle' ),
		1145 => array( 'far fa-fa fa-times-circle' => 'Times Circle' ),
		1146 => array( 'fa fa-tint' => 'tint' ),
		1147 => array( 'fa fa-tint-slash' => 'Tint Slash' ),
		1148 => array( 'fa fa-tired' => 'Tired Face' ),
		1149 => array( 'far fa-fa fa-tired' => 'Tired Face' ),
		1150 => array( 'fa fa-toggle-off' => 'Toggle Off' ),
		1151 => array( 'fa fa-toggle-on' => 'Toggle On' ),
		1152 => array( 'fa fa-toolbox' => 'Toolbox' ),
		1153 => array( 'fa fa-tooth' => 'Tooth' ),
		1154 => array( 'fab fa-trade-federation' => 'Trade Federation' ),
		1155 => array( 'fa fa-trademark' => 'Trademark' ),
		1156 => array( 'fa fa-traffic-light' => 'Traffic Light' ),
		1157 => array( 'fa fa-train' => 'Train' ),
		1158 => array( 'fa fa-transgender' => 'Transgender' ),
		1159 => array( 'fa fa-transgender-alt' => 'Alternate Transgender' ),
		1160 => array( 'fa fa-trash' => 'Trash' ),
		1161 => array( 'fa fa-trash-alt' => 'Alternate Trash' ),
		1162 => array( 'far fa-fa fa-trash-alt' => 'Alternate Trash' ),
		1163 => array( 'fa fa-tree' => 'Tree' ),
		1164 => array( 'fab fa-trello' => 'Trello' ),
		1165 => array( 'fab fa-tripadvisor' => 'TripAdvisor' ),
		1166 => array( 'fa fa-trophy' => 'trophy' ),
		1167 => array( 'fa fa-truck' => 'truck' ),
		1168 => array( 'fa fa-truck-loading' => 'Truck Loading' ),
		1169 => array( 'fa fa-truck-monster' => 'Truck Monster' ),
		1170 => array( 'fa fa-truck-moving' => 'Truck Moving' ),
		1171 => array( 'fa fa-truck-pickup' => 'Truck Side' ),
		1172 => array( 'fa fa-tshirt' => 'T-Shirt' ),
		1173 => array( 'fa fa-tty' => 'TTY' ),
		1174 => array( 'fab fa-tumblr' => 'Tumblr' ),
		1175 => array( 'fab fa-tumblr-square' => 'Tumblr Square' ),
		1176 => array( 'fa fa-tv' => 'Television' ),
		1177 => array( 'fab fa-twitch' => 'Twitch' ),
		1178 => array( 'fab fa-twitter' => 'Twitter' ),
		1179 => array( 'fab fa-twitter-square' => 'Twitter Square' ),
		1180 => array( 'fab fa-typo3' => 'Typo3' ),
		1181 => array( 'fab fa-uber' => 'Uber' ),
		1182 => array( 'fab fa-uikit' => 'UIkit' ),
		1183 => array( 'fa fa-umbrella' => 'Umbrella' ),
		1184 => array( 'fa fa-umbrella-beach' => 'Umbrella Beach' ),
		1185 => array( 'fa fa-underline' => 'Underline' ),
		1186 => array( 'fa fa-undo' => 'Undo' ),
		1187 => array( 'fa fa-undo-alt' => 'Alternate Undo' ),
		1188 => array( 'fab fa-uniregistry' => 'Uniregistry' ),
		1189 => array( 'fa fa-universal-access' => 'Universal Access' ),
		1190 => array( 'fa fa-university' => 'University' ),
		1191 => array( 'fa fa-unlink' => 'unlink' ),
		1192 => array( 'fa fa-unlock' => 'unlock' ),
		1193 => array( 'fa fa-unlock-alt' => 'Alternate Unlock' ),
		1194 => array( 'fab fa-untappd' => 'Untappd' ),
		1195 => array( 'fa fa-upload' => 'Upload' ),
		1196 => array( 'fab fa-usb' => 'USB' ),
		1197 => array( 'fa fa-user' => 'User' ),
		1198 => array( 'far fa-fa fa-user' => 'User' ),
		1199 => array( 'fa fa-user-alt' => 'Alternate User' ),
		1200 => array( 'fa fa-user-alt-slash' => 'Alternate User Slash' ),
		1201 => array( 'fa fa-user-astronaut' => 'User Astronaut' ),
		1202 => array( 'fa fa-user-check' => 'User Check' ),
		1203 => array( 'fa fa-user-circle' => 'User Circle' ),
		1204 => array( 'far fa-fa fa-user-circle' => 'User Circle' ),
		1205 => array( 'fa fa-user-clock' => 'User Clock' ),
		1206 => array( 'fa fa-user-cog' => 'User Cog' ),
		1207 => array( 'fa fa-user-edit' => 'User Edit' ),
		1208 => array( 'fa fa-user-friends' => 'User Friends' ),
		1209 => array( 'fa fa-user-graduate' => 'User Graduate' ),
		1210 => array( 'fa fa-user-lock' => 'User Lock' ),
		1211 => array( 'fa fa-user-md' => 'user-md' ),
		1212 => array( 'fa fa-user-minus' => 'User Minus' ),
		1213 => array( 'fa fa-user-ninja' => 'User Ninja' ),
		1214 => array( 'fa fa-user-plus' => 'Add User' ),
		1215 => array( 'fa fa-user-secret' => 'User Secret' ),
		1216 => array( 'fa fa-user-shield' => 'User Shield' ),
		1217 => array( 'fa fa-user-slash' => 'User Slash' ),
		1218 => array( 'fa fa-user-tag' => 'User Tag' ),
		1219 => array( 'fa fa-user-tie' => 'User Tie' ),
		1220 => array( 'fa fa-user-times' => 'Remove User' ),
		1221 => array( 'fa fa-users' => 'Users' ),
		1222 => array( 'fa fa-users-cog' => 'Users Cog' ),
		1223 => array( 'fab fa-ussunnah' => 'us-Sunnah Foundation' ),
		1224 => array( 'fa fa-utensil-spoon' => 'Utensil Spoon' ),
		1225 => array( 'fa fa-utensils' => 'Utensils' ),
		1226 => array( 'fab fa-vaadin' => 'Vaadin' ),
		1227 => array( 'fa fa-vector-square' => 'Vector Square' ),
		1228 => array( 'fa fa-venus' => 'Venus' ),
		1229 => array( 'fa fa-venus-double' => 'Venus Double' ),
		1230 => array( 'fa fa-venus-mars' => 'Venus Mars' ),
		1231 => array( 'fab fa-viacoin' => 'Viacoin' ),
		1232 => array( 'fab fa-viadeo' => 'Viadeo' ),
		1233 => array( 'fab fa-viadeo-square' => 'Viadeo Square' ),
		1234 => array( 'fa fa-vial' => 'Vial' ),
		1235 => array( 'fa fa-vials' => 'Vials' ),
		1236 => array( 'fab fa-viber' => 'Viber' ),
		1237 => array( 'fa fa-video' => 'Video' ),
		1238 => array( 'fa fa-video-slash' => 'Video Slash' ),
		1239 => array( 'fab fa-vimeo' => 'Vimeo' ),
		1240 => array( 'fab fa-vimeo-square' => 'Vimeo Square' ),
		1241 => array( 'fab fa-vimeo-v' => 'Vimeo' ),
		1242 => array( 'fab fa-vine' => 'Vine' ),
		1243 => array( 'fab fa-vk' => 'VK' ),
		1244 => array( 'fab fa-vnv' => 'VNV' ),
		1245 => array( 'fa fa-volleyball-ball' => 'Volleyball Ball' ),
		1246 => array( 'fa fa-volume-down' => 'volume-down' ),
		1247 => array( 'fa fa-volume-off' => 'volume-off' ),
		1248 => array( 'fa fa-volume-up' => 'volume-up' ),
		1249 => array( 'fab fa-vuejs' => 'Vue.js' ),
		1250 => array( 'fa fa-walking' => 'Walking' ),
		1251 => array( 'fa fa-wallet' => 'Wallet' ),
		1252 => array( 'fa fa-warehouse' => 'Warehouse' ),
		1253 => array( 'fab fa-weebly' => 'Weebly' ),
		1254 => array( 'fab fa-weibo' => 'Weibo' ),
		1255 => array( 'fa fa-weight' => 'Weight' ),
		1256 => array( 'fa fa-weight-hanging' => 'Hanging Weight' ),
		1257 => array( 'fab fa-weixin' => 'Weixin (WeChat)' ),
		1258 => array( 'fab fa-whatsapp' => 'What\'s App' ),
		1259 => array( 'fab fa-whatsapp-square' => 'What\'s App Square' ),
		1260 => array( 'fa fa-wheelchair' => 'Wheelchair' ),
		1261 => array( 'fab fa-whmcs' => 'WHMCS' ),
		1262 => array( 'fa fa-wifi' => 'WiFi' ),
		1263 => array( 'fab fa-wikipedia-w' => 'Wikipedia W' ),
		1264 => array( 'fa fa-window-close' => 'Window Close' ),
		1265 => array( 'far fa-fa fa-window-close' => 'Window Close' ),
		1266 => array( 'fa fa-window-maximize' => 'Window Maximize' ),
		1267 => array( 'far fa-fa fa-window-maximize' => 'Window Maximize' ),
		1268 => array( 'fa fa-window-minimize' => 'Window Minimize' ),
		1269 => array( 'far fa-fa fa-window-minimize' => 'Window Minimize' ),
		1270 => array( 'fa fa-window-restore' => 'Window Restore' ),
		1271 => array( 'far fa-fa fa-window-restore' => 'Window Restore' ),
		1272 => array( 'fab fa-windows' => 'Windows' ),
		1273 => array( 'fa fa-wine-glass' => 'Wine Glass' ),
		1274 => array( 'fa fa-wine-glass-alt' => 'Wine Glass-alt' ),
		1275 => array( 'fab fa-wix' => 'Wix' ),
		1276 => array( 'fab fa-wolf-pack-battalion' => 'Wolf Pack-battalion' ),
		1277 => array( 'fa fa-won-sign' => 'Won Sign' ),
		1278 => array( 'fab fa-wordpress' => 'WordPress Logo' ),
		1279 => array( 'fab fa-wordpress-simple' => 'Wordpress Simple' ),
		1280 => array( 'fab fa-wpbeginner' => 'WPBeginner' ),
		1281 => array( 'fab fa-wpexplorer' => 'WPExplorer' ),
		1282 => array( 'fab fa-wpforms' => 'WPForms' ),
		1283 => array( 'fa fa-wrench' => 'Wrench' ),
		1284 => array( 'fa fa-x-ray' => 'X-Ray' ),
		1285 => array( 'fab fa-xbox' => 'Xbox' ),
		1286 => array( 'fab fa-xing' => 'Xing' ),
		1287 => array( 'fab fa-xing-square' => 'Xing Square' ),
		1288 => array( 'fab fa-y-combinator' => 'Y Combinator' ),
		1289 => array( 'fab fa-yahoo' => 'Yahoo Logo' ),
		1290 => array( 'fab fa-yandex' => 'Yandex' ),
		1291 => array( 'fab fa-yandex-international' => 'Yandex International' ),
		1292 => array( 'fab fa-yelp' => 'Yelp' ),
		1293 => array( 'fa fa-yen-sign' => 'Yen Sign' ),
		1294 => array( 'fab fa-yoast' => 'Yoast' ),
		1295 => array( 'fab fa-youtube' => 'YouTube' ),
		1296 => array( 'fab fa-youtube-square' => 'YouTube Square' ),
		1297 => array( 'fab fa-zhihu' => 'Zhihu' ),
	);
}

function stm_minimize_word( $word, $length = 40, $affix = '...' ) {
	return mb_strimwidth( $word, 0, $length, $affix );
}

function stm_check_time_load() {
	echo esc_html( microtime( true ) - MICROTIME . 's' );
}

function stm_lms_get_terms_with_meta( $meta_key, $taxonomy = null, $args = array() ) {
	if ( empty( $taxonomy ) ) {
		$taxonomy = 'stm_lms_course_taxonomy';
	}

	$term_args = array(
		'taxonomy'   => $taxonomy,
		'hide_empty' => false,
		'fields'     => 'all',
	);

	if ( ! empty( $meta_key ) ) {
		$term_args['meta_key']     = $meta_key;
		$term_args['meta_value']   = '';
		$term_args['meta_compare'] = '!=';
	}

	$term_args = wp_parse_args( $args, $term_args );

	$term_query = new WP_Term_Query( $term_args );

	if ( empty( $term_query->terms ) ) {
		return false;
	}

	return $term_query->terms;
}

function stm_lms_get_layout() {
	return get_option( 'stm_lms_layout', 'default' );
}

function stm_lms_wsl_use_fontawesome_icons( $provider_id, $provider_name, $authenticate_url ) {
	?>
	<a rel="nofollow"
		href="<?php echo esc_url( $authenticate_url ); ?>"
		data-provider="<?php echo esc_attr( $provider_id ); ?>"
		class="wp-social-login-provider wp-social-login-provider-<?php echo esc_attr( strtolower( $provider_id ) ); ?>">
		<span>
			<i class="fab fa-<?php echo esc_attr( strtolower( $provider_id ) ); ?>"></i>
		</span>
	</a>
	<?php
}

add_filter( 'wsl_render_auth_widget_alter_provider_icon_markup', 'stm_lms_wsl_use_fontawesome_icons', 10, 3 );

function stm_lms_get_offline_course_status( $status ) {
	$statuses = array(
		'no_status' => __( 'No Status', 'masterstudy' ),
		'hot'       => __( 'Hot', 'masterstudy' ),
		'special'   => __( 'Special', 'masterstudy' ),
		'new'       => __( 'New', 'masterstudy' ),
	);

	return ( ! empty( $statuses[ $status ] ) ) ? $statuses[ $status ] : '';

}

if ( ! function_exists( 'stm_option' ) ) {
	function stm_option( $id, $fallback = false, $key = false ) {
		global $stm_option;
		if ( is_null( $stm_option ) ) {
			$stm_option = stm_get_default_option();
		}
		if ( false === $fallback ) {
			$fallback = '';
		}
		$output = ( isset( $stm_option[ $id ] ) && '' !== $stm_option[ $id ] ) ? $stm_option[ $id ] : $fallback;
		if ( ! empty( $stm_option[ $id ] ) && $key ) {
			$output = $stm_option[ $id ][ $key ];
		}

		return $output;
	}
}

function stm_get_default_option() {
	return array(
		'logo'                            => '',
		'logo_transparent'                => '',
		'logo_text_font'                  =>
			array(
				'color'       => '#333',
				'font-family' => 'Montserrat',
				'font-size'   => '23px',
			),
		'logo_width'                      => '253',
		'menu_top_margin'                 => '5',
		'main_paddings'                   => '',
		'preloader'                       => false,
		'favicon'                         => '',
		'header_style'                    => 'header_default',
		'sticky_header'                   => false,
		'online_show_wpml'                => true,
		'online_show_socials'             => true,
		'online_show_search'              => true,
		'online_show_links'               => true,
		'header_course_categories'        => '',
		'header_course_categories_online' => '',
		'top_bar'                         => false,
		'top_bar_login'                   => true,
		'top_bar_social'                  => true,
		'top_bar_wpml'                    => true,
		'top_bar_color'                   => '#333333',
		'font_top_bar'                    =>
			array(
				'color'       => '#aaaaaa',
				'font-family' => 'Montserrat',
				'font-size'   => '12px',
			),
		'top_bar_use_social'              =>
			array(
				'facebook'     => '0',
				'twitter'      => '0',
				'instagram'    => '0',
				'behance'      => '0',
				'dribbble'     => '0',
				'flickr'       => '0',
				'git'          => '0',
				'linkedin'     => '0',
				'pinterest'    => '0',
				'yahoo'        => '0',
				'delicious'    => '0',
				'dropbox'      => '0',
				'reddit'       => '0',
				'soundcloud'   => '0',
				'google'       => '0',
				'google-plus'  => '0',
				'skype'        => '0',
				'youtube'      => '0',
				'youtube-play' => '0',
				'tumblr'       => '0',
				'whatsapp'     => '0',
				'telegram'     => '0',
			),
		'top_bar_address'                 => '',
		'top_bar_working_hours'           => '',
		'top_bar_phone'                   => '',
		'color_skin'                      => '',
		'primary_color'                   => '#eab830',
		'secondary_color'                 => '#48a7d4',
		'link_color'                      => '#48a7d4',
		'button_radius'                   => '0',
		'button_dimensions'               => '',
		'blog_layout'                     => 'grid',
		'blog_sidebar'                    => '655',
		'blog_sidebar_position'           => 'right',
		'teachers_sidebar_position'       => 'none',
		'events_sidebar_position'         => 'none',
		'gallery_sidebar_position'        => 'none',
		'shop_layout'                     => 'grid',
		'shop_sidebar'                    => '740',
		'shop_sidebar_position'           => 'right',
		'currency'                        => 'USD',
		'paypal_mode'                     => 'sand',
		'admin_subject'                   => 'New Participant for [event]',
		'admin_message'                   => 'A new member wants to join your [event]<br>Participant Info:<br>Name: [name]<br>Email: [email]<br>Phone: [phone]<br>Message: [message]',
		'user_subject'                    => 'Confirmation of your pariticipation in the [event]',
		'user_message'                    => 'Dear [name].<br/> This email is sent to you to confirm your participation in the event.<br/>We will contact you soon with further details.<br>With any question, feel free to phone +999999999999 or write to <a href="mailto:timur@stylemix.net">timur@stylemix.net</a>.<br>Regards,<br>MasterStudy Team.',
		'font_body'                       =>
			array(
				'color'       => '#555555',
				'font-family' => 'Open Sans',
				'font-size'   => '14px',
			),
		'font_btn'                        =>
			array(
				'font-family' => 'Montserrat',
				'font-size'   => '14px',
			),
		'menu_heading'                    =>
			array(
				'color'       => '#fff',
				'font-family' => 'Montserrat',
				'font-weight' => '900',
			),
		'font_heading'                    =>
			array(
				'color'       => '#333333',
				'font-family' => 'Montserrat',
			),
		'h1_params'                       =>
			array(
				'font-size'   => '50px',
				'font-weight' => '700',
			),
		'h1_dimensions'                   => '',
		'h2_params'                       =>
			array(
				'font-size'   => '32px',
				'font-weight' => '700',
			),
		'h2_dimensions'                   => '',
		'h3_params'                       =>
			array(
				'font-size'   => '18px',
				'font-weight' => '700',
			),
		'h3_dimensions'                   => '',
		'h4_params'                       =>
			array(
				'font-size'   => '16px',
				'font-weight' => '400',
			),
		'h4_dimensions'                   => '',
		'h5_params'                       =>
			array(
				'font-size'   => '14px',
				'font-weight' => '700',
			),
		'h5_dimensions'                   => '',
		'h6_params'                       =>
			array(
				'font-size'   => '12px',
				'font-weight' => '400',
			),
		'h6_dimensions'                   => '',
		'footer_top'                      => true,
		'footer_top_color'                => '#414b4f',
		'footer_first_columns'            => '4',
		'footer_bottom'                   => false,
		'footer_bottom_color'             => '#414b4f',
		'footer_bottom_title_uppercase'   => true,
		'footer_bottom_text_color'        => '#fff',
		'footer_bottom_columns'           => '4',
		'footer_bottom_socials'           => 'none',
		'footer_copyright'                => true,
		'footer_copyright_bg_color'       => '#5e676b',
		'footer_copyright_text_color'     => '#fff',
		'footer_copyright_border_color'   => '#5e676b',
		'footer_logo_enabled'             => true,
		'footer_logo'                     =>
			array(
				'url' => 'http://lms.loc/unittest/wp-content/themes/masterstudy/assets/img/tmp/footer-logo2x.png',
			),
		'footer_copyright_text'           => 'Copyright &copy; <a target="_blank" href="https://stylemixthemes.com/masterstudy/">MasterStudy</a> Theme for WordPress by <a target="_blank" href="https://www.stylemixthemes.com/">StylemixThemes</a>',
		'copyright_use_social'            =>
			array(
				'facebook'     => '0',
				'twitter'      => '0',
				'instagram'    => '0',
				'behance'      => '0',
				'dribbble'     => '0',
				'flickr'       => '0',
				'git'          => '0',
				'linkedin'     => '0',
				'pinterest'    => '0',
				'yahoo'        => '0',
				'delicious'    => '0',
				'dropbox'      => '0',
				'reddit'       => '0',
				'soundcloud'   => '0',
				'google'       => '0',
				'google-plus'  => '0',
				'skype'        => '0',
				'youtube'      => '0',
				'youtube-play' => '0',
				'tumblr'       => '0',
				'whatsapp'     => '0',
			),
		'facebook'                        => 'https://www.facebook.com/',
		'twitter'                         => 'https://www.twitter.com/',
		'instagram'                       => 'https://www.instagram.com/',
		'stm_social_widget_sort'          => array(
			'facebook'     => 'Facebook',
			'twitter'      => 'Twitter',
			'instagram'    => 'Instagram',
			'behance'      => 'Behance',
			'dribbble'     => 'Dribbble',
			'flickr'       => 'Flickr',
			'git'          => 'Git',
			'linkedin'     => 'Linkedin',
			'pinterest'    => 'Pinterest',
			'yahoo'        => 'Yahoo',
			'delicious'    => 'Delicious',
			'dropbox'      => 'Dropbox',
			'reddit'       => 'Reddit',
			'soundcloud'   => 'Soundcloud',
			'google'       => 'Google',
			'google-plus'  => 'Google +',
			'skype'        => 'Skype',
			'youtube'      => 'Youtube',
			'youtube-play' => 'Youtube Play',
			'tumblr'       => 'Tumblr',
			'whatsapp'     => 'Whatsapp',
			'telegram'     => 'Telegram',
		),
	);
}

/*All Theme check INFO in one place*/
function stm_include_file( $file, $view = 'false' ) {
	require_once $file;
}

function masterstudy_custom_styles_url( $main = false, $get_dir = false ) {
	$upload     = wp_upload_dir();
	$upload_url = $upload['baseurl'];
	if ( is_ssl() ) {
		$upload_url = str_replace( 'http://', 'https://', $upload_url );
	}
	if ( $get_dir ) {
		$upload_url = $upload['basedir'];
	}
	$parts = ( ! $main ) ? 'parts/' : '';

	return $upload_url . "/stm_lms_styles/{$parts}";
}

function masterstudy_lazyload_image( $image ) {
	if ( ! function_exists( 'stm_conf_layload_image' ) ) {
		return $image;
	}

	return stm_conf_layload_image( $image );
}

add_action( 'masterstudy_before_header', 'masterstudy_left_bar' );
function masterstudy_left_bar() {
	if ( stm_option( 'left_bar' ) ) {
		get_template_part( 'partials/headers/parts/left_bar' );
	}
}

function stm_get_tgm_plugin_path( $plugin_slug, $wp_repository = false ) {
	$is_dev_mode = defined( 'STM_DEV_MODE' ) && STM_DEV_MODE === true;

	/*DEV mode is off and we have WP Repository*/
	if ( ! $is_dev_mode && $wp_repository ) {
		return null;
	}

	/*DEV mode is off and is not a WP Repository*/
	if ( ! $is_dev_mode && ! $wp_repository ) {
		return get_package( $plugin_slug, 'zip' );
	}

	/*Only dev mode now*/
	$plugins_path = get_template_directory() . '/inc/tgm/plugins';
	$plugins_path = "{$plugins_path}/{$plugin_slug}.zip";

	/*DEV mode is on but no plugin uploaded locally */
	if ( defined( 'STM_DEV_MODE' ) && ! file_exists( $plugins_path ) ) {
		return ! $wp_repository ? get_package( $plugin_slug, 'zip' ) : null;
	}

	/*So we have this plugin locally*/

	return $plugins_path;

}


function masterstudy_hex2rgb( $color ) {
	$color = str_replace( '#', '', $color );

	if ( strlen( $color ) === 6 ) {
		list( $r, $g, $b ) = array(
			$color[0] . $color[1],
			$color[2] . $color[3],
			$color[4] . $color[5],
		);
	} elseif ( strlen( $color ) === 3 ) {
		list( $r, $g, $b ) = array(
			$color[0] . $color[0],
			$color[1] . $color[1],
			$color[2] . $color[2],
		);
	} else {
		return false;
	}

	$r = hexdec( $r );
	$g = hexdec( $g );
	$b = hexdec( $b );

	return array(
		$r,
		$g,
		$b,
	);
}

if ( ! function_exists( 'stm_lms_get_term_meta_text' ) ) {
	function stm_lms_get_term_meta_text( $term_id, $term_key ) {
		$value = get_term_meta( $term_id, $term_key, true );
		$value = sanitize_text_field( $value );

		return $value;
	}
}

function masterstudy_event_price_format( $price ) {
	$symbol          = stm_option( 'event_currency_symbol', '$' );
	$symbol_position = stm_option( 'event_currency_symbol_position', 'left' );

	return ( 'right' === $symbol_position ) ? "{$price}{$symbol}" : "{$symbol}{$price}";
}

if ( defined( 'ELEMENTOR_VERSION' ) ) {
	// Custom icons for Elementor
	function masterstudy_theme_icons( $tabs = array() ) {
		$new_icons['masterstudy-theme-icons'] = array(
			'name'          => 'masterstudy-icons',
			'label'         => 'Masterstudy Icons',
			'url'           => '',
			'enqueue'       => '',
			'prefix'        => '',
			'displayPrefix' => '',
			'labelIcon'     => 'fa-icon-stm_icon_ms_logo',
			'ver'           => '0.1.0',
			'fetchJson'     => get_template_directory_uri() . '/assets/layout_icons/elementor-icons.json',
		);

		return array_merge( $tabs, $new_icons );
	}

	add_action( 'elementor/icons_manager/additional_tabs', 'masterstudy_theme_icons', 9999999, 1 );

	function masterstudy_icons_font() {
		wp_enqueue_style( 'font-icomoon', get_template_directory_uri() . '/assets/css/icomoon.fonts.css', null, STM_THEME_VERSION, 'all' );
		wp_enqueue_style( 'language_center', get_template_directory_uri() . '/assets/layout_icons/language_center/style.css', null, STM_THEME_VERSION, 'all' );
		wp_enqueue_style( 'rtl_demo', get_template_directory_uri() . '/assets/css/rtl_demo/style.css', null, STM_THEME_VERSION, 'all' );
	}

	add_action( 'elementor/editor/before_enqueue_scripts', 'masterstudy_icons_font', 99999, 1 );

}

if ( ! function_exists( 'masterstudy_substr_text' ) ) {
	function masterstudy_substr_text( $lenght, $description = '' ) {
		if ( strlen( $description ) > $lenght ) {
			$description  = substr( $description, 0, strpos( $description, ' ', $lenght ) );
			$description .= '...';
		}

		return $description;
	}
}

function stm_masterstudy_allowed_html() {
	$allowed_html           = wp_kses_allowed_html( 'post' );
	$allowed_html['iframe'] = array(
		'autoplay'        => 1,
		'src'             => 1,
		'width'           => 1,
		'height'          => 1,
		'class'           => 1,
		'style'           => 1,
		'muted'           => 1,
		'loop'            => 1,
		'allowfullscreen' => array(),
		'allow'           => array(),
	);

	return apply_filters( 'stm_masterstudy_allowed_html', $allowed_html );
}
