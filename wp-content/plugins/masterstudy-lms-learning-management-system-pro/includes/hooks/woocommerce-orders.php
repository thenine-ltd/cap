<?php

add_filter( 'stm_lms_user_orders', 'stm_lms_user_orders_pro', 10, 4 );

function stm_lms_user_orders_pro( $response, $user_id, $pp, $offset ) {
	$posts     = array();
	$args      = array(
		'post_type'      => wc_get_order_types(),
		'posts_per_page' => $pp,
		'post_status'    => array_keys( wc_get_order_statuses() ),
		'offset'         => $offset,
		'customer_id'    => $user_id,
		'return'         => 'ids',
	);
	$order_ids = wc_get_orders( $args );
	$total     = count( $order_ids );

	if ( ! empty( $order_ids ) ) {
		foreach ( $order_ids as $order_id ) {
			$posts[] = STM_LMS_Order::get_order_info( $order_id );
		}
		wp_reset_postdata();
	}

	return array(
		'total' => $total,
		'posts' => $posts,
	);
}

add_filter( 'stm_lms_order_details', 'stm_lms_order_details_pro', 10, 2 );

function stm_lms_order_details_pro( $order, $order_id ) {
	$order   = new WC_Order( $order_id );
	$user_id = $order->get_user_id();

	return array(
		'user_id'   => $user_id,
		'status'    => $order->get_status(),
		'items'     => get_post_meta( $order_id, 'stm_lms_courses', true ),
		'date'      => strtotime( $order->get_date_created() ),
		'order_key' => "#{$order_id}",
	);
}
