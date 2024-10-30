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

global $wpdb;
$table_name = $wpdb->prefix . LIMESPOT_CONFIG_TABLE_NAME;
$sql =
	"CREATE TABLE IF NOT EXISTS $table_name (
		`AdministrationUrl` varchar(1024) DEFAULT NULL,
		`StorefrontIncludeUrl` varchar(1024) DEFAULT NULL,
		`TopPicksPageID` int DEFAULT NULL
	);";
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);
?>