=== Kmetric for Woocommerce ===
Contributors: KogiTech
Donate link: https://www.kmetric.io/
Tags: kmetric, woocommerce, tracking, analytics
Requires at least: 3.8
Tested up to: 5.1
Stable tag: 5.1
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Provides integration between Kmetric Analytics and WooCommerce.

== Description ==

This plugin provides the integration between Kmetric Analytics and the WooCommerce plugin. You can link a referral to a purchase and add transaction information to your Kmetric Analytics data. It also supports the new Universal Analytics, eCommerce, and enhanced eCommerce event tracking.

Contributions are welcome via the [GitHub repository](https://github.com/kogi-team/kmetric-woocommerce-plugin).

== Installation ==

1. Download the plugin file to your computer and unzip it
2. Using an FTP program, or your hosting control panel, upload the unzipped plugin folder to your WordPress installation’s wp-content/plugins/ directory.
3. Activate the plugin from the Plugins menu within the WordPress admin.
4. Don't forget to enable e-commerce tracking in your Kmetric Analytics account: https://www.kmetric.io/docs/getting-started

Or use the automatic installation wizard through your admin panel, just search for this plugins name.

== Frequently Asked Questions ==

= Where can I find the setting for this plugin? =

This plugin will add the settings to the Settings tab, to be found in the Settings > Kmetric Tracking menu.

= I don't see the code on my site. Where is it? =

We purposefully don't track admin visits to the site. Log out of the site (or open a Google Chrome Incognito window) and check if the site is there for non-admins.

Also please make sure your Kmetric Product ID under Settings > Kmetric Tracking.

= My code is there. Why is it still not tracking sales?  =

Duplicate Kmetric Analytics code causes a conflict in tracking. Remove any other Kmetric Analytics plugin or code from your site to avoid duplication and conflicts in tracking.

== Screenshots ==

1. Kmetric - Homepage.
2. Kmetric - Create new product.
3. Kmetric - The snippet of Javascript code.
4. Kmetric - Wordpress Integration Settings.

== Changelog ==

= 1.0 =
* Init plugin
