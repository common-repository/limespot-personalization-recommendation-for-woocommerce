<?php
/*
Plugin Name: LimeSpot Personalization/Recommendation for WooCommerce
Description: LimeSpot's patent-pending technology creates Intelligent Recommendations that can be placed on different pages of your store and showcase the products your customers are most likely to buy.
Version: 1.0.1
Author: LimeSpot
Author URI: https://limespot.com/
Category: Wordpress Extension By LimeSpot Solutions Inc.
Copyright: Copyright 2016 LimeSpot Solutions Inc. All Rights Reserved.
 */

function limespot_activate() {
	include('config.php');
	include('includes/activate.php');
}
register_activation_hook(__FILE__, 'limespot_activate');

function limespot_add_menu() {
	//Add Option In Admin Menu
	add_menu_page(':: LS ::','LimeSpot', 'manage_options', 'mt-top-level-handle', 'limespot_get_page_content', plugins_url('/images/logo-menu.png', __FILE__));
}
add_action('admin_menu', 'limespot_add_menu');

function limespot_get_page_content($user) {
	//Include Variable File
	include('config.php');

	global $wpdb;
	$installationInfoRow = $wpdb->get_row("SELECT `AdministrationUrl` FROM " . $wpdb->prefix . LIMESPOT_CONFIG_TABLE_NAME);

	if (isset($installationInfoRow->AdministrationUrl)) {
		$adminUrl = $installationInfoRow->AdministrationUrl;
		include('includes/admin-container.php');
	} else {
		include('includes/signup.php');
	}
}

function limespot_enqueue_storefront_library() {
	include('config.php');
	global $wpdb;
	$row = $wpdb->get_row("SELECT `StorefrontIncludeUrl` FROM " . $wpdb->prefix . LIMESPOT_CONFIG_TABLE_NAME);
	if (isset($row->StorefrontIncludeUrl)) {
		wp_enqueue_script('limespot-storefront', $row->StorefrontIncludeUrl);
	}
}
add_action('wp_enqueue_scripts', 'limespot_enqueue_storefront_library');

function limespot_render_page_info() {
	$pageType = "";
	$referenceIdentifier = "";

	if (is_shop() || $_SERVER['REQUEST_URI'] == "/")
		$pageType = "Home";
	elseif (is_product()) {
		$pageType = "Product";
		$referenceIdentifier = get_the_ID();
	} elseif (is_product_category()) {
		$pageType = "Collection";
		$referenceIdentifier = get_queried_object()->term_id;
	} elseif (is_cart()) {
		$pageType = "Cart";
	}

	if ($pageType != "") {
?>
<script>
	LimeSpot.PageInfo = {
		Type: "<?php echo $pageType; ?>",
		ReferenceIdentifier: "<?php echo $referenceIdentifier; ?>"
	};
</script>
<?php
	}
}
add_action('wp_head', 'limespot_render_page_info');

function limespot_show_toppicks() {
	include('includes/toppicks.php');
}
add_shortcode('limespot_toppicks', 'limespot_show_toppicks');
?>