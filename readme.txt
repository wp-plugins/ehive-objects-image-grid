=== eHive Objects Image Grid ===
Contributors: vernonsystems 
Donate link:http://ehive.com/what_is_ehive
Tags: ehive, collection, museum, archive, history
Requires at least: 3.3.1
Tested up to: 4.2.2
Stable tag: 2.1.4
License: GPL2+
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A plugin that enabled you to embed a grid of images from eHive on your site.

== Description ==

This plugin is part of a suite of plugins created by Vernon Systems Ltd., which give you the power to embed eHive functionality into your WordPress website.

This plugin allows you display a grid of images from eHive. The images can be filtered using a search term and some sort information. Alternatively, you can choose to display interesting, popular, or recently added images to eHive. You can choose to return only images from your account or community by configuring the "Site type" setting in the eHive Access plugin.

Before you install this plugin you will need to install the eHive Access plugin. 

<span style="text-decoration: underline;">**Get more from the eHive plugin suite**</span>

To enhance the page you embed this plugin on you can also install the eHive Object details plugin to allow your users to click through to view the Object Records in detail.

Other plugins in the suite include:

* eHive Account Details - A plugin for displaying eHive account information.
* eHive Object Comments - Enables users to add comments to Object Records from your site.
* eHive Object Details - A plugin for displaying Object Record detail pages.
* eHive Objects Tag Cloud - Displays a tag cloud from eHive.
* eHive Search - Allows you to search eHive.
* eHive Objects Gallery widget - Provides a gallery of Object Records that can be placed in your sites widget areas.
* eHive Objects Tag Cloud widget - Allows you to display a tag cloud in a widget area on your site.
* eHive Object Tags widget - A widget that displays tags for an Object Record.
* eHive Search widget - A widget plugin that provides an access to eHive Search from a widget.

<div>
  <br />
</div>


== Installation ==
**Dependencies:**

* eHive Access plugin

This plugin requires the eHive Access plugin to be installed first. Please ensure you have installed and configured these plugins correctly first before installing this plugin.

There are three ways to install a plugin:

<span style="text-decoration: underline;">**Method 1**</span>


1.  Navigate to the plugins page by clicking the link in the WordPress admin menu.
2.  Click the "Add New" link
3.  Type the name of the plugin you want to install (i.e. "eHive Access plugin") into the search box
4.  Click the "Search plugins" button
5.  Locate the plugin you want to install from the search results
6.  Click the "Install Now" link and click "OK" on the popup confirmation window
7.  Click the "Activate plugin" link when the plugin installation has completed


<span style="text-decoration: underline;">**Method 2**</span>


1.  Download the plugin's .ZIP file.
2.  Navigate to the plugins page by clicking the link in the WordPress admin menu.
3.  Click the "Add New" link
4.  Click the "Upload" link
5.  Click the "Choose File" button and locate the .ZIP file you downloaded in step 1
6.  Click the "Install Now" button
7.  Click the "Activate plugin" link when the plugin installation has completed

<span style="text-decoration: underline;">**Method 3**</span>


1. Download the plugin's .ZIP file.
2. Unzip the contents into your WordPress sites plugin directory (<em>/wordpress/wp-contents/plugins</em>)
3. Navigate to the plugins page by clicking the link in the WordPress admin menu.
4. Click the "Activate plugin" link below the plugin's name

== Changelog ==
= 2.1.4 =
* Bug fix with sort and direction terms in the shortcode attributes.
* A valid short code with a search term and sort  [ehive_objects_image_grid explore_type="all" search_term='maker:Fred' sort="name" direction="desc"] 

= 2.1.3 =
* Bug fix, defaulting of shortcode attributes from options for columns, image_size and search_term corrected.
* Added version control for plugin options. Defaulting of new options without changing existing options is now possible.
* Added uninstall script to remove options from the database when the plugin is deleted. 

= 2.1.2 =
* Allow public, private and any content for interesting, popular and recent searches.

= 2.1.1 =
* First stable release of the eHive Access plugin. 

== Upgrade Notice ==
= 2.1.4 =
* Bug fix, error processing the sort and direction terms in the shortcode fixed. 
* A valid short code with a search term and sort  [ehive_objects_image_grid explore_type="all" search_term='maker:Fred' sort="name" direction="desc"] 

= 2.1.3 =
* Bug fix, defaulting of shortcode attributes from options for columns, image_size and search_term corrected.
* Added version control for plugin options. Defaulting of new options without changing existing options is now possible.
* Added uninstall script to remove options from the database when the plugin is deleted. 

= 2.1.2 =
* Allow public, private and any content for interesting, popular and recent searches.

= 2.1.1 =
This is the first stable release of the eHive Access plugin.

== Screenshots ==

1. screenshot_1.png
2. screenshot_2.png

== Frequently Asked Questions ==

Q. What is eHive?

A. eHive is an online collections management software package. See more at <a href="http://ehive.com/what_is_ehive" target="_blank" title="what is ehive?">What is eHive?</a>

<div>
	<br />
</div>

Q. What do these plugins do?

A. The eHive plugin suite gives you the ability to provide eHive functionality to your site's visitors. This means that you can search and display eHive records, leave comments that are visible also in ehive and add and remove tags to records where the record owner has given permission to do so. You can filter the search results by account or community if you want to display only records from a particular source. We also provide plugins to do other nice things like display grids of interesting, popular or recent images added to eHive; display galleries of other objects by the same account as a record you are viewing etc.

<div>
	<br />
</div>

Q. How do I get an API Key?

A. To get an API Key you will first need an active eHive account. If you don't have one you can <a href="http://ehive.com/signup/" title="sign up for an ehive account">sign up</a> for an account for free. Once you have an account you can navigate to the "Edit My Profile > Api Keys" page and create a new Key.


