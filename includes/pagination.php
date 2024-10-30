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

if ($loop->max_num_pages <= 1) {
	return;
}
?>
<nav class="woocommerce-pagination"><?php
	echo paginate_links(
		apply_filters(
			'woocommerce_pagination_args',
			array(
				'base'      => esc_url( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) ),
				'format'    => '',
				'current'   => max( 1, get_query_var( 'paged' ) ),
				'total'     => $loop->max_num_pages,
				'prev_text' => '&larr;',
				'next_text' => '&rarr;',
				'type'      => 'list',
				'end_size'  => 3,
				'mid_size'  => 3
			)
		)
	);
?></nav>