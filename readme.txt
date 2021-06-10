=== AffiliateWP - Sign Up Bonus ===
Contributors: sumobi, mordauk
Tags: AffiliateWP, affiliate, Pippin Williamson, Andrew Munro, mordauk, pippinsplugins, sumobi, ecommerce, e-commerce, e commerce, selling, membership, referrals, marketing
Requires at least: 3.3
Tested up to: 5.7
Requires PHP: 5.3
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Entice more affiliates to register by offering them a sign up bonus

== Description ==

> This plugin requires [AffiliateWP](http://affiliatewp.com/ "AffiliateWP") in order to function.

Offering a sign up bonus is a great way to entice more affiliates to register on your site.
In turn these affiliates will go on to promote your products and services, making you more sales.

**How it works**

When an affiliate registers through the AffiliateWP registration form, or you
manually add an affiliate from within the admin, a sign up bonus will be awarded
(amount set from within the settings). This bonus is created in the form of a
referral with a description of "Sign Up Bonus", which can be viewed from the
Affiliates &rarr; Referrals screen in the admin. The affiliate will see this on
the "Unpaid Earnings" and "Referrals" sections of their affiliate dashboard.

When "Require approval" is enabled from AffiliateWP's settings, the affiliate
will not receive the sign up bonus until their application is approved.
Likewise, if you manually add an affiliate from within the admin, the sign up
bonus is not created until they are approved.

AffiliateWP v1.7 introduced the ability to manually add an affiliate (from the admin) with
an affiliate status. If the "pending" status is chosen, the sign up bonus will
not be created until the affiliate has been approved.

If you're using AffiliateWP 1.7 or higher you'll also see 2 new fields on the
add affiliate screen:

Sign Up Bonus - Allows you to enable or disable the sign up bonus on a per-affiliate
basis

Sign Up Bonus Amount - Allows you to set the sign up bonus on a per-affiliate basis,
overriding the global sign up bonus setting.

Note: It would be a good idea to include information in your affiliate terms
and conditions about a minimum payout amount, or you'll have affiliates joining
and requesting a payout straight away.

**What is AffiliateWP?**

[AffiliateWP](http://affiliatewp.com/ "AffiliateWP") provides a complete affiliate management system for your WordPress website that seamlessly integrates with all major WordPress e-commerce and membership platforms. It aims to provide everything you need in a simple, clean, easy to use system that you will love to use.

== Installation ==

1. Unpack the entire contents of this plugin zip file into your `wp-content/plugins/` folder locally
1. Upload to your site
1. Navigate to `wp-admin/plugins.php` on your site (your WP Admin plugin page)
1. Activate this plugin

OR you can just install it with WordPress by going to Plugins >> Add New >> and type this plugin's name

Finally, go to Affiliates &rarr; Settings &rarr; Integrations and enter the amount that an Affiliate should receive when they register

== Screenshots ==

== Upgrade Notice ==

Prevented multiple referrals from being created when the affiliate was deactivated and/or activated again from the admin

== Changelog ==

= 1.2 =
* New: Enforce minimum dependency requirements checking
* Improved: Requires PHP 5.6 minimum
* Improved: Requires WordPress 5.0 minimum
* Improved: Requires AffiliateWP 2.6 minimum

= 1.1 =
* Fix: Fixed a bug where referrals were not created due to changes in AffiliateWP v1.7
* New: While adding a new affiliate from the admin you can now choose whether or not they will receive a sign up bonus.
* New: While adding a new affiliate from the admin you can now override the global sign up bonus amount and award them a custom amount.
* New: Added activation script

= 1.0.1 =
* Fix: Prevented multiple referrals from being created when the affiliate was deactivated and/or activated again from the admin

= 1.0 =
* Initial release
