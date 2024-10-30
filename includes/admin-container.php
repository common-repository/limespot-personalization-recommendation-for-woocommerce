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
<iframe src="" id="limespot-frame" style="display: none; border: 1px solid grey; height: 425px; margin-top: 1.5%; width: 98%;">
	<p>Your browser does not support iframes.</p>
</iframe>
<div id="td-loader" style="display: none; text-align: center; width: 1000px; margin-top: 1.5%;">
	<img src="<?php echo LIMESPOT_LOADING_ICON_URL ?>" />
</div>
<script>
function resizeLimeSpotFrame() {
	jQuery("#wpfooter").hide();
	jQuery(".update-nag").hide();
	jQuery(".notice").hide();
	jQuery("#wpbody-content").css("padding-bottom", 0);
	jQuery("#limespot-frame").height(jQuery(window).height() - jQuery("#limespot-frame").position().top - 70);
}

jQuery(document).ready(function () {
	jQuery("#error_msg").hide();
	jQuery("#td-loader").hide();

	jQuery("#limespot-frame").prop("src", "<?php echo $adminUrl; ?>");
	jQuery("#limespot-frame").show();

	resizeLimeSpotFrame();
});

jQuery(window).resize(resizeLimeSpotFrame);
resizeLimeSpotFrame();
</script>