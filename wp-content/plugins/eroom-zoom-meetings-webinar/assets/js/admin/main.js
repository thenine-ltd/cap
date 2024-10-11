(function ($) {
	'use strict';
	$( document ).ready(
		function () {
			var classes = [
			'post-type-stm-zoom-webinar',
			];

			if ($( 'body' ).is( "." + classes.join( ', .' ) )) {
				$( '#adminmenu > li' ).removeClass( 'wp-has-current-submenu wp-menu-open' );

				$( '#toplevel_page_stm_zoom' )
				.addClass( 'wp-has-current-submenu wp-menu-open' )
				.removeClass( 'wp-not-current-submenu' );

				$( '.toplevel_page_stm_zoom' )
				.addClass( 'wp-has-current-submenu' )
				.removeClass( 'wp-not-current-submenu' );
			}

			/**
			 * Feedback Modal
			 */
			let body           = 'body';
			let feedback_modal = '#eroom-feedback-modal';

			$( body ).on(
				'click',
				'.eroom-feedback-button',
				function (e) {
					e.preventDefault();
					$( feedback_modal ).fadeIn( 200 );
				}
			);

			$( body ).on(
				'click',
				'.feedback-modal-close',
				function (e) {
					e.preventDefault();
					$( feedback_modal ).fadeOut( 200 );
				}
			);

			$( body ).on(
				'click',
				function ( e ) {
					if ( e.target.id === 'eroom-feedback-modal' ) {
						$( feedback_modal ).fadeOut( 200 );
					}
				}
			);

			/**
			 * Feedback Review
			 */
			$( body ).on(
				'click',
				'#feedback-stars li',
				function (e) {
					var rating = parseInt( $( this ).data( 'value' ), 10 ),
					stars      = $( this ).parent().children( 'li.star' );

					stars.removeClass( 'selected' );

					for ( let i = 0; i < rating; i++ ) {
						$( stars[i] ).addClass( 'selected' );
					}

					$( '.feedback-rating-stars span.rating-text' ).text( $( this ).attr( 'title' ) );
					$( '.feedback-extra' ).toggle( rating < 4 );
					$( '.feedback-submit img' ).toggle( rating > 3 );
				}
			);

			$( body ).on(
				'click',
				'.feedback-submit',
				function (e) {
					var rating = parseInt( $( 'ul#feedback-stars li.selected' ).last().data( 'value' ), 10 ),
					review     = $( '#feedback-review' ).val();

					/** Send Feedback */
					if ( rating < 4 ) {
						e.preventDefault();
						$.ajax(
							{
								url: 'https://panel.stylemixthemes.com/api/item-review',
								dataType: 'json',
								method: 'POST',
								data: {
									'item': 'eroom-zoom-meetings-webinar',
									'type': 'plugin',
									rating,
									review
								},
								success: function(response) {}
							}
						);
					}

					/** Thank You */
					$( 'ul#feedback-stars li' ).addClass( 'disabled' ).prop( 'disabled', true );
					$( feedback_modal ).find( 'h2' ).text( 'Thank You for Feedback' );
					$( feedback_modal ).find( '.feedback-review-text' ).text( review );
					$( '.feedback-review-text, .feedback-thank-you' ).show();
					$( '.feedback-extra, .feedback-submit' ).hide();

					/** Remove Feedback Button */
					$.ajax(
						{
							url: ajaxurl,
							type: 'GET',
							data: 'action=stm_zoom_ajax_add_feedback',
							success: function (data) {
								$( '.eroom-feedback-button' ).remove();
							}
						}
					);
				}
			);

		}
	);
})( jQuery );
