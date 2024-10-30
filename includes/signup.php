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

function lime_rand_hash() {
	$hash = '';
	if (function_exists('openssl_random_pseudo_bytes')) {
		$hash = bin2hex(openssl_random_pseudo_bytes( 20 ));
	} else {
		$hash = sha1(wp_rand());
	}

	return hash_hmac('sha256', $hash, 'wc-api');
}

$error = null;

//Get WP Login Admin Details
$user = wp_get_current_user();
if (!isset($user)) {
	echo "ERROR: Could not retrieve current WordPress administrator user info.";
	exit;
}

$wpUserID = $user->ID;
$adminFullName = $user->display_name;
$adminEmail = $user->user_email;
if (!isset($adminEmail) || !isset($adminFullName)) {
	echo "ERROR: WordPress admin email adress and display name must be set to allow LimeSpot installation.";
	exit;
}

global $wpdb;

$wpdb->delete(
	$wpdb->prefix . "woocommerce_api_keys",
	array("description" => LIMESPOT_WOO_API_KEY_DESCRIPTION),
	array("%s")
);

//Generate Api Keys
$consumer_key    = substr(lime_rand_hash(), 0, 64);
$consumer_secret = substr(lime_rand_hash(), 0, 32);

$wpdb->insert(
	$wpdb->prefix . "woocommerce_api_keys",
	array(
		"user_id" => $wpUserID,
		"description" => LIMESPOT_WOO_API_KEY_DESCRIPTION,
		"permissions" => "read_write",
		"consumer_key" => hash_hmac("sha256", $consumer_key, "wc-api"),
		"consumer_secret" => $consumer_secret,
		"truncated_key" => substr($consumer_key, -7)
	),
	array("%s", "%s", "%s", "%s", "%s", "%s")
);

$storeUrl = home_url();
$website = trim(trim(str_replace(array('http://','https://'), '', $storeUrl), '/'), ' ');

//Call Signup endpoint
$data = array (
	"ConfigSettings" => array (
		"StoreUrl" => $storeUrl,
		"ConsumerKey" => $consumer_key,
		"ConsumerSecret" => $consumer_secret,
		"Handle" => $website,
	),
	"SubscriberSignupForm" => array (
		"SubscriberType" => 1,
		"Title" => get_bloginfo('name'),
		"Website" => $website,
		"CurrencyCode" => get_woocommerce_currency(),
		"TimeZoneTitle" => get_option('gmt_offset'),
		"ContactFullName" => $adminFullName,
		"ContactJobTitle" => NULL,
		"ContactEmail" => $adminEmail,
		"ContactPhone" => NULL,
		"Street" => NULL,
		"City" => NULL,
		"ZipCode" => NULL,
		"State" => NULL,
		"Country" => NULL,
		"AdministratorFullName" => $adminFullName,
		"AdministratorEmail" => $adminEmail
	)
);

$post = json_encode($data);
$ch = curl_init(LIMESPOT_SIGNUP_ENDPOINT_URL);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$resultJson = curl_exec($ch);
//If Any Error
$error = curl_error($ch);
curl_close($ch);
$response = json_decode($resultJson);

//Insert Admin Url To Database
if (isset($response->AdministrationUrl) && $response->AdministrationUrl != "") {

	$topPicksPageID = wp_insert_post(array (
		'post_title'=>'Top Picks',
		'post_content'=>'[limespot_toppicks]',
		'post_status'=>'publish',
		'post_type'=>'page',
		'post_author'=>1,
		'post_date'=>date("Y-m-d"),
	));

	$wpdb->insert(
		$wpdb->prefix . LIMESPOT_CONFIG_TABLE_NAME,
		array (
			"AdministrationUrl" => $response->AdministrationUrl,
			"StorefrontIncludeUrl" => $response->StorefrontIncludeUrl,
			"TopPicksPageID" => $topPicksPageID
		),
		array ("%s", "%s", "%d")
	);
}

if (isset($error) && $error != '') {
	echo "ERROR: " + $error;
	exit;
}

$adminUrl = $response->SetupRedirectUrl;
include('admin-container.php');
?>