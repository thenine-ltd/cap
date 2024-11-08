<?php
/**
 * @var string $post_id
 *
 * data-masterstudy-modal="masterstudy-group-courses-modal" - js trigger
 */

$data = apply_filters( 'masterstudy_group_courses_modal_data', $post_id );

if ( empty( $data['theme_fonts'] ) ) {
	wp_enqueue_style( 'masterstudy-buy-button-group-courses-fonts' );
}

wp_enqueue_style( 'masterstudy-group-course' );
wp_enqueue_script( 'masterstudy-group-course-trigger' );
wp_enqueue_script( 'masterstudy-group-course-add-group' );
wp_enqueue_script( 'masterstudy-group-course-add-to-cart' );
?>
<div class="masterstudy-group-courses-modal">
	<div class="masterstudy-group-courses-modal__wrapper">
		<div class="masterstudy-group-courses-modal__header">
			<span class="masterstudy-group-courses-modal__header-title-back stmlms-arrow_left <?php echo ( empty( $data['groups'] ) ) ? '' : 'has-group'; ?>"></span>
			<h2 class="masterstudy-group-courses-modal__header-title"
				data-default-text="<?php esc_html_e( 'Buy for group', 'masterstudy-lms-learning-management-system-pro' ); ?>"
				data-second-text="<?php esc_html_e( 'New group', 'masterstudy-lms-learning-management-system-pro' ); ?>">
				<?php esc_html_e( 'Buy for group', 'masterstudy-lms-learning-management-system-pro' ); ?>
			</h2>
			<span class="masterstudy-group-courses-modal__header-title-close stmlms-close"></span>
		</div>
		<div class="masterstudy-group-courses-modal__content">
			<div class="masterstudy-group-courses__name">
				<?php echo esc_html( get_the_title( $data['post_id'] ) ); ?>
			</div>
			<?php if ( empty( $data['groups'] ) ) : ?>
			<div class="masterstudy-group-courses__start">
				<div class="masterstudy-group-courses__start-icon-wrapper">
					<span class="masterstudy-group-courses__start-icon"></span>
				</div>
				<div class="masterstudy-group-courses__start-title"><?php echo esc_html__( "You don't have groups yet", 'masterstudy-lms-learning-management-system-pro' ); ?></div>
				<div class="masterstudy-group-courses__start-description">
					<?php echo esc_html__( 'Create group and add group members', 'masterstudy-lms-learning-management-system-pro' ); ?>
				</div>
				<?php
				STM_LMS_Templates::show_lms_template(
					'components/button',
					array(
						'title' => __( 'Create group', 'masterstudy-lms-learning-management-system-pro' ),
						'link'  => '#',
						'style' => 'primary masterstudy-group-courses__create-group',
						'size'  => 'sm',
					)
				);
				?>
			</div>
			<?php endif; ?>
			<div class="masterstudy-group-courses__list" <?php echo empty( $data['groups'] ) ? 'style="display: none"' : ''; ?>>
				<div class="masterstudy-group-courses__list-header">
					<div class="masterstudy-group-courses__list-header_title"><?php echo esc_html__( 'Choose group', 'masterstudy-lms-learning-management-system-pro' ); ?></div>
					<?php
					STM_LMS_Templates::show_lms_template(
						'components/button',
						array(
							'title' => __( 'Add new group', 'masterstudy-lms-learning-management-system-pro' ),
							'link'  => '#',
							'style' => 'tertiary masterstudy-group-courses__create-group',
							'size'  => 'sm',
						)
					);
					?>
				</div>
				<div class="masterstudy-group-courses__list-wrap">
				<?php
				foreach ( $data['groups'] as $group ) :
					$user_course = stm_lms_get_user_course( $data['user_id'], $data['post_id'], array( 'start_time' ), $group['group_id'] );
					$class       = ! empty( $user_course ) ? 'masterstudy-group-courses__list-item_selected active' : 'masterstudy-group-courses__list-item';
					$count       = count( $group['emails'] );
					$member      = $count . ( 1 === $count ? esc_html__( ' member', 'masterstudy-lms-learning-management-system-pro' ) : esc_html__( ' members', 'masterstudy-lms-learning-management-system-pro' ) );
					?>
					<div class="<?php echo esc_attr( $class ); ?>" data-masterstudy-group-courses-group-id="<?php echo esc_attr( intval( $group['group_id'] ) ); ?>">
						<div class="masterstudy-group-courses__list-item_title">
							<div class="masterstudy-group-courses__list-item_checkbox"></div>
							<?php echo esc_html( $group['title'] ); ?>
						</div>
						<div class="masterstudy-group-courses__list-item_members">
							<?php echo esc_html( $member ); ?>
						</div>
					</div>
				<?php endforeach; ?>
				</div>
				<div class="masterstudy-group-courses__list-loading" style="display: none;">
					<?php
					STM_LMS_Templates::show_lms_template(
						'components/loader',
						array(
							'dark_mode' => false,
							'is_local'  => true,
						)
					);
					?>
				</div>
			</div>
			<div class="masterstudy-group-courses__actions" <?php echo empty( $data['groups'] ) ? 'style="display: none"' : ''; ?>>
				<a href="#" data-course-id="<?php echo intval( $data['post_id'] ); ?>" class="masterstudy-group-courses__actions-button masterstudy-group-courses__actions-button-cart disable" data-masterstudy-group-courses-price="<?php echo esc_attr( $data['price'] ); ?>">
					<?php
					printf(
						/* translators: %s Price */
						esc_html__( 'Add to cart %s', 'masterstudy-lms-learning-management-system-pro' ),
						'<span>' . esc_html( STM_LMS_Helpers::display_price( '0' ) ) . '</span>'
					);
					?>
				</a>
			</div>
			<div class="masterstudy-group-courses__addition">
				<div class="masterstudy-group-courses__addition-list" data-max-group="<?php echo esc_attr( STM_LMS_Enterprise_Courses::get_group_common_limit() ); ?>">
					<label>
						<span class="masterstudy-group-courses__addition-list_title"><?php echo esc_html__( 'Group name', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
						<input type="text" placeholder="<?php echo esc_html__( 'Enter group name', 'masterstudy-lms-learning-management-system-pro' ); ?>" name="group_name" id="masterstudy-group-courses__group-name" />
					</label>
					<label>
						<span class="masterstudy-group-courses__addition-list_title">
							<?php
							printf(
								/* translators: %s Group Limit */
								esc_html__( 'Add student emails (Max : %s)', 'masterstudy-lms-learning-management-system-pro' ),
								esc_html( STM_LMS_Enterprise_Courses::get_group_common_limit() )
							);
							?>
						</span>
						<input type="text" placeholder="<?php esc_attr_e( 'Enter student email here', 'masterstudy-lms-learning-management-system-pro' ); ?>" name="group_emails" id="masterstudy-group-courses__group-email" />
						<span class="masterstudy-group-courses__addition-list_add_email"><?php echo esc_html__( 'Add', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
					</label>
					<div class="masterstudy-group-courses__addition-list_emails" data-member="<?php echo esc_html__( ' member', 'masterstudy-lms-learning-management-system-pro' ); ?>" data-members="<?php echo esc_html__( ' members', 'masterstudy-lms-learning-management-system-pro' ); ?>"></div>
					<?php
					STM_LMS_Templates::show_lms_template(
						'components/button',
						array(
							'title' => __( 'Create group', 'masterstudy-lms-learning-management-system-pro' ),
							'link'  => '#',
							'style' => 'primary masterstudy-group-courses__add-group',
							'size'  => 'sm',
						)
					);
					?>
					<div class="masterstudy-group-courses__error">
						<div class="masterstudy-group-courses__error-group-name"><?php echo esc_html__( 'Specify group name', 'masterstudy-lms-learning-management-system-pro' ); ?></div>
						<div class="masterstudy-group-courses__error-user-email"><?php echo esc_html__( 'Specify student email', 'masterstudy-lms-learning-management-system-pro' ); ?></div>
						<div class="masterstudy-group-courses__error-limit-email"><?php echo esc_html__( 'You have reached the maximum limit', 'masterstudy-lms-learning-management-system-pro' ); ?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="masterstudy-group-courses-modal__close"></div>
</div>
