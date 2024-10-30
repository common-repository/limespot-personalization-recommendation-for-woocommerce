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

$data = '';
if (isset($_POST) && !empty($_POST)) {
  $data = $_POST;
  $postData = stripcslashes($data['data']);
  $postDataArray = json_decode($postData);
  $_SESSION['postDataArray'] = $postDataArray;
  $postDataArray = '';
}

get_header('shop');

global $paged;
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$args = array('post__in'=>$_SESSION['postDataArray'], 'orderby'=>'post__in', 'post_type'=>'product', 'post_status'=>array('publish','draft','trash'), 'paged'=>$paged);
$loop = new WP_Query( $args );

do_action( 'woocommerce_before_main_content' );

if (apply_filters('woocommerce_show_page_title', true)) {
?>
<h1 class="page-title">Top Picks for You</h1>
<?php
}

include('result-count.php');

if ($loop->have_posts()) {

	do_action( 'woocommerce_before_shop_loop' );

	woocommerce_product_loop_start();
	woocommerce_product_subcategories();

	while ($loop->have_posts()) {
		$loop->the_post();
		wc_get_template_part('content', 'product');
	}

	woocommerce_product_loop_end();

	do_action('woocommerce_after_shop_loop');

} elseif (!woocommerce_product_subcategories(array('before' => woocommerce_product_loop_start(false), 'after' => woocommerce_product_loop_end(false)))) {
	wc_get_template( 'loop/no-products-found.php' );
}

include('pagination.php');

do_action( 'woocommerce_after_main_content' );

do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );
?>
<script type="text/javascript">
	jQuery(document).ready(function () {
		jQuery(document.body).addClass('woocommerce-page');
		jQuery(".entry-header").hide();
		jQuery(".woocommerce-breadcrumb").css('padding-top', '0px');
		jQuery("#main-content").removeClass();
		jQuery("#content").first().removeClass();
		jQuery("#primary").first().removeClass();
		jQuery("#comments").hide();
		jQuery(".entry-meta").hide();
		jQuery(".post-title").hide();
		jQuery(".post-meta").hide();
		jQuery("#reply-title").hide();
		jQuery("#reviews").hide();
		jQuery('#content').find('div').width('100%');
	});
</script>
