<?php
/**
 * LimeSpot Personalization/Recommendation for WooCommerce
 *
 * @package     LimeSpot Personalization/Recommendation for WooCommerce
 * @category    WooCommerce Plugin By LimeSpot Solutions Inc.
 * @copyright   Copyright 2016 LimeSpot Solutions Inc. All Rights Reserved.
 * @version     1.0.0
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>
<p class="woocommerce-result-count">
    <?php
		$paged    = max( 1, $paged );
		$per_page = $loop->get( 'posts_per_page' );
		$total    = $loop->found_posts;
		$first    = ( $per_page * $paged ) - $per_page + 1;
		$last     = min( $total, $loop->get( 'posts_per_page' ) * $paged );

		if ( 1 == $total ) {
			_e( 'Showing the single result', 'woocommerce' );
		} elseif ( $total <= $per_page || -1 == $per_page ) {
			printf( __( 'Showing all %d results', 'woocommerce' ), $total );
		} else {
			printf( _x( 'Showing %1$d&ndash;%2$d of %3$d results', '%1$d = first, %2$d = last, %3$d = total', 'woocommerce' ), $first, $last, $total );
		}
		?>
</p>
