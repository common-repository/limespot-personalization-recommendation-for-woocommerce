<?php
/**
 * LimeSpot Personalization/Recommendation for WooCommerce
 *
 * @package     LimeSpot Personalization/Recommendation for WooCommerce
 * @category    WooCommerce Plugin By LimeSpot Solutions Inc.
 * @copyright   Copyright 2016 LimeSpot Solutions Inc. All Rights Reserved.
 * @version     1.0.1
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

include('config.php');

global $wpdb;

$configTableName = $wpdb->prefix . LIMESPOT_CONFIG_TABLE_NAME;

$topPicksPostRow = $wpdb->get_row("SELECT `TopPicksPageID` FROM " . $configTableName);
if (isset($topPicksPostRow->TopPicksPageID)) {
	wp_delete_post($topPicksPostRow->TopPicksPageID, true);
}

$wpdb->query("DROP TABLE IF EXISTS $configTableName");

$consumerRow = $wpdb->get_row($wpdb->prepare("SELECT consumer_secret FROM " . $wpdb->prefix . "woocommerce_api_keys WHERE `Description` = %s", LIMESPOT_WOO_API_KEY_DESCRIPTION));
if (isset($consumerRow->consumer_secret)) {
	$handle = trim(trim(str_replace(array('http://','https://'), '', home_url()), '/'), ' ');

	$ch = curl_init(LIMESPOT_UNINSTALL_ENDPOINT_URL . "?handle=" . $handle . "&consumerSecret=" . $consumerRow->consumer_secret);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, null);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$resultJson = curl_exec($ch);
	//If Any Error
	$error = curl_error($ch);
	curl_close($ch);

	if (isset($error) && $error != '') {
		echo $error;
	}

	$wpdb->delete(
		$wpdb->prefix . "woocommerce_api_keys",
		array("description" => LIMESPOT_WOO_API_KEY_DESCRIPTION),
		array("%s")
	);
}
?>