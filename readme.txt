=== User Meta Display ===
Contributors: tripflex
Donate link: https://www.gittip.com/tripflex
Tags: user, meta, user meta, display, output, show, users, ajax, jquery, display name, id, user login
Requires at least: 3.8
Tested up to: 3.8
Stable tag: 1.2.0
License: GPLv3

Ajax powered admin page to show, edit, and remove user meta. Choose dropdown list by User Login, ID, or Display Name.

== Description ==

Ajax powered admin page to show, edit, and remove user meta. Choose dropdown list by User Login, ID, or Display Name.

You can select how the display list will show the users, by Display Name, User Login, or ID. Default is Display Name.

Refresh the current users meta being shown, or dropdown list by clicking on Refresh button.

This plugin will create a submenu "User Meta Display" item under the "Users" menu item in your wordpress installation.

User meta is generated via AJAX (doesn't require page reload) and is protected using Wordpress nonce to prevent unauthorized output of user meta.

= Features =

* Display all user meta via Ajax
* Edit user meta via Ajax
* Remove user meta via Ajax
* Add new user meta via Ajax
* Refresh user dropdown list via Ajax
* Refresh displayed user meta via Ajax
* Direct link from WP user list table to view user meta

[Read more about User Meta Display](https://github.com/tripflex/user-meta-display).

= Documentation =

Documentation will be maintained on the [GitHub Wiki here](https://github.com/tripflex/user-meta-display/wiki).

= Contributing and reporting bugs =

You can contribute code and localizations to this plugin via GitHub: [https://github.com/tripflex/user-meta-display](https://github.com/tripflex/user-meta-display)

= Support =

If you spot a bug, you can of course log it on [Github](https://github.com/tripflex/user-meta-display)

Or contact me at myles@smyl.es

== Installation ==

= Automatic installation =

Install through Wordpress, select activate.

= Manual installation =

The manual installation method involves downloading the plugin and uploading it to your webserver via your favourite FTP application.

* Download the plugin file to your computer and unzip it
* Using an FTP program, or your hosting control panel, upload the unzipped plugin folder to your WordPress installation's `wp-content/plugins/` directory.
* Activate the plugin from the Plugins menu within the WordPress admin.

== Screenshots ==

1. User Meta Display Output

== Changelog ==

= 1.2.0 =
- May 11, 2014 -
* Added refresh button for currently displayed user meta
* Added refresh button for dropdown list of users

= 1.1.1 =
- March 25, 2014 -
* fix missing css files

= 1.1.0 = 
- March 25, 2014 -
* Add Ajax loading
* Select user by ID, Display Name, or User Login
* Removed old non-used code

= 1.0.0 = 
- March 17, 2014 -
* Initial Creation
