<?php
/**
 * @var $icon_align
 * @var $box_align
 * @var $icon_height
 * @var $icon_width
 * @var $icon_width
 * @var $link_color_style
 * @var $css_icon
 * @var $box_bg_color
 * @var $box_text_color
 * @var $title_holder
 * @var $icon_size
 * @var $icon_color
 */

$inline_css = '';
$library    = $library ?? '';

if ( ! empty( $padding_icon_box ) && is_array( $padding_icon_box ) ) {
	if ( '' === $padding_icon_box['top'] && '' === $padding_icon_box['right'] && '' === $padding_icon_box['bottom'] && '' === $padding_icon_box['left'] ) {
		$inline_css .= "
            body:not(.home) .icon_box.{$unique} {
                padding: 0;
            }
        ";
	}
}

$icon_inline_css = ( 'center' === $icon_align || 'top_left' === $icon_align ) ? 'height:' . esc_attr( $icon_height ) . 'px;' : 'width:' . esc_attr( $icon_width ) . 'px;';

if ( ! empty( $box_icon_bg_color ) ) {
	$icon_inline_css .= " background-color: {$box_icon_bg_color};";
}

$inline_css .= ".{$unique} {
    background:{$box_bg_color} !important; color:{$box_text_color};
}

.{$unique} .icon {
    {$icon_inline_css}
}
.{$unique} .icon svg {
	width: {$icon_size}px;
	height: {$icon_size}px;
}
.{$unique} .icon svg,
.{$unique} .icon i {
    font-size: {$icon_size}px;
    color: {$icon_color} !important;
}
.icon_alignment_top_left .{$unique} {
    text-align: left;
}
.icon_alignment_top_center .{$unique} {
    text-align: center;
    margin-right: auto;
    margin-left: auto;
}
.icon_alignment_top_right .{$unique} {
    text-align: right;
    margin-right: 0;
    margin-left: auto;
}
";

$css_class .= ' stm_icon_box_hover_' . $hover_pos;

stm_module_styles( 'iconbox', 'style_1', array(), $inline_css );

echo '<style type="text/css">' . $inline_css . '</style>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

$icon_b_classes = array( $css_class, $link_color_style, $unique, "box_align_{$box_align}", 'clearfix' );

?>

<?php if ( ! empty( $link['url'] ) ) : ?>
	<a	
		href="<?php echo esc_url( $link['url'] ); ?>" 
		title="<?php echo ( ! empty( $link['title'] ) ? esc_attr( $link['title'] ) : '' ); ?>"
		<?php if ( ! empty( $link['target'] ) ) : ?> 
			target="_blank"
		<?php endif; ?>
	>
<?php endif; ?>

	<div class="icon_box<?php echo esc_attr( implode( ' ', $icon_b_classes ) ); ?>">
		<div class="icon_alignment_<?php echo esc_attr( $icon_align ); ?>">
			<?php if ( $icon ) { ?>
				<div class="icon<?php echo esc_attr( $css_icon_class ); ?>">
					<?php if ( 'svg' === $library ) : ?>
						<?php echo '<span>' . $icon . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php else : ?>
						<i class="<?php echo esc_attr( $icon ); ?>"></i>
					<?php endif; ?>
				</div>
			<?php } ?>

			<div class="icon_text">
				<?php if ( $title ) { ?>
					<<?php echo esc_attr( $title_holder ); ?> style="color:<?php echo esc_attr( $box_text_color ); ?>">
						<?php echo esc_html( $title ); ?>
					</<?php echo esc_attr( $title_holder ); ?>>
				<?php } ?>
				<?php echo ( function_exists( 'wpb_js_remove_wpautop' ) ) ? wpb_js_remove_wpautop( $content, true ) : '<p>' . $content . '</p>';  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div> <!-- align icons -->
	</div>

<?php if ( ! empty( $link['url'] ) ) : ?>
	</a>
<?php endif; ?>
